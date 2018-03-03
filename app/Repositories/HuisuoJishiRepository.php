<?php
namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;
use App\Models\HuisuoJishi;
use App\Models\HuisuoJishiRelationship;
use App\Http\Requests\HuisuoJishiRelationshipRequest;

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
            //创建帖子
            $request->request->add([

            ]);
            $topicResult = app(TopicRepository::class)->create($request);

            $data = $request->all();
            $data['key'] = \Uuid::uuid4();
            $huisuoJishi = HuisuoJishi::create($data);

            \DB::commit();
            return normalize(0, "新建成功", $huisuoJishi);
        }
        catch (\Exception  $e)
        {
            \DB::rollBack();
            return normalize("新建失败：" . $e->getMessage(), $request->all());
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
            return normalize("创建失败：" . $e->getMessage(), $request->all());
        }
    }

    public function createRelationship(HuisuoJishiRelationshipRequest $request)
    {
        $result = $this->validateRelationshipData($request);
        if ($result['ret'] != 0)
        {
            return $result;
        }
        $relationship = HuisuoJishiRelationship::create($result['data']);

        return normalize(0, "创建关联成功", $relationship);
    }

    public function updateRelationship(HuisuoJishiRelationshipRequest $request, $id)
    {
        $relationship = HuisuoJishiRelationship::findOrFail($id);
        $result = $this->validateRelationshipData($request, $id);
        if ($result['ret'] != 0)
        {
            return $result;
        }
        $relationship->update($result['data']);

        return normalize(0, "更新关联成功", $relationship);
    }

    /**
     * 验证关联数据并返回之
     *
     * @param HuisuoJishiRelationshipRequest $request
     * @return array
     */
    private function validateRelationshipData(HuisuoJishiRelationshipRequest $request, $id = null)
    {
        $jishi = HuisuoJishi::jishi()->findOrFail($request->jishi_id);
        $data = $request->all();
        $data['jishi_name'] = $jishi->name;
        if (!empty($data['end_time']))
        {
            if ($data['begin_time'] > $data['end_time'])
            {
                return normalize('结束时间要大于开始时间');
            }
        }

        $maxBeginTime = $jishi->huisuos()->max('begin_time');
        $maxEndTime = $jishi->huisuos()->max('end_time');

        if (empty($data['end_time']))
        {
            //如果没有结束时间，开始时间必须大于现有的最大的结束时间
            if ($data['begin_time'] < $maxBeginTime)
            {
                return normalize(sprintf("没有结束时间，开始时间必须大于现有最大开始时间 $maxBeginTime"));
            }
            if ($maxEndTime && $data['begin_time'] < $maxEndTime)
            {
                return normalize(sprintf("没有结束时间，开始时间必须大于现有最大结束时间 $maxEndTime"));
            }
            //一个JS不能有多个有效的HS，需要先结束其他的
            $huisuoNotEnd = $jishi->huisuos()
                ->when($id, function ($query) use ($id) {$query->where("id", "!=", $id);})
                ->whereNull('end_time')
                ->first();
            if ($huisuoNotEnd)
            {
                return normalize(sprintf(
                    "在HS: %s(ID: %s, 开始时间: %s)的记录: %s 并没有结束",
                    $huisuoNotEnd->huisuo_name, $huisuoNotEnd->huisuo_id, $huisuoNotEnd->begin_time, $huisuoNotEnd->id
                ));
            }
        }
        else
        {
            //有结束时间，时间段不与其他时间段冲突
            $huisuoCross = $jishi->huisuos()
                ->when($id, function ($query) use ($id) {$query->where("id", "!=", $id);})
                ->where('begin_time', '<=', $data['end_time'])
                ->where('end_time', '>=', $data['begin_time'])
                ->first();
            if ($huisuoCross)
            {
                return normalize(sprintf(
                    "时间段 %s~%s 与现有记录 %s(时间：%s~%s, HS: %s(%s)) 冲突",
                    $data['begin_time'], $data['end_time'],
                    $huisuoCross['id'], $huisuoCross['begin_time'], $huisuoCross['end_time'],
                    $huisuoCross['huisuo_name'], $huisuoCross['huisuo_id']
                ));
            }
        }
        return normalize(0, 'OK', $data);
    }
}