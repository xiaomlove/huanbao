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
    protected $typeFlag;
    
    protected $pageTitle;
    
    protected $huisuoJishi;
    
    public function __construct(HuisuoJishiRepository $huisuoJishi)
    {
        $currentRouteName = \Route::currentRouteName();
        if (strpos($currentRouteName, 'huisuo') !== false)
        {
            $this->typeFlag = HuisuoJishi::TYPE_FLAG_HUISUO;
            $this->pageTitle = '会所';
        }
        elseif (strpos($currentRouteName, 'jishi') !== false)
        {
            $this->typeFlag = HuisuoJishi::TYPE_FLAG_JISHI;
            $this->pageTitle = '技师';
        }
        else
        {
            return response('非法访问', 500);
        }
        
        $this->huisuoJishi = $huisuoJishi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [];
        $params['per_page'] = 10;
        $params['page'] = request('page', 1);
        $params['type_flag'] = $this->typeFlag;
        $params['with'] = ['coverImage', 'contacts', 'contacts.image'];
        $result = $this->huisuoJishi->listAll($params);
//         dd($result);
        if ($result['ret'] == 0)
        {
            $list = $result['data']['list'];
            $pageTitle = $this->pageTitle;
            $typeFlag = $this->typeFlag;
            return view('admin.huisuo_jishi.index', compact('list', 'pageTitle', 'typeFlag'));
        }
        else
        {
            return response($result['msg'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "新建" . $this->pageTitle;
        $contactTypes = Contact::listTypes();
        $info = new HuisuoJishi();
        return view('admin.huisuo_jishi.create', compact('info', 'pageTitle', 'contactTypes'));
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
