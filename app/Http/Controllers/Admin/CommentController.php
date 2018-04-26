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
            $list = $this->comment->listAll($request, [
                'orderBy' => 'id asc',
                'with' => ['user', 'detail', 'parentComment', 'parentComment.user'],
                'per_page' => 5,
            ]);
            $view = view('admin.topic.comment_comment', compact('list'));
            return normalize(0, 'OK', ['html' => $view->render()]);
        }
        else
        {
            $list = $this->comment->listAll($request, [
                'orderBy' => 'id desc',
                'with' => ['user', 'detail', 'topic', 'topic.forum', 'rootComment'],
            ]);
//            dd($list);
            return view('admin.comment.index', compact('list'));
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
        if ($result['ret'] == 0)
        {
            return redirect()->route('admin.comment.show', $result['data'])->with("success", $result['msg']);
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
        $comment = Comment::findOrFail($id);
        if ($comment->floor_num > 0)
        {
            //正常楼层
            $floorNum = $comment->floor_num;
        }
        else
        {
            $rootComment = $comment->rootComment()->firstOrFail();
            $floorNum = $rootComment->floor_num;
        }
        $page = ceil($floorNum / Comment::ADMIN_TOPIC_SHOW_PER_PAGE);
        return redirect()->route('admin.topic.show', ['tid' => $comment->tid, 'page' => $page]);
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
        if ($result['ret'] == 0)
        {
            return redirect()->route('admin.comment.show', $id);
        }
        else
        {
            return back()->with('danger', $result['msg']);
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
