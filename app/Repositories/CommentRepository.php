<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Events\CommentCreated;
use App\Repositories\AttachmentRepository;
use App\Models\AttachmentRelationship;
use Illuminate\Http\Request;
use App\Traits\ContentJson;

class CommentRepository
{
    use ContentJson;

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
    public function create(Request $request)
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
                'key' => \Uuid::uuid4(),
            ]);
            //创建详情
            $contentArr = self::getContents();
            $commentDetail = $comment->detail()->create([
                'content' => json_encode($contentArr['contents'], JSON_UNESCAPED_UNICODE),
            ]);
            //保存附件
            foreach ($contentArr['images'] as $image)
            {
                $attachment = $commentDetail->attachments()->create([
                    'uid' => \Auth::id(),
                    'key' => $image['key'],
                    'mime_type' => "image/" . $image['imageInfo']['format'],
                    'size' => $image['fsize'],
                    'width' => $image['imageInfo']['width'],
                    'height' => $image['imageInfo']['height'],
                    'shoot_time' => $image['exif'] ? self::getDateTimeFromExif($image['exif']) : null,
                    'latitude' => $image['exif'] ? self::getLatitudeFromExif($image['exif']) : null,
                    'longitude' => $image['exif'] ? self::getLongitudeFromExif($image['exif']): null,
                ]);
            }

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
            $count = $rootComment->comments()->count();
            $rootComment->update(['comment_count' => $count]);
            if ($count <= 5)
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
        
        return normalize(0, "回复成功", $comment);
    }
    
    /**
     * 更新评论。只有评论详情可以更新
     * 
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try
        {
            $comment = $this->comment->with(['detail', 'detail.attachments'])->findOrFail($id);
            $contentArr = self::getContents();
            $comment->detail()->update([
                'content' => json_encode($contentArr['contents'], JSON_UNESCAPED_UNICODE),
            ]);
            //原来拥有的(对象)
            $had = $comment->detail->attachments->pluck(null, 'key');
            //现在前端传递过来的(数组)
            $now = collect($contentArr['images'])->pluck(null, 'key');
            //要新增的
            $toAdd = $now->diffKeys($had);
            //要解除关联的
            $toRemove = $had->diffKeys($now);
//            dd($had, $now);
            $uid = \Auth::id();
            foreach ($toAdd as $image)
            {
                $comment->detail->attachments()->create([
                    'uid' => $uid,
                    'key' => $image['key'],
                    'mime_type' => "image/" . $image['imageInfo']['format'],
                    'size' => $image['fsize'],
                    'width' => $image['imageInfo']['width'],
                    'height' => $image['imageInfo']['height'],
                    'shoot_time' => $image['exif'] ? self::getDateTimeFromExif($image['exif']) : null,
                    'latitude' => $image['exif'] ? self::getLatitudeFromExif($image['exif']) : null,
                    'longitude' => $image['exif'] ? self::getLongitudeFromExif($image['exif']): null,
                ]);
            }
            if ($toRemove->isNotEmpty())
            {
                $toRemoveTargetIdArr = $toRemove->pluck('pivot.attachment_id')->all();
                $comment->detail->attachments()->detach($toRemoveTargetIdArr);
            }

            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $request->all());
        }
    
        return normalize(0, "更新成功", $comment);
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
            ->with([
                'user', 'detail', 'firstComments',
                'firstComments.user', 'firstComments.detail',
                'firstComments.parentComment', 'firstComments.parentComment.user',
            ])
            ->orderBy('floor_num', 'asc')
            ->paginate(request('per_page', Comment::ADMIN_TOPIC_SHOW_PER_PAGE));
//        dd($comments);

        return ['topic' => $topic, 'list' => $comments];
    }
    
    /**
     * 取所有评论
     * 
     * @param array $params
     * @return number[]|string[]|array[]
     */
    public function listAll(Request $request, array $otherParams = [])
    {
        $defaults = [
            'orderBy' => 'id desc',
            'with' => ['user', 'detail'],
            'per_page' => 15,
        ];
        $params = array_merge($defaults, $otherParams);
        $comments = $this->comment
        ->when($request->tid, function ($query) use ($request) {return $query->where('tid', $request->tid);})
        ->when($request->root_id, function ($query) use ($request) {return $query->where('root_id', $request->root_id);})
        ->when($request->begin_time, function ($query) use ($request) {return $query->where('created_at', '>=', $request->begin_time);})
        ->when($request->end_time, function ($query) use ($request) {return $query->where('created_at', '<=', $request->end_time);})
        ->with($params['with'])
        ->orderByRaw($params['orderBy'])
        ->paginate($request->get('per_page', $params['per_page']));
    
        return $comments;
    }

    public static function getContents()
    {
        $content = request()->get('content');
        $contentOriginalArr = json_decode($content, true);
        $contentArr = [];
        $imageArr = [];
        if ($contentOriginalArr && is_array($contentOriginalArr))
        {
            foreach ($contentOriginalArr as $item)
            {
                if ($item['type'] == CommentDetail::CONTENT_TYPE_TEXT)
                {
                    $contentArr[] = [
                        'type' => CommentDetail::CONTENT_TYPE_TEXT,
                        'data' => [
                            'text' => $item['data']['text'],
                        ],
                    ];
                }
                elseif ($item['type'] == CommentDetail::CONTENT_TYPE_IMAGE)
                {
                    //图片不需要保存那么多字段，比如exif不要放在详情字段里边
                    $key = $item['data']['key'];
                    $contentArr[] = [
                        'type' => CommentDetail::CONTENT_TYPE_IMAGE,
                        'data' => [
                            'key' => $key,
                            //新上传的与旧的不同格式
                            'width' => isset($item['data']['imageInfo']) ? $item['data']['imageInfo']['width'] : $item['data']['width'],
                            'height' => isset($item['data']['imageInfo']) ? $item['data']['imageInfo']['height'] : $item['data']['height'],
                        ],
                    ];
                    $imageArr[] = $item['data'];//图片全部信息返回
                }
            }
        }
        else
        {
            $contentArr[] = [
                'type' => CommentDetail::CONTENT_TYPE_TEXT,
                'data' => ['text' => (string)$content],
            ];
        }
        return ['contents' => $contentArr, 'images' => $imageArr];
    }

    public static function getDateTimeFromExif(array $exif)
    {
        foreach (['DateTime', 'DateTimeDigitized', 'DateTimeOriginal'] as $field)
        {
            if (empty($exif[$field]))
            {
                continue;
            }
            $dateTime = $exif[$field]['val'];
            if (!preg_match('/[\s]+/', $dateTime))
            {
                $dateTime .= " 00:00:00";
            }
            return $dateTime;
        }
    }

    public static function getLatitudeFromExif(array $exif)
    {
        if (!empty($exif['GPSLatitude']))
        {
            $valueArr = preg_split('/[\s,]+/', $exif['GPSLatitude']['val']);
            return $valueArr[0] + ($valueArr[1]/60) + ($valueArr[2]/3600);
        }
    }

    public static function getLongitudeFromExif(array $exif)
    {
        if (!empty($exif['GPSLongitude']))
        {
            $valueArr = preg_split('/[\s,]+/', $exif['GPSLongitude']['val']);
            return $valueArr[0] + ($valueArr[1]/60) + ($valueArr[2]/3600);
        }
    }

}