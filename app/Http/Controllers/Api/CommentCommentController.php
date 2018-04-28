<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Transformers\CommentTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class CommentCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $with = ['user', 'detail', 'parentComment', 'parentComment.user'];
        $key = $request->root_comment_key;
        $comments = Comment::with($with)
            ->when($key, function ($query) use ($key) {
                $query->whereHas("rootComment", function ($query) use ($key) {
                    $query->where("key", $key);
                });
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

        //当第一交请求，同时返回根评论
        if ($key && $request->page <= 1 && $request->include_root_comment)
        {
            $with = ['user', 'detail'];
            $rootComment = Comment::with($with)->where("key", $key)->firstOrFail();
            $rootCommentApiData = fractal()
                ->item($rootComment)
                ->transformWith(new CommentTransformer())
                ->parseIncludes($with)
                ->toArray();
            array_unshift($list, $rootCommentApiData['data']);
        }

        $out = [
            'list' => $list,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
