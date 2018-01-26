<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Attachment;

class AttachmentTransformer extends TransformerAbstract
{
    protected $width;

    protected $height;
    
    protected $defaultIncludes = [];
    
    protected $availableIncludes = [];
    
    public function __construct()
    {
        $currentRouteName = \Route::currentRouteName();
        if ($currentRouteName == 'api.topic.index')
        {
            $this->width = 350;
            $this->height = 750;
        }
        else 
        {
            $this->width = 350;
            $this->height = 750;
        }
    }
    
    public function transform(Attachment $attachment)
    {
        return [
            'id' => $attachment->id,
            'key' => $attachment->id,
            'url' => $attachment->url($this->width, $this->height),
        ];
    }
    
}

