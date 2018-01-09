<?php

namespace App\Presenters;

use App\Models\Comment;
use App\Models\Attachment;
use App\Models\CommentDetail;

class CommentPresenter
{
    public function getEditLink(Comment $comment)
    {
        if ($comment->floor_num == 1)
        {
            return route('admin.topic.edit', $comment->tid);
        }
        else 
        {
            return route('admin.comment.edit', $comment->id);
        }
    }
    
    public function getFloorNumHuman(Comment $comment)
    {
        $floorNum = $comment->floor_num;
        switch ($floorNum)
        {
            case 1:
                return '主帖';
            case 2:
                return '沙发';
            case 3:
                return '板凳';
            case 4:
                return '地板';
            default:
                return "{$floorNum}楼";
        }
    }
    
    public function getPosition(Comment $comment)
    {
        $getFloorNumComment = $comment;
        $suffix = '';
        if ($getFloorNumComment->floor_num == -1 && $comment->rootComment)
        {
            $getFloorNumComment = $comment->rootComment;
            $suffix = '子回复';
        }
        $out = [];
        if ($suffix)
        {
            $out[] = $suffix;
        }
        $out[] = sprintf(
            '<a href="%s">%s</a>',
            route('admin.comment.index', array_merge(request()->except(['page', 'root_id']), ['root_id' => $getFloorNumComment->id])),
            $this->getFloorNumHuman($getFloorNumComment)
        );
        if ($comment->topic)
        {
            $out[] = sprintf(
                '<a href="%s">%s</a>',
                route('admin.comment.index', array_merge(request()->except(['page', 'tid']), ['tid' => $comment->topic->id])),
                str_limit($comment->topic->title, 20, '...')
            );
            if ($comment->topic->forum)
            {
                $out[] = $comment->topic->forum->name;
            }
            else
            {
                $out[] = "[已删除版块({$comment->topic->fid})]";
            }
        }
        else 
        {
            $out[] = "[已删除话题({$comment->tid})]";
        }
        
        return implode(' -> ', array_reverse($out));
    }
    
    public function listAttachmentImages(Comment $comment)
    {
        if (count($comment->attachments) == 0)
        {
            return '';
        }
        $imageArr = [];
        foreach ($comment->attachments as $image)
        {
            $imageArr[] = sprintf(
                '<img src="%s" class="attachment-image" />',
                $this->getAttachmentImageLink($image)
            );
        }
        return '<p class="attachment-wrap">' . implode('', $imageArr) . '</p>';
    }
    
    public function getAttachmentImageLink(Attachment $attachment)
    {
        return asset(sprintf("storage/%s/%s", $attachment->dirname, $attachment->basename));
    }

    public function renderDetail(Comment $comment, array $params = [])
    {
        static $disk;
        if (!$disk)
        {
            $disk = \Storage::disk('qiniu');
        }
        $defaults = [
            'include_user' => false,
            'only_text' => false,
        ];
        $params = array_merge($defaults, $params);

        $contents = json_decode($comment->detail->content, true);

        $htmls = [];
        foreach ($contents as $content)
        {
            switch ($content['type'])
            {
                case CommentDetail::CONTENT_TYPE_TEXT:
                    if ($params['only_text'])
                    {
                        $htmls[] = $content['data']['text'];
                    }
                    else
                    {
                        if ($params['include_user'])
                        {
                            $name = '<span class="name">' . $comment->user->name . '</span>';
                            if ($comment->root_id != $comment->pid)
                            {
                                $name .= " 回复 " . $comment->parentComment->user->name;
                            }
                            $htmls[] = sprintf('<p>%s：%s</p>', $name, str_replace(["\n"], ["<br/>"], $content['data']['text']));
                        }
                        else
                        {
                            $htmls[] = sprintf('<p>%s</p>', str_replace(["\n"], ["<br/>"], $content['data']['text']));
                        }
                    }
                    break;
                case CommentDetail::CONTENT_TYPE_IMAGE:
                    if ($params['only_text'])
                    {
                        $htmls[] = '[图片]';
                    }
                    else
                    {
                        $htmls[] = sprintf(
                            '<p><a href="%s" target="_blank"><img src="%s" class="image"></a></p>',
                            $content['data']['url'],
                            $content['data']['attachment_key'] ? $disk->imagePreviewUrl($content['data']['attachment_key'], 'imageView2/0/h/400') : $content['data']['url']
                        );
                    }
                    break;
            }
        }
        return implode("", $htmls);
    }

}