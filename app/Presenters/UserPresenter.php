<?php

namespace App\Presenters;

use App\User;

class UserPresenter
{
    protected $attachment;
    
    public function __construct(AttachmentPresenter $attachment)
    {
        $this->attachment = $attachment;
    }
    
}