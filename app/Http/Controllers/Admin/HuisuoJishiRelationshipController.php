<?php

namespace App\Http\Controllers\Admin;

use App\Models\HuisuoJishi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HuisuoJishiRelationshipRequest;
use App\Models\HuisuoJishiRelationship;

class HuisuoJishiRelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = HuisuoJishiRelationship::when($request->huisuo_id, function ($query) use ($request) {$query->where('huisuo_id', $request->huisuo_id);})
            ->when($request->huisuo_name, function ($query) use ($request) {$query->where('huisuo_name', 'like', "%{$request->huisuo_name}%");})
            ->when($request->jishi_id, function ($query) use ($request) {$query->where('jishi_id', $request->jishi_id);})
            ->when($request->jishi_name, function ($query) use ($request) {$query->where('jishi_name', 'like', "%{$request->jishi_name}%");})
            ->paginate(request('per_page', 20));

        return view('admin.huisuo_jishi_relationship.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $relationship = new HuisuoJishiRelationship();
        $huisuoJishi = HuisuoJishi::where("id", $request->huisuo_id)->where('type', HuisuoJishi::TYPE_HUISUO)->firstOrFail();
        return view('admin.huisuo_jishi_relationship.form', compact('relationship', 'huisuoJishi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HuisuoJishiRelationshipRequest $request)
    {
        $huisuoJishi = HuisuoJishi::where('id', $request->jishi_id)->where('type', HuisuoJishi::TYPE_JISHI)->firstOrFail();
        $data = $request->all();
        $data['jishi_name'] = $huisuoJishi->name;
        $result = HuisuoJishiRelationship::create($data);
        return redirect()->route('admin.huisuojishi.index')->with('success', '创建成功');
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
