<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ForumTaxonomyRequest;
use App\Http\Controllers\Controller;
use App\Models\ForumTaxonomy;

class ForumTaxonomyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = ForumTaxonomy::paginate(request('per_page', 20));
        return view('admin.forumtaxonomy.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ForumTaxonomy $taxonomy)
    {
        return view('admin.forumtaxonomy.form', compact('taxonomy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForumTaxonomyRequest $request)
    {
        //
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
        $taxonomy = ForumTaxonomy::findOrFail($id);
        return view('admin.forumtaxonomy.form', compact('taxonomy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ForumTaxonomyRequest $request, $id)
    {
        $taxonomy = ForumTaxonomy::findOrFail($id);
        $taxonomy->update($request->all());
        return redirect()->route('admin.forumtaxonomy.index')->with("success", "更新成功");
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
