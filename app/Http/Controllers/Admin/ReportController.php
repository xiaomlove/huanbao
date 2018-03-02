<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Http\Requests\ReportRequest;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    protected $report;

    public function __construct(ReportRepository $report)
    {
        $this->report = $report;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = Report::when($request->jishi_id, function ($query) use ($request) {$query->where("jishi_id", $request->jishi_id);})
            ->when($request->jishi_name, function ($query) use ($request) {$query->where("jishi_name", 'like', "%{$request->jishi_name}%");})
            ->when($request->huisuo_id, function ($query) use ($request) {$query->where("huisuo_id", $request->huisuo_id);})
            ->when($request->huisuo_name, function ($query) use ($request) {$query->where("huisuo_name", 'like', "%{$request->huisuo_name}%");})
            ->paginate($request->get('per_page', 20));
        return view('admin.report.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $report = new Report();
        return view('admin.report.form', compact('report'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
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
    public function update(ReportRequest $request, $id)
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
