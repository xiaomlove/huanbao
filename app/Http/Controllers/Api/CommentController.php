<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Repositories\CommentRepository;
use App\Models\Topic;
use App\Transformers\CommentCommentTransformer;

class CommentController extends Controller
{
    
    public function __construct(CommentRepository $comment)
    {
        $this->comment = $comment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tid = request('tid');
        $topic = Topic::findOrFail($tid);
        $params = [];
        $params['tid'] = $tid;
        $params['root_id'] = request('root_id');
        $params['per_page'] = request('per_page', 10);
        $params['page'] = request('page', 1);
        $params['order'] = request('order', 'id asc');
        $params['with'] = ['user', 'detail'];
        $result = $this->comment->listAll($params);
        if ($result['ret'] == 0)
        {
            $data = $result['data']['list'];
//             dd($data->items());
            $comments = fractal()
            ->collection($data->items())
            ->transformWith(new CommentCommentTransformer())
            ->toArray();
            
            $out = $data->toArray();
            unset($out['data']);
            $out['list'] = $comments;
            return normalize(0, 'OK', $out);
        }
        else
        {
            return $result;
        }
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
        $data = $request->all();
        $data['uid'] = $this->apiUser()->id;
        $data['pid'] = $request->get('pid', 0);
        $result = $this->comment->create($data);
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
