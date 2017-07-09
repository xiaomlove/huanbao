<?php

use Illuminate\Database\Seeder;

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
            ['id' => 1, 'name' => '罗湖', 'slug' => 'luohu', 'pid' => 0, 'description' => '专业！'],
            ['id' => 2, 'name' => '福田', 'slug' => 'futian', 'pid' => 0, 'description' => '高端！'],
            ['id' => 3, 'name' => '沙尾', 'slug' => 'shawei', 'pid' => 2, 'description' => '车场！'],
            ['id' => 4, 'name' => '南山', 'slug' => 'nanshan', 'pid' => 0, 'description' => '学习！'],
            ['id' => 5, 'name' => '宝安', 'slug' => 'baoan', 'pid' => 0, 'description' => '深藏不露'],
            ['id' => 6, 'name' => '西乡', 'slug' => 'xixiang', 'pid' => 5, 'description' => '不行'],
            ['id' => 7, 'name' => '坪洲', 'slug' => 'pingzhou', 'pid' => 6, 'description' => '比较水'],
        ];
        $forums = App\Models\Forum::insert($data);
    }
}
