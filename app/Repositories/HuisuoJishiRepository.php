<?php
namespace App\Repositories;

use App\Models\Topic;
use App\Models\Comment;
use App\Models\CommentDetail;
use App\User;
use App\Events\CommentCreated;
use App\Repositories\AttachmentRepository;
use App\Models\AttachmentRelationship;
use App\Models\HuisuoJishi;
use App\Models\Contact;

class HuisuoJishiRepository
{
    protected $huisuoJishi;
    
    protected $contact;
    
    public function __construct
    (
        HuisuoJishi $huisuoJishi,
        Contact $contact
    )
    {
        $this->huisuoJishi = $huisuoJishi;
        $this->contact = $contact;
    }
    
    /**
     * 创建会所或技师
     * 
     * @param array $data
     * @return number[]|string[]|array[]
     */
    public function create(array $data)
    {
        //整理联系人
        $contactTypes = $this->contact->listTypes();
        $contactData = [];
        foreach ($data['contacts']['type'] as $k => $type)
        {
            if (!isset($contactTypes[$type]))
            {
                continue;
            }
            $contactData[] = [
                'type' => $type,
                'account' => $data['contacts']['account'][$k],
                'image_id' => $data['contacts']['image'][$k],
            ];
        }
        \DB::beginTransaction();
        try
        {
            //创建会所或技师
            $huisuoJishi = $this->huisuoJishi->create($data);
            //创建联系人
            if (!empty($contactData))
            {
                $contactCreated = [];
                foreach ($contactData as $_contact)
                {
                    $contact = $this->contact->create($_contact);
                    $contactCreated[$contact->id] = ['owner_type' => $data['type_flag']];
                }
                //创建关联
                $huisuoJishi->contacts()->sync($contactCreated);
            }
            \DB::commit();
            return normalize(0, "OK", ['data' => $huisuoJishi]);
        }
        catch (\Exception  $e)
        {
            \DB::rollBack();
            return ["创建失败：" . $e->getMessage(), $data];
        }
        
    }
    
    /**
     * 更新
     * 
     * @param array $data
     * @param unknown $id
     * @return number[]|string[]|array[]
     */
    public function update(array $data, $id)
    {
        //主体
        $huisuoJishi = $this->huisuoJishi->find($id);
        if (empty($huisuoJishi))
        {
            return ["id为 {$id} 的目标不存在", $data];
        }
        //整理联系人
        $contactTypes = $this->contact->listTypes();
        $contactToBeUpdate = [];
        $contactToBeCreate = [];
        foreach ($data['contacts']['type'] as $k => $type)
        {
            if (!isset($contactTypes[$type]))
            {
                continue;
            }
            if (isset($data['contacts']['id'][$k]))
            {
                $contactToBeUpdate[$data['contacts']['id'][$k]] = [
                    'type' => $type,
                    'account' => $data['contacts']['account'][$k],
                    'image_id' => (int)$data['contacts']['image'][$k],
                ];
            }
            else 
            {
                $contactToBeCreate[] = [
                    'type' => $type,
                    'account' => $data['contacts']['account'][$k],
                    'image_id' => (int)$data['contacts']['image'][$k],
                ];
            }
        }
//         dd($contactToBeCreate, $contactToBeUpdate);
        \DB::beginTransaction();
        try
        {
            $huisuoJishi->update($data);
            $contactToBeSync = [];
            //创建联系人
            if (!empty($contactToBeCreate))
            {
                foreach ($contactToBeCreate as $_contact)
                {
                    $contact = $this->contact->create($_contact);
                    $contactToBeSync[$contact->id] = ['owner_type' => $data['type_flag']];
                }
            }
            //更新联系人
            if (!empty($contactToBeUpdate))
            {
                foreach ($contactToBeUpdate as $_id => $_contact)
                {
                    $contact = $this->contact->updateOrCreate(
                        ['id' => $_id],
                        $_contact
                    );
                    $contactToBeSync[$contact->id] = ['owner_type' => $data['type_flag']];
                }
            }
            //更新关联关联
            $huisuoJishi->contacts()->sync($contactToBeSync);
            
            \DB::commit();
            return normalize(0, "OK", ['data' => $huisuoJishi]);
        }
        catch (\Exception  $e)
        {
            \DB::rollBack();
            return ["创建失败：" . $e->getMessage(), $data];
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