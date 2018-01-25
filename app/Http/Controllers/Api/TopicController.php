<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TopicRepository;
use App\Repositories\CommentRepository;
use App\Transformers\CommentTransformer;
use App\Transformers\TopicTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Models\Topic;
use App\Http\Requests\TopicRequest;

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
        $list = Topic::with(['user', 'mainFloor', 'mainFloor.detail', 'mainFloor.detail.attachments'])
            ->when($request->fid, function ($query) use ($request) {return $query->where("fid", $request->fid);})
            ->paginate($request->get('per_page', 10));

        dd($list);

        $apiData = fractal()
        ->collection($list)
        ->transformWith(new TopicTransformer())
        ->parseIncludes(['mainFloor', 'mainFloor.detail', 'mainFloor.detail.attachments'])
        ->paginateWith(new IlluminatePaginatorAdapter($list))
        ->toArray();

        return normalize(0, 'OK', [
            'list' => $apiData['data'], 
            'pagination' => $apiData['meta']['pagination']
            
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
    public function store(TopicRequest $request)
    {
        $data = $request->all();
        $data['uid'] = $this->apiUser()->id;
        $result = $this->topic->create($data);
        //         dd($result);
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
        $topic = Topic::with('user')->findOrFail($id);
        $params = [];
        $params['tid'] = $id;
        $params['per_page'] = request('per_page', 10);
        $params['page'] = request('page', 1);
        $params['order'] = request('order', 'id asc');
        $result = $this->comment->listOfTopic($params);
        if ($result['ret'] != 0)
        {
            return $result;
        }
        $commentList = $result['data']['list'];
        $comments = fractal()
        ->collection($commentList->getCollection())
        ->transformWith(new CommentTransformer())
        ->parseIncludes(['detail', 'attachments', 'first_comments'])
        ->paginateWith(new IlluminatePaginatorAdapter($commentList))
        ->toArray();
        
        $topicInfo = fractal()
        ->item($topic)
        ->transformWith(new TopicTransformer())
        ->toArray();
        
        return normalize(0, 'OK', [
            'list' => $comments['data'], 
            'pagination' => $comments['meta']['pagination'],
            'topic' => $topicInfo
        ]);
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
    public function update(TopicRequest $request, $id)
    {
        $data = $request->all();
        $result = $this->topic->update($data, $id);
        return $result;
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
