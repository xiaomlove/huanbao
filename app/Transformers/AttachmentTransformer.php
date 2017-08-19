<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Attachment;

class AttachmentTransformer extends TransformerAbstract
{
    protected $uriSuffix = '';
    
    protected $defaultIncludes = [];
    
    protected $availableIncludes = [];
    
    public function __construct()
    {
        $currentRouteName = \Route::currentRouteName();
        if ($currentRouteName == 'api.topic.index')
        {
            $this->uriSuffix = '!r350x200';
        }
        else 
        {
            $this->uriSuffix = '!r350x200';
        }
    }
    
    public function transform(Attachment $attachment)
    {
        return [
            'id' => $attachment->id,
            'key' => $attachment->id,
            'uri' => asset(sprintf("storage/%s/%s%s", $attachment->dirname, $attachment->basename, $this->uriSuffix)),
        ];
    }
    
}

