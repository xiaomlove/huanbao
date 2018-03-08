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
            ->when($request->forum_key, function ($query) use ($request) {
                $key = $request->forum_key;
                $query->whereHas("forum", function ($query) use ($key) {
                    $query->where("key", $key);
                });
            })
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
        $result = $this->topic->create($request);
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
        $topic = Topic::with('user')->where('key', $id)->firstOrFail();
        $apiData = fractal()
            ->item($topic)
            ->transformWith(new TopicTransformer())
            ->toArray();
        return normalize(0, 'OK', $apiData['data']);
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
