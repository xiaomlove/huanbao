<?php

namespace App\Presenters;

use App\Models\Attachment;

class AttachmentPresenter
{
    public function getResizeStr($resize)
    {
        if (empty($resize))
        {
            return '';
        }
        $env = config('app.env');
        if ($env === 'product')
        {
            return "!r" . $resize;
        }
        return '';
    }
    
    public function getAttached(Attachment $attachment)
    {
        $html = '';
        foreach ($attachment->comments as $comment)
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
        foreach ($attachment->users as $user)
        {
            $html .= sprintf(
                '<small><a href="%s">%s</a></small>',
                route('user.show', $user->id),
                $user->name
            );
        }
        return $html;
    }
    
    public function getAttachmentImageLink(Attachment $attachment, $resize = null)
    {
        return asset(sprintf(
            "storage/%s/%s%s", 
            $attachment->dirname, 
            $attachment->basename,
            $this->getResizeStr($resize)
        ));
    }

    public function getThumbnail(Attachment $attachment, $width = 40, $height = 40)
    {
        static $disk;
        if (!$disk)
        {
            $disk = \Storage::disk('qiniu');
        }
        if (strpos($attachment->mime_type, "image") !== false)
        {
            return sprintf(
                '<a href="%s" target="_blank"><img src="%s" /></a>',
                $disk->url($attachment->key),
                $disk->imagePreviewUrl($attachment->key, "imageView2/0/w/{$width}/h/{$height}")
            );
        }
        else
        {
            return "";
        }
    }

    public function listAttaches(Attachment $attachment)
    {
        static $commentDetailPresenter;
        if (!$commentDetailPresenter)
        {
            $commentDetailPresenter = new CommentDetailPresenter();
        }
        $htmls = [];
        if (!$attachment->commentDetails->isEmpty())
        {
            $commentDetail = $attachment->commentDetails->first();
            $cid = $commentDetail->cid;
            $content = $commentDetailPresenter->renderDetail($commentDetail, ['only_text' => true]);
            $htmls[] = sprintf(
                '<a href="%s">%s</a>',
                route('admin.comment.show', ['id' => $cid]),
                str_limit($content, 20)
            );
        }
        return implode('、', $htmls);
    }
}