<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Repositories\CommentRepository;
use App\Models\Topic;
use App\Models\Comment;
use App\Transformers\CommentTransformer;
use App\Transformers\TopicTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class CommentController extends Controller
{
    protected $comment;
    
    public function __construct(CommentRepository $comment)
    {
        $this->comment = $comment;
    }
    /**
     * 话题详情的回复列表，不区分什么主楼
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        \Log::info(sprintf("%s, request params: %s", __METHOD__, json_encode($request->all())));
        $with = [
            'user',
            'detail',
            'firstComments', 'firstComments.user', 'firstComments.detail',
            'firstComments.parentComment', 'firstComments.parentComment.user',
        ];
        $page = (int)$request->page;
        $topicKey = $request->topic_key;
        $commentKey = $request->comment_key;//回复后立即返回列表的结构，只需要新发的那条的信息
        $comments = Comment::with($with)
            ->when($topicKey, function ($query) use ($topicKey) {
                $query->whereHas('topic', function ($query) use ($topicKey) {
                    $query->where("key", $topicKey);
                });
            })
            ->when($commentKey, function ($query) use ($commentKey) {
                $query->where('key', $commentKey);
            })
            ->where('pid', 0)
            ->paginate($request->get('per_page', 10));

//        dd($comments);

        $commentsApiData = fractal()
            ->collection($comments)
            ->transformWith(new CommentTransformer())
            ->parseIncludes($with)
            ->paginateWith(new IlluminatePaginatorAdapter($comments))
            ->toArray();

//        dd($commentsApiData);

        $out = [
            'list' => $commentsApiData['data'],
            'pagination' => $commentsApiData['meta']['pagination'],
        ];

        return normalize(0, 'OK', $out);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        \Log::info(sprintf("%s, init request params: %s", __METHOD__, json_encode($request->all())));
        $result = $this->comment->create($request);
        if ($result['ret'] !== 0)
        {
            return $result;
        }
        \Log::info(sprintf("%s, create comment result: %s", __METHOD__, json_encode($result)));
        //立即返回列表，只包含当前最新的这条评论
        $toAppendParams = [
            'topic_key' => $result['data']['topic']->key,
            'comment_key' => $result['data']['comment']->key,
        ];
        \Log::info(sprintf("%s, I will add params: %s", __METHOD__, json_encode($toAppendParams)));
        $request->query->add($toAppendParams);

        \Log::info(sprintf("%s, I will set sbsb = sbsb", __METHOD__));
        $request->query->set('sbsb', 'sbsb');
        if ($request->pid)
        {
            $request->query->set("root_comment_key", $result['data']['root_comment']->key);
            //转发至评论的评论列表
            \Log::info(sprintf("%s, has pid: %s, goto comment, request params: %s", __METHOD__, $request->pid, json_encode($request->all())));
            return $this->comment($request);
        }
        else
        {
            //直接当前页的评论列表
            \Log::info(sprintf(
                "%s, no pid, goto index, request request params: %s, request params: %s",
                    __METHOD__,
                    json_encode($request->query->all()),
                    json_encode($request->all())
            ));
            return $this->index($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $with = ['user', 'detail', 'detail.attachments'];
        $comment = Comment::with($with)->where('key', $id)->firstOrFail();
        $commentApiData = fractal()
            ->item($comment)
            ->transformWith(new CommentTransformer())
            ->parseIncludes($with)
            ->toArray();

//        dd($commentApiData);

        return normalize(0, 'OK', $commentApiData['data']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 评论的评论列表
     *
     * @param Request $request
     * @return array
     */
    public function comment(Request $request)
    {
        \Log::info(sprintf("%s, request params: %s", __METHOD__, json_encode($request->all())));
        $with = ['user', 'detail', 'parentComment', 'parentComment.user'];
        $rootCommentKey = $request->root_comment_key;
        $commentKey = $request->comment_key;
        $includeRootComment = $request->include_root_comment;
        $comments = Comment::with($with)
            ->when($rootCommentKey, function ($query) use ($rootCommentKey) {
                $query->whereHas("rootComment", function ($query) use ($rootCommentKey) {
                    $query->where("key", $rootCommentKey);
                });
            })
            ->when($commentKey, function ($query) use ($commentKey) {
                $query->where("key", $commentKey);
            })
            ->paginate($request->get('per_page', 10));

//        dd($comments);

        $commentsApiData = fractal()
            ->collection($comments)
            ->transformWith(new CommentTransformer())
            ->parseIncludes($with)
            ->paginateWith(new IlluminatePaginatorAdapter($comments))
            ->toArray();

//        dd($commentsApiData);

        $list = $commentsApiData['data'];

        //当第一页时，同时返回根评论
        if ($rootCommentKey && $request->page <= 1 && $includeRootComment)
        {
            $with = ['user', 'detail'];
            $rootComment = Comment::with($with)->where("key", $rootCommentKey)->firstOrFail();
            $rootCommentApiData = fractal()
                ->item($rootComment)
                ->transformWith(new CommentTransformer())
                ->parseIncludes($with)
                ->toArray();
            $item = $rootCommentApiData['data'];
            $item['is_first'] = 1;
            array_unshift($list, $item);
        }

        $out = [
            'list' => $list,
            'pagination' => $commentsApiData['meta']['pagination'],
        ];

        return normalize(0, 'OK', $out);
    }
}
