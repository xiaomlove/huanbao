<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Models\Forum;
use App\Models\AttachmentRelationship;
use App\Repositories\AttachmentRepository;

class TopicRepository
{
    protected $topic;
    
    protected $comment;
    
    protected $commentDetail;
    
    protected $user;
    
    protected $forum;
    
    protected $attachment;
    
    public function __construct
    (
        Topic $topic, 
        Comment $comment, 
        CommentDetail $commentDetail, 
        User $user, 
        Forum $forum,
        AttachmentRepository $attachment
    )
    {
        $this->topic = $topic;
        $this->comment = $comment;
        $this->commentDetail = $commentDetail;
        $this->user = $user;
        $this->forum = $forum;
        $this->attachment = $attachment;
    }
    
    /**
     * 创建帖子
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(array $data)
    {
        //先保存附件
        \Log::info(sprintf('%s, data: %s', __METHOD__, var_export($data, true)));
        $attachmentResult = $this->attachment->getFromRequestData($data);
        if ($attachmentResult['ret'] != 0)
        {
            return $attachmentResult;
        }
        //@see https://stackoverflow.com/questions/27230672/laravel-sync-how-to-sync-an-array-and-also-pass-additional-pivot-fields
        $attachments = [];
        foreach ($attachmentResult['data'] as $attachment)
        {
            $attachments[$attachment->id] = ['target_type' => AttachmentRelationship::TARGET_TYPE_COMMENT];
        }
        unset($attachmentResult, $attachment);
        \Log::info(sprintf('%s, attachmentsata: %s', __METHOD__, var_export($attachments, true)));
//         dd($attachments);
        
        \DB::beginTransaction();
        try
        {
            //创建话题
            $topic = $this->topic->create($data);
            //创建主楼
            $comment = $topic->main_floor()->create([
                'uid' => $topic->uid,
                'floor_num' => 1,//创建帖子时候创建的评论，肯定是1楼
            ]);
            //创建主楼详情
            $commentDetail = $comment->detail()->create(['content' => $data['content']]);
            //保存附件
            $comment->attachments()->sync($attachments);
            
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $data);
        }
        return normalize(0, "OK", [
            'topic' => $topic, 
            'comment' => $comment, 
            'comment_detail' => $commentDetail
            
        ]);
    }
    
    /**
     * 更新帖子。有标题、所属版、以及详情可以更新
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(array $data, $id)
    {
        //先保存附件
        $attachmentResult = $this->attachment->getFromRequestData($data);
        if ($attachmentResult['ret'] != 0)
        {
            return $attachmentResult;
        }
        //@see https://stackoverflow.com/questions/27230672/laravel-sync-how-to-sync-an-array-and-also-pass-additional-pivot-fields
        $attachments = [];
        foreach ($attachmentResult['data'] as $attachment)
        {
            $attachments[$attachment->id] = ['target_type' => AttachmentRelationship::TARGET_TYPE_COMMENT];
        }
        unset($attachmentResult, $attachment);
//         dd($attachments);
        
        \DB::beginTransaction();
        try
        {
            $topic = $this->topic->with('main_floor', 'main_floor.detail', 'main_floor.attachments')->findOrFail($id);
            $topic->update($data);
            $topic->main_floor->update($data);
            $topic->main_floor->detail->update($data);
            $topic->main_floor->attachments()->sync($attachments);
            
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $data);
        }
    
        return normalize(0, "OK", [
            'topic' => $topic, 
        ]);
    }
    
    public function listAll($params = [])
    {
        $defaults = [
            'page' => 1,
            'per_page' => 10,
            'order' => 'id desc',
            'fid' => null,
            'uid' => null,
            'with' => [],
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
        ->with($args['with'])
        ->where($where)
        ->orderByRaw(\DB::raw($args['order']))
        ->paginate($args['per_page'], ['*'], 'page', $args['page']);
    
        return normalize(0, "OK", ['list' => $list]);
    }
}