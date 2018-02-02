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

        $currentRouteName = \Route::currentRouteName();
        if (strpos($currentRouteName, 'huisuo') !== false)
        {
            $this->guessType = HuisuoJishi::TYPE_HUISUO;
        }
        elseif (strpos($currentRouteName, 'jishi') !== false)
        {
            $this->guessType = HuisuoJishi::TYPE_JISHI;
        }
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
        $data = $request->all();
        $data['type_flag'] = $this->typeFlag;
        if (\Auth::check())
        {
            $data['creator'] = \Auth::user()->name;
        }
        $result = $this->huisuoJishi->create($data);
        if ($result['ret'] == 0)
        {
            return normalize(0, '创建成功', $result['data']['data']->toArray());
        }
        else
        {
            return $result;
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
        $info = HuisuoJishi::with(['coverImage', 'contacts', 'contacts.image'])
        ->where("type_flag", $this->typeFlag)
        ->findOrFail($id);
        $contactTypes = Contact::listTypes();
        $pageTitle = "编辑" . $this->pageTitle;
//         dd($info);
        return view('admin.huisuo_jishi.edit', compact('info', 'pageTitle', 'contactTypes'));
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
        $data = $request->all();
        $data['type_flag'] = $this->typeFlag;
//         dd($data);
        $result = $this->huisuoJishi->update($data, $id);
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
