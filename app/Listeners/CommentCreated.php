<?php

namespace App\Listeners;

use App\Events\CommentCreated as EventCommentCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Topic;

class CommentCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentCreated  $event
     * @return void
     */
    public function handle(EventCommentCreated $event)
    {
        $comment = $event->comment;
        
        //更新帖子相关信息
        
    }
}
