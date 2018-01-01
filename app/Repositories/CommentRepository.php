<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Events\CommentCreated;
use App\Repositories\AttachmentRepository;
use App\Models\AttachmentRelationship;
use App\Http\Requests\CommentRequest;

class CommentRepository
{
    protected $topic;

    protected $comment;

    protected $commentDetail;

    protected $user;

    protected $attachment;

    public function __construct
    (
        Topic $topic,
        Comment $comment,
        CommentDetail $commentDetail,
        User $user,
        AttachmentRepository $attachment
    )
    {
        $this->topic = $topic;
        $this->comment = $comment;
        $this->commentDetail = $commentDetail;
        $this->user = $user;
        $this->attachment = $attachment;
    }
    
    /**
     * 创建评论。当是主帖回复时更新楼层号，楼中楼不更新
     * 
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(CommentRequest $request)
    {
        $topic = $this->topic->findOrFail($request->tid);
        $rootId = $pid = 0;
        $parentComment = null;
        
        //判断 pid是否有效
        if ($request->pid)
        {
            $parentComment = $topic->comments()->findOrFail($request->pid);
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
                $rootComment = $parentComment->rootComment()->findOrFail($rootId);
            }
            else
            {
                $rootId = $parentComment->id;
                $rootComment = $parentComment;
            }
        }
        
        \DB::beginTransaction();
        try
        {
            //创建评论
            $comment = $topic->comments()->create([
                'uid' => \Auth::id(),
                'pid' => $pid,
                'root_id' => $rootId,
            ]);
            //创建详情

            $commentDetail = $comment->detail()->create([
                'content' => $this->getContentJson($request),
            ]);

            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $request->all());
        }
        //先插入，再更新楼层号
        if ($pid == 0)
        {
            //非楼中楼，更新帖子的信息
            $count = $topic->comments()
            ->where('pid', 0)
            ->where('id', '<=', $comment->id)
            ->count();
            $comment->update(['floor_num' => $count]);
            $topic->update([
                'comment_count' => $count - 1, //主楼的不算
                'last_comment_time' => time(),
                'last_comment_id' => $comment->id,
            ]);
        }
        else
        {
            //楼中楼，更新根评论的信息
            $count = $comment->rootComment()->count();
            $rootComment->update(['comment_count' => $count]);
            if ($count < 5)
            {
                $rootComment->firstComments()->save($comment);
            }
            //更新帖子信息
            $topic->update([
                'last_comment_time' => time(),
                'last_comment_id' => $comment->id,
            ]);
        }
        
        //插入评论成功，触发“评论添加事件”
        event(new CommentCreated($comment));
        
        return normalize(0, "OK", $comment);
    }
    
    /**
     * 更新评论。只有评论详情可以更新
     * 
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(CommentRequest $request, $id)
    {
        \DB::beginTransaction();
        try
        {
            $comment = $this->comment->findOrFail($id);
            $comment->detail()->update([
                'content' => $this->getContentJson($request),
            ]);

            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $request->all());
        }
    
        return normalize(0, "OK", $comment);
    }
    
    /**
     * 列出一个话题下的评论，话题详情页，包含楼中楼数据
     * 
     * @param array $params
     * @return number[]|string[]|array[]
     */
    public function listOfTopic($id)
    {
        $topic = $this->topic->findOrFail($id);
        $comments = $this->comment
            ->where("tid", $topic->id)->where('pid', 0)
            ->with(['user', 'detail'])
            ->orderBy('floor_num', 'asc')
            ->paginate(request('per_page', 20));
//        dd($comments);

        return ['topic' => $topic, 'list' => $comments];
//         dd($list);
        //取楼中楼数据
        $commentCommentIdArr = [];
        foreach ($list->getIterator() as $item)
        {
            $ids = $item->first_comment_ids;
            if (!empty($ids))
            {
                $commentCommentIdArr = array_merge($commentCommentIdArr, explode(',', $ids));
            }
        }
//         dd($list);
        $commentComments = $this->comment
        ->whereIn('id', $commentCommentIdArr)
        ->with(['user', 'detail'])
        ->get()
        ->groupBy('root_id');

        foreach ($list->getIterator() as $item)
        {
            $rootId = $item->id;
            $firstComments = $commentComments->get($rootId);
            if ($firstComments)
            {
                $item->setRelation('first_comments', $firstComments);
            }
        }

        return normalize(0, "创建回复成功", ['list' => $list]);
    }
    
    /**
     * 取所有评论
     * 
     * @param array $params
     * @return number[]|string[]|array[]
     */
    public function listAll($params = [])
    {
        $defaults = [
            'page' => 1,
            'per_page' => 10,
            'order' => 'id asc',
            'tid' => 0,
            'root_id' => null,
            'with' => [],
        ];
        $args = array_merge($defaults, $params);
        $where = [];
        if (!is_null($args['tid']))
        {
            $where[] = ['tid', '=', (int)$args['tid']];
        }
        if (!is_null($args['root_id']))
        {
            $where[] = ['root_id', '=', (int)$args['root_id']];
        }
    
        $list = $this->comment
        ->where($where)
        ->with($args['with'])
        ->orderByRaw(\DB::raw($args['order']))
        ->paginate($args['per_page'], ['*'], 'page', $args['page']);
    
        return normalize(0, "OK", ['list' => $list]);
    }

    private function getContentJson(CommentRequest $request)
    {
        $content = $request->get('content', '');
        $contentArr = json_decode($content, true);
        if ($contentArr && is_array($contentArr))
        {
            return $content;
        }
        else
        {
            return json_encode([
                [
                    'type' => 'text',
                    'data' => ['text' => $content],
                ],
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}