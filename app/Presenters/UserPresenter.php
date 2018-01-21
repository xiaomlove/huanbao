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

    public function listUserRoles(User $user)
    {
        $htmls = [];
        foreach ($user->roles as $role)
        {
            $htmls[] = sprintf('<a href="%s", target="_blank">%s</a>', route('admin.role.edit', $role->id), $role->display_name);
        }
        return implode(', ', $htmls);
    }
}