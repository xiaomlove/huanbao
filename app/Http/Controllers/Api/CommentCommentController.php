<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Transformers\CommentCommentTransformer;
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
        $comments = Comment::where("root_id", $request->root_id)
            ->with($with)
            ->paginate($request->get('per_page', 10));

//        dd($comments);

        $commentsApiData = fractal()
            ->collection($comments)
            ->transformWith(new CommentCommentTransformer())
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
