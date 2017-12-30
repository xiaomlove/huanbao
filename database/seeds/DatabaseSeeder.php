<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class); //创建用户
        $this->call(PermissionsTableSeeder::class);//创建权限
        $this->call(ForumsTableSeeder::class); //创建版块
        $this->call(TopicsTableSeeder::class); //创建话题
//        $this->call(CommentsTableSeeder::class); //创建评论

    }
}
