<?php
namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;
use App\Models\HuisuoJishi;
use App\Models\Report;


class ReportRepository
{
    
    /**
     * 创建
     * 
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(Request $request)
    {
        \DB::beginTransaction();
        try
        {
            $report = Report::create($request->all());

            \DB::commit();
            return normalize(0, "新建成功", $report);
        }
        catch (\Exception  $e)
        {
            \DB::rollBack();
            return ["新建失败：" . $e->getMessage(), $request->all()];
        }
        
    }
    
    /**
     * 更新
     * 
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(Request $request, $id)
    {
        //主体
        $report = HuisuoJishi::findOrFail($id);
        \DB::beginTransaction();
        try
        {
            $report->update($request->all());
            
            \DB::commit();
            return normalize(0, "更新成功", $report);
        }
        catch (\Exception  $e)
        {
            \DB::rollBack();
            return ["创建失败：" . $e->getMessage(), $request->all()];
        }
    }

}