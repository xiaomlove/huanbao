<?php

use Illuminate\Database\Seeder;
use App\Models\Forum;
use App\Models\ForumTaxonomy;
use App\Models\HuisuoJishi;

class ForumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'taxonomy' => '地区',
                'icon' => 'map-marker-outline',
                'forums' => [
                    ['name' => '罗湖', 'description' => '专业！'],
                    ['name' => '福田', 'description' => '高端！'],
                    ['name' => '沙尾', 'description' => '车场！'],
                ],
            ],
            [
                'taxonomy' => 'HS',
                'icon' => 'hospital-building',
                'forums' => [
                    ['name' => '明珠', 'description' => '第一次'],
                    ['name' => '新悦', 'description' => '老牌'],
                ],
            ],
            [
                'taxonomy' => 'JS',
                'icon' => 'human-female',
                'forums' => [
                    ['name' => '小可', 'description' => '泰山'],
                    ['name' => '骚琳', 'description' => '北斗'],
                ],
            ],
            [
                'taxonomy' => '内容',
                'icon' => 'table-of-contents',
                'forums' => [
                    ['name' => '闲聊', 'description' => '吹水'],
                    ['name' => '技术讨论', 'description' => '技术讨论'],
                ],
            ],
        ];

        //特定的两个版块
        $huisuoJishiTypes =HuisuoJishi::listTypes();
        foreach ($huisuoJishiTypes as $type => $value)
        {
            if ($type == HuisuoJishi::TYPE_JISHI)
            {
                Forum::create([
                    'id' => Forum::ID_JISHI,
                    'name' => $value['name'],
                    'key' => \Uuid::uuid4(),
                    'description' => $value['name'] . "专区",
                ]);
            }
            elseif ($type == HuisuoJishi::TYPE_HUISUO)
            {
                Forum::create([
                    'id' => Forum::ID_HUISUO,
                    'name' => $value['name'],
                    'key' => \Uuid::uuid4(),
                    'description' => $value['name'] . "专区",
                ]);
            }
        }

        foreach ($data as $value)
        {
            $taxonomy = ForumTaxonomy::create([
                'name' => $value['taxonomy'],
                'icon' => $value['icon'],
                'key' => \Uuid::uuid4(),
            ]);
            //无须其他步骤，自动往中间表插入了数据
            foreach ($value['forums'] as $forum)
            {
                $forum['key'] = \Uuid::uuid4();
                $taxonomy->forums()->create($forum);
            }
        }

    }
}
