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
        $params['with'] = ['user', 'forum', 'mainFloor', 'mainFloor.detail', 'lastComment', 'lastComment.user'];
        $result = $this->topic->listAll($params);
//         dd($result);
        if ($result['ret'] == 0)
        {
            $list = $result['data']['list'];
            return view('admin.topic.index', compact('list'));
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
    public function create(Topic $topic)
    {
        $forums = Forum::all();
        return view('admin.topic.form', compact('forums', 'topic'));
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
        $result = $this->topic->create($request);

        if ($result['ret'] == 0)
        {
            return redirect()->route("admin.topic.show", ['id' => $result['data']['topic']['id']])->with('success', $result['msg']);
        }
        else
        {
            return back()->withInput()->with('danger', $result['msg']);
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
        $result = $this->comment->listOfTopic($id);
//         dd($result);
        return view('admin.topic.show', $result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topic = Topic::with('mainFloor', 'mainFloor.detail')->findOrFail($id);
//        dd($topic);
        $forums = Forum::all();
        return view('admin.topic.form', compact('topic', 'forums'));
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
        $result = $this->topic->update($request, $id);
        if ($result['ret'] == 0)
        {
            return redirect()->route("admin.topic.show", ['id' => $result['data']['id']])->with('success', $result['msg']);
        }
        else
        {
            return back()->withInput()->with('danger', $result['msg']);
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
