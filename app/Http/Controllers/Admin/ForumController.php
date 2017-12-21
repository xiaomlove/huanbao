<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Http\Requests\ForumRequest;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Forum::paginate(20);
        
        return view('admin.forum.index', compact('list'));
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
    public function create()
    {
        return view('admin.forum.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForumRequest $request)
    {
        $forum = Forum::create([
            'name' => $request->name,
            'slug' => empty($request->slug) ? urlencode($request->name) : $request->slug,
            'description' => strval($request->description),
            'pid' => intval($request->pid),
            'display_order' => intval($request->display_order),
        ]);
        return redirect()->route('forum.index');
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
        $forum = Forum::findOrFail($id);
        return view('admin.forum.edit', compact('forum'));
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
        $forum->update([
            'name' => $request->name,
            'slug' => empty($request->slug) ? urlencode($request->name) : $request->slug,
            'description' => strval($request->description),
            'pid' => intval($request->pid),
            'display_order' => intval($request->display_order),
        ]);
        return redirect()->route('forum.index');
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
        $deleteResult = $forum->delete();
        return response()->json([
            'ret' => $deleteResult ? 0 : 1,
            'msg' => '',
            'data' => $forum->toArray()
        ]);
    }
}
