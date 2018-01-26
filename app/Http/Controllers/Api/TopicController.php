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
        $with = ['user', 'mainFloor', 'mainFloor.detail', 'mainFloor.detail.attachments'];
        $list = Topic::with($with)
            ->when($request->fid, function ($query) use ($request) {return $query->where("fid", $request->fid);})
            ->paginate($request->get('per_page', 10));

//        dd($list);

        $apiData = fractal()
        ->collection($list)
        ->transformWith(new TopicTransformer())
        ->parseIncludes($with)
        ->paginateWith(new IlluminatePaginatorAdapter($list))
        ->toArray();

//        dd($apiData);

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
        $topicApiData = fractal()
            ->item($topic)
            ->transformWith(new TopicTransformer())
            ->toArray();

        $with = [
            'user',
            'detail', 'detail.attachments',
            'firstComments', 'firstComments.user', 'firstComments.detail',
            'firstComments.parentComment', 'firstComments.parentComment.user',
        ];
        $comments = $topic->comments()
            ->where('pid', 0)
            ->with($with)
            ->paginate(request()->get('per_page', 10));

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
            'topic' => $topicApiData['data'],
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
