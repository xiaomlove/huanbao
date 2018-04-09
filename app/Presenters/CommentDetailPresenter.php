<?php

namespace App\Presenters;

use App\Models\Comment;
use App\Models\Attachment;
use App\Models\CommentDetail;

class CommentDetailPresenter
{
    public function renderDetail(CommentDetail $commentDetail, array $params = [])
    {
        static $disk;
        if (!$disk)
        {
            $disk = \Storage::disk('qiniu');
        }
        $defaults = [
            'only_text' => false,
        ];
        $params = array_merge($defaults, $params);

        $contents = json_decode($commentDetail->content, true);

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
                        $htmls[] = sprintf('<p>%s</p>', str_replace(["\n"], ["<br/>"], $content['data']['text']));
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
                            !empty($content['data']['key']) ? $disk->imagePreviewUrl($content['data']['key'], 'imageView2/0/h/400') : $content['data']['url']
                        );
                    }
                    break;
            }
        }
        return implode("", $htmls);
    }

}