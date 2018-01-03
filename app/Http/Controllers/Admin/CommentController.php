<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Repositories\CommentRepository;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\Models\Topic;
use App\Repositories\UploadRepository;

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
        if ($request->expectsJson())
        {
            $result = $this->comment->listAll($request, [
                'orderBy' => 'id asc',
                'with' => ['user', 'detail', 'parentComment', 'parentComment.user'],
                'per_page' => 5,
            ]);
            $view = view('admin.topic.comment_comment', ['list' => $result['data']]);
            return normalize(0, 'OK', ['html' => $view->render()]);
        }
        else
        {
            $result = $this->comment->listAll($request);
            return view('admin.comment.index', compact('comments'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $topic = Topic::findOrFail($request->tid);
        $comment = new Comment();
        return view('admin.comment.form', compact('topic', 'comment'));
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
        if ($request->expectsJson())
        {
            return $result;
        }
        else
        {
            return back()->with('success', $result['msg']);
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
        $comment = Comment::with('detail')->findOrFail($id);
        $topic = Topic::findOrFail($comment->tid);
//         dd($comment);
        
        return view('admin.comment.form', compact('comment', 'topic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        $result = $this->comment->update($request, $id);
        if ($request->expectsJson())
        {
            return $result;
        }
        else
        {
            return back()->with('success', $result['msg']);
        }
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
