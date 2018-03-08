<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Repositories\CommentRepository;
use App\Models\Topic;
use App\Models\Comment;
use App\Transformers\CommentTransformer;
use App\Transformers\CommentCommentTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class CommentController extends Controller
{
    protected $comment;
    
    public function __construct(CommentRepository $comment)
    {
        $this->comment = $comment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $topic = Topic::where('key', $request->topic_key)->firstOrFail();

        $with = [
            'user',
            'detail', 'detail.attachments',
            'firstComments', 'firstComments.user', 'firstComments.detail',
            'firstComments.parentComment', 'firstComments.parentComment.user',
        ];
        $page = (int)$request->page;
        $comments = $topic->comments()
            ->where('pid', 0)
            ->with($with)
            ->when($page <= 1, function ($query) {$query->where('floor_num', '>', 1);})
            ->paginate($page <= 1 ? 9 : 10);

//        dd($comments);

        $commentsApiData = fractal()
            ->collection($comments)
            ->transformWith(new CommentTransformer())
            ->parseIncludes($with)
            ->paginateWith(new IlluminatePaginatorAdapter($comments))
            ->toArray();

//        dd($commentsApiData);

        return normalize(0, 'OK', [
            'list' => $commentsApiData['data'],
            'pagination' => $commentsApiData['meta']['pagination'],
        ]);
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
        $comment = Comment::with($with)->findOrFail($id);
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
