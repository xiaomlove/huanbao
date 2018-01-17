<?php

namespace App\Repositories;

use App\User;
use App\Repositories\AttachmentRepository;
use App\Models\AttachmentRelationship;

class UserRepository
{
    
    public function listMainProfileData($id)
    {
        $out = [];
        $user = User::with(['roles', 'avatarAttachment'])->find($id);

        $out['user'] = $user;

        return $out;
    }
    
    public function update(array $data, $id)
    {
//         dd($data);
        $user = $this->user->find($id);
        if (empty($user))
        {
            return normalize("invalid id: $id");
        }
        //先保存附件
        $attachmentResult = $this->attachment->getFromRequestData($data);
        if ($attachmentResult['ret'] != 0)
        {
            return $attachmentResult;
        }
        $attachments = [];
        foreach ($attachmentResult['data'] as $attachment)
        {
            $attachments[$attachment->id] = ['target_type' => AttachmentRelationship::TARGET_TYPE_USER_AVATAR];
        }
        unset($attachmentResult, $attachment);
        
        if (empty($data['password']))
        {
            unset($data['password']);
        }
        else 
        {
            $data['password'] = bcrypt($data['password']);
        }
        
        \DB::beginTransaction();
        try
        {
            $user->update($data);
            $user->avatars()->syncWithoutDetaching($attachments);
            $user->syncRoles($data['roles']);
    
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $data);
        }
    
        return normalize(0, "OK", [
            'user' => $user,
        ]);
    }
}