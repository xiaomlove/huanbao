<?php

namespace App\Http\Controllers\Admin;

use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HuisuoJishi;
use App\Models\CommentDetail;
use App\Repositories\HuisuoJishiRepository;
use App\Http\Requests\HuisuoJishiRequest;

class HuisuoJishiController extends Controller
{
    protected $huisuoJishi;

    protected $guessType;
    
    public function __construct(HuisuoJishiRepository $huisuoJishi)
    {
        $this->huisuoJishi = $huisuoJishi;

        $guessType = HuisuoJishi::getGuessType();
        $this->guessType = $guessType['type'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $huisuoJishi = new HuisuoJishi(['type' => $this->guessType]);

        $list = HuisuoJishi::where("type", $this->guessType)
            ->when($request->name, function ($query) use ($request) {
                $name = $request->name;
                return $query->where(function ($query) use ($name) {
                    return $query->where("name", "like", "%{$name}%")->orWhere("short_name", "like", "%{$name}%");
                });
            })
            ->paginate(request('per_page', 20));
        if ($request->wantsJson())
        {
            return normalize(0, "OK", array_only($list, ["id", "name", "short_name"]));
        }
        return view('admin.huisuo_jishi.index', compact('list', 'huisuoJishi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $huisuoJishi = new HuisuoJishi(['type' => $this->guessType]);

        return view('admin.huisuo_jishi.form', compact('huisuoJishi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HuisuoJishiRequest $request)
    {
        $request->request->add([
            'type' => $this->guessType,
            //帖子所需要参数
            'fid' => $this->guessType == HuisuoJishi::TYPE_HUISUO ? Forum::ID_HUISUO : Forum::ID_JISHI,
            'title' => $request->name,
        ]);
//        dd($request->all());
//        $contentJsonString = CommentDetail::getContentJsonString();
//        dd($contentJsonString);
        $result = $this->huisuoJishi->create($request);
        if ($result['ret'] == 0)
        {
            return redirect()->route("admin.{$this->guessType}.index")->with('success', $result['msg']);
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
        $huisuoJishi = HuisuoJishi::where("type", $this->guessType)
            ->with(['topic', 'topic.mainFloor', 'topic.mainFloor.detail'])
            ->findOrFail($id);
//        dd($huisuoJishi);
        return view('admin.huisuo_jishi.form', compact('huisuoJishi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HuisuoJishiRequest $request, $id)
    {
        $result = $this->huisuoJishi->update($request, $id);
        if ($request->expectsJson())
        {
            return $result;
        }
        else
        {
            return redirect()->route("admin.{$this->guessType}.index")->with('success', $result['msg']);
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
