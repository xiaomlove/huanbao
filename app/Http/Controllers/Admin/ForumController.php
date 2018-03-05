<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumTaxonomy;
use App\Repositories\ForumRepository;
use App\Http\Requests\ForumRequest;

class ForumController extends Controller
{
    protected $forum;

    public function __construct(ForumRepository $forum)
    {
        $this->forum = $forum;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = $this->forum->listAll($request);
        $taxonomies = ForumTaxonomy::all();
        return view('admin.forum.index', compact('list', 'taxonomies'));
    }
    
    private function traverseTree($tree)
    {
        $arr = [$tree];
        while (!empty($arr))
        {
            $current = array_shift($arr);
            echo "当前节点：", $current->name, "，找到子孙如下：";
            $allChild = $this->retrieveAllChild($current);
            echo implode(',', $allChild), "<hr/>";
            foreach ($current->children as $child)
            {
                $arr[] = $child;
            }
        }
    }
    
    /**
     * 检索所有后代
     * 
     * @param unknown $tree
     * @return unknown[]
     */
    private function retrieveAllChild($node)
    {
        $result = [];
        $arr = [$node];
        while (!empty($arr))
        {
            $current = array_shift($arr);
            $result[] = $current->name;
            foreach ($current->children as $child)
            {
                $arr[] = $child;
            }
        }
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Forum $forum)
    {
        $taxonomies = ForumTaxonomy::all();
        return view('admin.forum.form', compact('forum', 'taxonomies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForumRequest $request)
    {
        $forum = Forum::create($request->all());
        $forum->taxonomies()->sync($request->taxonomies);
        return redirect()->route('admin.forum.index')->with('success', '版块创建成功');
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
        $forum = Forum::with('taxonomies')->findOrFail($id);
        $taxonomies = ForumTaxonomy::all();
        return view('admin.forum.form', compact('forum', 'taxonomies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ForumRequest $request, $id)
    {
        $forum = Forum::findOrFail($id);
        $forum->update($request->all());
        $forum->taxonomies()->sync($request->taxonomies);
        return redirect()->route('admin.forum.index')->with('success', "更新成功");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $forum = Forum::findOrFail($id);
        $forum->taxonomies()->detach();
        $forum->delete();
        return back()->with("success", "删除成功");
    }
}
