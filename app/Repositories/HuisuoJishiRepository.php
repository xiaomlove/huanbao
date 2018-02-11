<?php
namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;
use App\Models\HuisuoJishi;

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
    
    /**
     * 列出一个话题下的评论，话题详情页，包含楼中楼数据
     * 
     * @param array $params
     * @return number[]|string[]|array[]
     */
    public function listOfTopic($params = [])
    {
        $defaults = [
            'page' => 1,
            'per_page' => 10,
            'order' => 'id asc',
            'tid' => 0,
        ];
        $args = array_merge($defaults, $params);
        $where = [];
        if (empty($args['tid']) || !ctype_digit(strval($args['tid'])))
        {
            return normalize("非法tid: {$args['tid']}", $args);
        }
        $where[] = ['tid', '=', (int)$args['tid']];
        $where[] = ['pid', 0];
    
        $list = $this->comment
        ->where($where)
        ->with(['user', 'detail', 'attachments'])
        ->orderByRaw(\DB::raw($args['order']))
        ->paginate($args['per_page'], ['*'], 'page', $args['page']);
        
//         dd($list);
        //取楼中楼数据
        $commentCommentIdArr = [];
        foreach ($list->getIterator() as $item)
        {
            $ids = $item->first_comment_ids;
            if (!empty($ids))
            {
                $commentCommentIdArr = array_merge($commentCommentIdArr, explode(',', $ids));
            }
        }
//         dd($list);
        $commentComments = $this->comment
        ->whereIn('id', $commentCommentIdArr)
        ->with(['user', 'detail'])
        ->get()
        ->groupBy('root_id');
        
        foreach ($list->getIterator() as $item)
        {
            $rootId = $item->id;
            $firstComments = $commentComments->get($rootId);
            if ($firstComments)
            {
                $item->setRelation('first_comments', $firstComments);
            }
        }
        
        return normalize(0, "OK", ['list' => $list]);
    }
    
    /**
     * 取所有
     * 
     * @param array $params
     * @return number[]|string[]|array[]
     */
    public function listAll($params = [])
    {
        $defaults = [
            'page' => 1,
            'per_page' => 10,
            'order' => 'id asc',
            'type_flag' => null,
            'with' => [],
        ];
        $args = array_merge($defaults, $params);
        $where = [];
        if (!is_null($args['type_flag']))
        {
            $where[] = ['type_flag', '=', $args['type_flag']];
        }
    
        $list = $this->huisuoJishi
        ->where($where)
        ->with($args['with'])
        ->orderByRaw(\DB::raw($args['order']))
        ->paginate($args['per_page'], ['*'], 'page', $args['page']);
    
        return normalize(0, "OK", ['list' => $list]);
    }
}