<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Events\CommentCreated;

class CommentRepository
{
    protected $topic;
    
    protected $comment;
    
    protected $commentDetail;
    
    protected $user;
    
    public function __construct(Topic $topic, Comment $comment, CommentDetail $commentDetail, User $user)
    {
        $this->topic = $topic;
        $this->comment = $comment;
        $this->commentDetail = $commentDetail;
        $this->user = $user;
    }
    
    /**
     * 创建评论。当是主帖回复时更新楼层号，楼中楼不更新
     * 
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(array $data)
    {
        \DB::beginTransaction();
        try
        {
            $topic = $this->topic->findOrFail($data['tid']);
            $rootId = $pid = 0;
            $parentComment = null;
            
            //判断 pid是否有效
            if ($data['pid'] > 0)
            {
                $parentComment = $this->comment->where('id', $data['pid'])->where('tid', $topic->id)->firstOrFail();
                if ($parentComment->floor_num == 1)
                {
                    $parentComment = null;//回复的是主楼，相当于新楼层，不能对主楼评论进行回复
                }
            }
            if ($parentComment)
            {
                $pid = $parentComment->id;
                if ($parentComment->root_id > 0)
                {
                    $rootId = $parentComment->root_id;
                }
                else 
                {
                    $rootId = $parentComment->id;
                }
            }
            
            $comment = $this->comment->create([
                'uid' => $data['uid'],
                'tid' => $topic->id,
                'pid' => $pid,
                'root_id' => $rootId,
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
        //先插入，再更新楼层号
        \Log::info(__METHOD__ . ", pid = $pid");
        if ($pid == 0)
        {
            //非楼中楼，更新帖子的信息
            $count = $comment
            ->where('tid', '=', $topic->id)
            ->where('pid', 0)
            ->where('id', '<=', $comment->id)
            ->count();
            $comment->update(['floor_num' => $count]);
            \Log::info(__METHOD__ . ", topic: " . json_encode($topic));
            $topic->update([
                'comment_count' => $count,
                'last_comment_time' => time(),
                'last_comment_id' => $comment->id,
            ]);
        }
        else
        {
            //楼中楼，更新根评论的信息
            $count = $comment->where('tid', '=', $topic->id)->where('root_id', $rootId)->count();
            $this->comment->where('id', $rootId)->update(['comment_count' => $count]);
            $topic->update([
                'last_comment_time' => time(),
                'last_comment_id' => $comment->id,
            ]);
        }
        
        //插入评论成功，触发“评论添加事件”
        event(new CommentCreated($comment));
        
        return normalize(0, "OK", [$topic, $comment, $commentDetail]);
    }
    
    /**
     * 更新评论。只有评论详情可以更新
     * 
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(array $data, $id)
    {
        \DB::beginTransaction();
        try
        {
            $comment = $this->comment->findOrFail($id);
            $commentDetail = $this->commentDetail->where('cid', $comment->id)->firstOrFail();
            $commentDetail->update($data);
            
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $data);
        }
    
        return normalize(0, "OK", [$comment, $commentDetail]);
    }
    
    public function listAll($params = [])
    {
        $defaults = [
            'page' => 1,
            'per_page' => 10,
            'order' => 'floor_num asc',
            'not_in' => null,
            'tid' => null,
            'pid' => null, //父评论ID
            'root_id' => null, //根评论
            'include_total' => false, //是否包含数量
        ];
        $args = array_merge($defaults, $params);
        $offset = ($args['page'] - 1) * $args['per_page'];
        $where = [];
        if (!is_null($args['tid']) && ctype_digit(strval($args['tid'])))
        {
            $where[] = ['tid', '=', (int)$args['tid']];
        }
        if (!is_null($args['pid']) && ctype_digit(strval($args['pid'])))
        {
            $where[] = ['pid', '=', (int)$args['pid']];
        }
        if (!is_null($args['root_id']) && ctype_digit(strval($args['root_id'])))
        {
            $where[] = ['root_id', '=', (int)$args['root_id']];
        }
        
        $list = $this->comment
        ->where($where)
        ->orderByRaw(\DB::raw($args['order']))
        ->offset($offset)
        ->limit($args['per_page'])
        ->get();
        
        $count = null;
        if ($args['include_total'])
        {
            $count = $this->comment->where($where)->count();
        }
        $cidArr = [];
        $uidArr = [];
        foreach ($list as $value)
        {
            $cidArr[] = $value->id;
            $uidArr[] = $value->uid;
        }
        reset($list);
        unset($value);
        
        //获取详情
        $commentDetails = $this->commentDetail
        ->whereIn('cid', $cidArr)
        ->get()
        ->pluck(null, 'cid');
        
        //获取用户
        $uses = $this->user
        ->whereIn('id', array_unique($uidArr))
        ->get()
        ->pluck(null, 'id');
        
        //追加到评论列表中
        foreach ($list as &$value)
        {
            $value->user = $uses[$value->uid];
            $value->detail = $commentDetails[$value->id];
        }
        return normalize(0, "OK", ['list' => $list, 'total' => $count]);
    }
}