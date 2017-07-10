<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Models\Forum;

class TopicRepository
{
    protected $topic;
    
    protected $comment;
    
    protected $commentDetail;
    
    protected $user;
    
    protected $forum;
    
    public function __construct
    (
        Topic $topic, 
        Comment $comment, 
        CommentDetail $commentDetail, 
        User $user, 
        Forum $forum
    )
    {
        $this->topic = $topic;
        $this->comment = $comment;
        $this->commentDetail = $commentDetail;
        $this->user = $user;
        $this->forum = $forum;
    }
    
    /**
     * 创建帖子
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(array $data)
    {
        \DB::beginTransaction();
        try
        {
            $topic = $this->topic->create($data);
            $comment = $this->comment->create([
                'uid' => $topic->uid,
                'tid' => $topic->id,
                'floor_num' => 1,//创建帖子时候创建的评论，肯定是1楼
            ]);
            $commentDetail = $this->commentDetail->create([
                'cid' => $comment->id,
                'content' => $data['content'],
            ]);
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $data);
        }
        return normalize(0, "OK", [$topic, $comment, $commentDetail]);
    }
    
    /**
     * 更新帖子。有标题、所属版、以及详情可以更新
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(array $data, $id)
    {
        \DB::beginTransaction();
        try
        {
            $topic = $this->topic->findOrFail($id);
            $comment = $this->comment->where('tid', $topic->id)->where('floor_num', 1)->firstOrFail();
            $commentDetail = $this->commentDetail->where('cid', $comment->id)->firstOrFail();
            $topic->update($data);
            $commentDetail->update($data);
            
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $data);
        }
    
        return normalize(0, "OK", [$topic, $comment, $commentDetail]);
    }
    
    public function listAll($params = [])
    {
        $defaults = [
            'page' => 1,
            'per_page' => 10,
            'order' => 'id desc',
            'not_in' => null,
            'fid' => null,
            'uid' => null,
            'include_total' => false, //是否包含数量
        ];
        $args = array_merge($defaults, $params);
        $offset = ($args['page'] - 1) * $args['per_page'];
        $where = [];
        if (!is_null($args['fid']) && ctype_digit(strval($args['fid'])))
        {
            $where[] = ['fid', '=', (int)$args['fid']];
        }
        if (!is_null($args['uid']) && ctype_digit(strval($args['uid'])))
        {
            $where[] = ['uid', '=', (int)$args['uid']];
        }
    
        $list = $this->topic
        ->where($where)
        ->orderByRaw(\DB::raw($args['order']))
        ->offset($offset)
        ->limit($args['per_page'])
        ->get();
    
        $count = null;
        if ($args['include_total'])
        {
            $count = $this->topic->where($where)->count();
        }
        $fidArr = [];
        $uidArr = [];
        $idArr = [];
        $cidArr = [];
        foreach ($list as $value)
        {
            $fidArr[] = $value->fid;
            $uidArr[] = $value->uid;
            $idArr[] = $value->id;
            $cidArr[] = $value->last_comment_id;
        }
        reset($list);
        unset($value);
    
        //获取版块
        $forums = $this->forum
        ->whereIn('id', array_unique($fidArr))
        ->get()
        ->pluck(null, 'id');
    
        //获取最后回复
        $lastComments = $this->comment
        ->whereIn('id', array_unique($cidArr))
        ->get()
        ->pluck(null, 'id');
        
        //获取用户
        $uidArr = array_merge($uidArr, $lastComments->pluck('uid')->all());
        $uses = $this->user
        ->whereIn('id', array_unique($uidArr))
        ->get()
        ->pluck(null, 'id');
    
        //追加到主题列表中
        foreach ($list as &$value)
        {
            $value->user = $uses[$value->uid];
            $value->forum = $forums[$value->fid];
            if ($value->last_comment_id)
            {
                $value->last_comment = $lastComments[$value->last_comment_id];
                $value->last_comment_user = $uses[$value->last_comment->uid];
            }
        }
        return normalize(0, "OK", ['list' => $list, 'total' => $count]);
    }
}