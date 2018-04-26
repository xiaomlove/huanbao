<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Models\Forum;
use App\Models\AttachmentRelationship;
use App\Repositories\AttachmentRepository;
use Illuminate\Http\Request;
use App\Traits\ContentJson;

class TopicRepository
{
    use ContentJson;

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
    public function create(Request $request)
    {
        \DB::beginTransaction();
        try
        {
            //创建话题
            $topicData = $request->only(['title', 'fid']);
            $uid = \Auth::id();
            $topicData['uid'] = $uid;
            $topicData['key'] = \Uuid::uuid4();
            $topicData['last_comment_time'] = time();
            $topic = $this->topic->create($topicData);
            //创建主楼
            $comment = $topic->mainFloor()->create([
                'key' => \Uuid::uuid4(),
                'uid' => $topic->uid,
                'floor_num' => 1,//创建帖子时候创建的评论，肯定是1楼
            ]);
            //创建主楼详情
            $contentArr = CommentRepository::getContents();
            $commentDetail = $comment->detail()->create([
                'content' => json_encode($contentArr['contents'], JSON_UNESCAPED_UNICODE),
            ]);
            //保存附件
            foreach ($contentArr['images'] as $image)
            {
                $attachment = $commentDetail->attachments()->create([
                    'uid' => $uid,
                    'key' => $image['key'],
                    'mime_type' => "image/" . $image['imageInfo']['format'],
                    'size' => $image['fsize'],
                    'width' => $image['imageInfo']['width'],
                    'height' => $image['imageInfo']['height'],
                    'shoot_time' => $image['exif'] ? CommentRepository::getDateTimeFromExif($image['exif']) : null,
                    'latitude' => $image['exif'] ? CommentRepository::getLatitudeFromExif($image['exif']) : null,
                    'longitude' => $image['exif'] ? CommentRepository::getLongitudeFromExif($image['exif']): null,
                ]);
            }

            \DB::commit();

            return normalize(0, "发布成功", [
                'topic' => $topic,
            ]);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize($e->getMessage(), $request->all());
        }

    }
    
    /**
     * 更新帖子。有标题、所属版、以及详情可以更新
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try
        {
            $topic = $this->topic->with('mainFloor')->findOrFail($id);
            //更新话题
            $topic->update($request->only(['title', 'fid']));
            //更新主楼
            app(CommentRepository::class)->update($request, $topic->mainFloor->id);

            \DB::commit();

            return normalize(0, "更新成功", $topic);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize($e->getMessage(), $request->all());
        }
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