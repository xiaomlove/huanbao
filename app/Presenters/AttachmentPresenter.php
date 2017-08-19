<?php

namespace App\Presenters;

use App\Models\Attachment;

class AttachmentPresenter
{
    protected $uriSuffix = '';
    
    public function __construct()
    {
        $env = config('app.env');
        if ($env === 'product')
        {
            $this->uriSuffix = "!r40x40";
        }
    }
    
    public function getAttached(Attachment $attachment)
    {
        $html = '';
        foreach ($attachment->attached as $comment)
        {
            if ($comment->floornum == 1)
            {
                $html .= sprintf(
                    '<small><a href="%s">%s</a></small>',
                    route('topic.show', $comment->topic->id),
                    str_limit($comment->topic->title, 20, '...')
                );
            }
            else
            {
                $html .= sprintf(
                    '<small><a href="%s">%s</a> -> <a href="%s">%s</a></small>',
                    route('topic.show', $comment->topic->id),
                    str_limit($comment->topic->title, 20, '...'),
                    route('topic.show', $comment->topic->id),
                    str_limit($comment->detail->content, 20, '...')
                );
            }
        }
        return $html;
    }
    
    public function getAttachmentImageLink(Attachment $attachment, $resize = true)
    {
        return asset(sprintf(
            "storage/%s/%s%s", 
            $attachment->dirname, 
            $attachment->basename,
            $resize ? $this->uriSuffix : ""
        ));
    }
}