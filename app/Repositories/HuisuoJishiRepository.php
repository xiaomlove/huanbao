<?php
namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;
use App\Models\HuisuoJishi;
use App\Models\HuisuoJishiRelationship;
use App\Http\Requests\HuisuoJishiRelationshipRequest;
use Carbon\Carbon;

class HuisuoJishiRepository
{
    
    /**
     * 创建会所或技师
     * 
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(Request $request)
    {
        \DB::beginTransaction();
        try
        {
            //创建会所或技师
            $huisuoJishi = HuisuoJishi::create($request->all());

            \DB::commit();
            return normalize(0, "新建成功", $huisuoJishi);
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
        $huisuoJishi = HuisuoJishi::findOrFail($id);
        \DB::beginTransaction();
        try
        {
            $huisuoJishi->update($request->all());
            
            \DB::commit();
            return normalize(0, "更新成功", $huisuoJishi);
        }
        catch (\Exception  $e)
        {
            \DB::rollBack();
            return ["创建失败：" . $e->getMessage(), $request->all()];
        }
    }

    public function createRelationship(HuisuoJishiRelationshipRequest $request)
    {
        $jishi = HuisuoJishi::where('id', $request->jishi_id)->where('type', HuisuoJishi::TYPE_JISHI)->firstOrFail();
        $data = $request->all();
        $data['jishi_name'] = $jishi->name;
        if (!empty($data['end_time']))
        {
            if ($data['begin_time'] > $data['end_time'])
            {
                return normalize('结束时间要大于开始时间');
            }
        }
        $now = Carbon::now()->toDateTimeString();
        //一个JS不能有多个有效的HS，需要先结束其他的
        $huisuo = $jishi->huisuos()
            ->whereNull('end_time')
            ->first();
        if ($huisuo)
        {
            return normalize(sprintf(
                "在HS: %s(ID: %s, 开始时间: %s)的记录: %s 并没有结束",
                $huisuo->huisuo_name, $huisuo->huisuo_id, $huisuo->begin_time, $huisuo->id
            ));
        }

        //开始时间与结束时间都不要落入其他已有记录中，不与其他时间段冲突




        $result = HuisuoJishiRelationship::create($data);
        return normalize(0, "创建关联成功", $result);
    }
}