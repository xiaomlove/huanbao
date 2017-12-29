<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\CommentDetail;
use App\Models\Comment;
use App\User;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $topics = factory(Topic::class, 100)->create()->each(function($topic) use ($users) {
           //创建主楼评论
            $mainFloor = $topic->main_floor->create([
                'uid' => $topic->uid,
                'floor_num' => 1,
            ]);
            //主楼详情
            $mainFloor->detail->save(factory(CommentDetail::class));


        });
    }
}
