<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\CommentDetail;
use App\Models\Comment;
use App\User;
use Faker\Generator as Faker;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = User::all();
        $topics = factory(Topic::class, 2)->create()->each(function($topic) use ($users, $faker) {
           //创建主楼评论
            $mainFloor = $topic->mainFloor()->create([
                'key' => \Uuid::uuid4(),
                'uid' => $topic->uid,
                'floor_num' => 1,
            ]);

            //主楼详情
            $mainFloor->detail()->create([
                'content' => factory(CommentDetail::class, 1)->make()->first()->content,
            ]);
            //多条回复
            $commentCounts = rand(3, 10);
            for ($i = 2; $i < $commentCounts; $i++)
            {
                //正常楼
                $comment = $topic->comments()->create([
                    'key' => \Uuid::uuid4(),
                    'uid' => $users->random()->id,
                    'floor_num' => $i,
                ]);
                $comment->detail()->create([
                    'content' => factory(CommentDetail::class, 1)->make()->first()->content,
                ]);
                //楼中楼
                if (rand(1, 10) > 5)
                {
                    $commentCommentCounts = rand(3, 8);
                    for ($j = 0; $j < $commentCommentCounts; $j++)
                    {
                        $commentComment = $topic->comments()->create([
                            'key' => \Uuid::uuid4(),
                            'uid' => $users->random()->id,
                            'pid' => $comment->id,
                            'root_id' => $comment->id,
                        ]);

                        $content = json_encode([
                            ['type' => 'text', 'data' => ['text' => $faker->sentence()]],
                        ], JSON_UNESCAPED_UNICODE);

                        $commentComment->detail()->create([
                            'content' => $content,
                        ]);
                        $comment->firstComments()->attach($commentComment);
                    }
                    $comment->update(['comment_count' => $commentCommentCounts]);
                }
            }
            $topic->update(['comment_count' => $commentCounts - 1]);
        });
    }
}
