<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TopicRepository;
use App\Repositories\CommentRepository;
use App\Transformers\ForumTransformer;
use App\Transformers\TopicTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Models\Topic;
use App\Models\Forum;
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
        $forumKey = $request->forum_key;
        $topicKey = $request->topic_key;
        $list = Topic::with($with)
            ->when($forumKey, function ($query) use ($forumKey) {
                $query->whereHas("forum", function ($query) use ($forumKey) {
                    $query->where("key", $forumKey);
                });
            })
            ->when($topicKey, function ($query) use ($topicKey) {
                $query->where('key', $topicKey);
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

        $out = [
            'list' => $apiData['data'],
            'pagination' => $apiData['meta']['pagination']
        ];

        //若是第一页，包含版块信息
        if ($forumKey && $request->page <= 1 && $request->include_forum)
        {
            $forum = Forum::where("key", $forumKey)->firstOrFail();
            $apiData = fractal()
                ->item($forum)
                ->transformWith(new ForumTransformer($forum))
                ->toArray();
            $out['forum'] = $apiData;
        }

        return normalize(0, 'OK', $out);
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
        if ($result['ret'] !== 0)
        {
            return $result;
        }
        $request->query->add([
            'forum_key' => $result['data']['forum']->key,
            'topic_key' => $result['data']['topic']->key,
        ]);
        return $this->index($request);
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
