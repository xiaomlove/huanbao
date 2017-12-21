<?php

use Illuminate\Database\Seeder;
use App\Models\Forum;
use App\Models\ForumTaxonomyRelationship;
use App\Models\ForumTaxonomy;

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
                'forums' => [
                    ['name' => '罗湖', 'slug' => 'luohu', 'description' => '专业！'],
                    ['name' => '福田', 'slug' => 'futian', 'description' => '高端！'],
                    ['name' => '沙尾', 'slug' => 'shawei', 'description' => '车场！'],
                ],
            ],
            [
                'taxonomy' => 'HS',
                'forums' => [
                    ['name' => '明珠', 'slug' => 'mingzhu', 'description' => '第一次'],
                ],
            ],
            [
                'taxonomy' => 'JS',
                'forums' => [
                    ['name' => '小可', 'slug' => 'xiaoke', 'description' => '泰山'],
                    ['name' => '骚琳', 'slug' => 'saolin', 'description' => '北斗'],
                ],
            ],
            [
                'taxonomy' => 'JS',
                'forums' => [
                    ['name' => '闲聊', 'slug' => 'xianliao', 'description' => '吹水'],
                ],
            ],
            [
                'taxonomy' => 'JS',
                'forums' => [
                    ['name' => '2017年终总结', 'slug' => '2017zongjie', 'description' => '2017年终总结'],
                ],
            ],
        ];

        foreach ($data as $value)
        {
            $taxonomy = ForumTaxonomy::create(['name' => $value['taxonomy']]);
            $forums = $taxonomy->forums()->createMany($value['forums']);
            $taxonomy->forums()->attach($forums);
        }

    }
}
