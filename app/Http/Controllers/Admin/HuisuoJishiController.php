<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HuisuoJishi;
use App\Models\Contact;
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
    public function index()
    {
        $huisuoJishi = new HuisuoJishi(['type' => $this->guessType]);

        $list = HuisuoJishi::where("type", $this->guessType)
            ->paginate(request('per_page', 20));

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
        $request->request->add(['type' => $this->guessType]);
//        dd($request->all());
        $result = $this->huisuoJishi->create($request);
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
        $huisuoJishi = HuisuoJishi::where("type", $this->guessType)->findOrFail($id);
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
