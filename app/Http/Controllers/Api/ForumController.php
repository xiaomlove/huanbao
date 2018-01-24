<?php

namespace App\Http\Controllers\Api;

use App\Models\ForumTaxonomy;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ForumRepository;
use App\Transformers\ForumTransformer;
use App\Transformers\ForumTaxonomyTransformer;

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
    public function index()
    {
        $list = ForumTaxonomy::with('forums')->get();
        $apiData = fractal()
            ->collection($list)
            ->transformWith(new ForumTaxonomyTransformer())
            ->parseIncludes('forums')
            ->toArray();

        return normalize(0, "OK", ['list' => $apiData['data']]);
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
    public function store(Request $request)
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
