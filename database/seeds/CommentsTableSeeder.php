<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = factory(App\Models\Comment::class, 1000)->create()->each(function ($comment) {
            //创建评论详情
            $commentDetail = App\Models\CommentDetail::create([
                'cid' => $comment->id,
                'content' => factory(App\Models\CommentDetail::class)->make()->content,
            ]);
             
            //更新楼号
            $count = $comment
            ->where('tid', $comment->tid)
            ->where('id', '<=', $comment->id)
            ->where('pid', 0)
            ->count();
            
            $comment->update(['floor_num' => $count]);
        });
    }
}
