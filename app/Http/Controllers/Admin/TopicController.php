<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Forum;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\Http\Requests\TopicRequest;
use App\Repositories\TopicRepository;
use App\Repositories\CommentRepository;


class TopicController extends Controller
{
    protected $topic;
    
    protected $comment;
    
    public function __construct(TopicRepository $topic, CommentRepository $comment)
    {
        $this->topic = $topic;
        $this->comment = $comment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = [];
        $params['fid'] = request('fid');
        $params['uid'] = request('uid');
        $params['include_total'] = true;
        $params['per_page'] = 10;
        $params['page'] = request('page', 1);
        $result = $this->topic->listAll($params);
//         dd($result);
        if ($result['ret'] == 0)
        {
            $list = $result['data']['list'];
            $total = $result['data']['total'];
            $paginator = new \LengthAwarePaginator($list, $total, $params['per_page'], $params['page'], ['path' => url()->current()]);
            return view('admin.topic.index', compact('list', 'paginator'));
        }
        else
        {
            return response($result['msg'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $forums = (new Forum())->listTreeOneDimensional();
        $forumOptions = (object)[
            'name' => 'fid',
            'selected' => null,
        ];
        return view('admin.topic.create', compact('forums', 'forumOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request)
    {
        $data = $request->all();
        $data['uid'] = \Auth::user()->id;
        $result = $this->topic->create($data);
//         dd($result);
        if ($result['ret'] == 0)
        {
            return redirect()->route('topic.create')->with("success", "新建话题成功");
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
        $topic = Topic::findOrFail($id);
        $isAjax = request()->ajax();
        $params = [];
        $params['tid'] = $id;
        $params['root_id'] = request('root_id');
        $params['pid'] = $isAjax ? null : 0;
        $params['include_total'] = true;
        $params['per_page'] = 10;
        $params['page'] = request('page', 1);
        $result = $this->comment->listAll($params);
//         dd($params, $result);
        if ($result['ret'] == 0)
        {
            $list = $result['data']['list'];
            $total = $result['data']['total'];
            $paginator = new \LengthAwarePaginator($list, $total, $params['per_page'], $params['page'], ['path' => url()->current()]);
            if (request()->ajax())
            {
                $view = view('admin.topic.comment_comment', compact('list', 'paginator'));
                return normalize(0, "OK", ['html' => $view->render()]);
            }
            else
            {
                return view('admin.topic.show', compact('topic', 'list', 'paginator'));
            }
        }
        else
        {
            return response($result['msg'], 500);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $comment = Comment::where('tid', $topic->id)->where('floor_num', 1)->firstOrFail();
        $commentDetail = CommentDetail::where('cid', $comment->id)->firstOrFail();
        $forums = (new Forum())->listTreeOneDimensional();
        $forumOptions = (object)[
            'name' => 'fid',
            'selected' => $topic->fid,
        ];
        return view('admin.topic.edit', compact('topic', 'forums', 'forumOptions', 'commentDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, $id)
    {
        $data = $request->all();
        $result = $this->topic->update($data, $id);
        if ($result['ret'] == 0)
        {
            return redirect()->route('topic.edit', $id)->with("success", "更新话题成功");
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
