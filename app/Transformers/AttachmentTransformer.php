<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Attachment;

class AttachmentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    protected $availableIncludes = [];
    
    public function transform(Attachment $attachment)
    {
        return [
            'id' => $attachment->id,
            'key' => $attachment->id,
            'uri' => asset(sprintf("storage/%s/%s", $attachment->dirname, $attachment->basename)),
        ];
    }
    
}

