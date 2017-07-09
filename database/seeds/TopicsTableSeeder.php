<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = factory(App\Models\Topic::class, 100)->create()->each(function($topic) {
           //创建评论
           $comment = App\Models\Comment::create([
               'uid' => $topic->uid,
               'tid' => $topic->id,
               'floor_num' => 1,
           ]);
           
           //创建评论详情
           $commentDetail = App\Models\CommentDetail::create([
               'cid' => $comment->id,
               'content' => factory(App\Models\CommentDetail::class)->make()->content,
           ]);
           
        });
    }
}
