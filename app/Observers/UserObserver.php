<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function created(User $user)
    {
        $this->updateAttachmentRelationships($user, __FUNCTION__);
    }

    public function updated(User $user)
    {
        $this->updateAttachmentRelationships($user, __FUNCTION__);
    }

    private function updateAttachmentRelationships(User $user, $action = "")
    {
        if (!$user->avatar)
        {
            return;
        }
        $key = $user->avatar;
        $isExists = $user->allAvatarAttachment()->wherePivot("attachment_key", $key)->count();
        \Log::info(sprintf("%s, action: %s, key: %s, isExists: %s", __METHOD__, $action, $key, $isExists));
        if (!$isExists)
        {
            $user->allAvatarAttachment()->attach($key);
        }
    }
}