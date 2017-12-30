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
        $isAjax = $request->ajax();
        $params = [];
        $params['tid'] = request('tid');
        $params['root_id'] = request('root_id');
        $params['per_page'] = request('per_page', 10);
        $params['page'] = request('page', 1);
        
        if ($isAjax)
        {
            $params['order'] = request('order', 'id asc');
            $params['with'] = ['user', 'detail'];
        }
        else
        {
            $params['order'] = request('order', 'id desc');
            $params['with'] = ['user', 'detail', 'topic', 'topic.forum', 'rootComment'];
        }
        
        $result = $this->comment->listAll($params);
//         dd($result);
        
        if ($result['ret'] == 0)
        {
            $comments = $result['data']['list'];
            if ($isAjax)
            {
                $view = view('admin.topic.comment_comment', compact('comments'));
                return normalize(0, 'OK', ['html' => $view->render()]);
            }
            else 
            {
                return view('admin.comment.index', compact('comments'));
            }
        }
        else
        {
            return normalize($result['msg'], \Input::all());
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
        $data = $request->all();
        $data['uid'] = \Auth::user()->id;
        $data['pid'] = $request->get('pid', 0);
        $result = $this->comment->create($data);
        //         dd($result);
        if ($result['ret'] == 0)
        {
            return redirect()->route('topic.show', $data['tid'])->with("success", "发表回复成功");
        }
        else
        {
            return back()->with("danger", $result['msg'])->withInput();
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
        $comment = Comment::with('topic', 'detail', 'attachments')->findOrFail($id);
//         dd($comment);
        
        return view('admin.comment.edit', compact('comment'));
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
        $data = $request->all();
        $data['uid'] = \Auth::user()->id;
        $result = $this->comment->update($data, $id);
//                 dd($result);
        if ($result['ret'] == 0)
        {
            return redirect()->route('comment.edit', $id)->with("success", "编辑回复成功");
        }
        else
        {
            return back()->with("danger", $result['msg'])->withInput();
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
