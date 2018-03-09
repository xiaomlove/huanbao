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
     * 话题详情的回复列表，首页不包含主楼，且比其他页少取一条(楼层 >= 1)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $with = [
            'user',
            'detail', 'detail.attachments',
            'firstComments', 'firstComments.user', 'firstComments.detail',
            'firstComments.parentComment', 'firstComments.parentComment.user',
        ];
        $page = (int)$request->page;
        $perpage = 10;
        $key = $request->topic_key;
        $comments = Comment::with($with)
            ->when($key, function ($query) use ($key) {
                $query->whereHas('topic', function ($query) use ($key) {
                    $query->where("key", $key);
                });
            })
            ->where('pid', 0)
            ->when($page <= 1, function ($query) {$query->where('floor_num', '>', 1);})
            ->paginate($page <= 1 ? ($perpage - 1) : $perpage);

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

        //如果是首页，并要求话题(第一次加载要，后边下拉刷新不需要)，把话题一并返回
        if ($key && $page <= 1 && $request->include_topic == 1)
        {
            $topic = Topic::with('user')->where('key', $key)->firstOrFail();
            $topicApiData = fractal()
                ->item($topic)
                ->transformWith(new TopicTransformer())
                ->toArray();
            $out['topic'] = $topicApiData;
        }

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
        $result = $this->comment->create($request);

        return $result;
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
}
