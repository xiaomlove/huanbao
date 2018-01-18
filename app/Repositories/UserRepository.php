<?php

namespace App\Repositories;

use App\User;
use App\Repositories\AttachmentRepository;
use App\Models\AttachmentRelationship;
use App\Http\Requests\UserRequest;

class UserRepository
{
    
    public function listMainProfileData($id)
    {
        $out = [];
        $user = User::with(['roles', 'avatarAttachment'])->find($id);

        $out['user'] = $user;

        return $out;
    }
    
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $update = $request->only(['email', 'name', 'avatar']);
        if ($request->password)
        {
            $update['password'] = bcrypt($request->password);
        }
        if (!empty($update['avatar']))
        {
            $update['avatar'] = attachmentKey($update['avatar']);
        }

        \DB::beginTransaction();
        try
        {
            $user->update($update);
            \DB::commit();
            return normalize(0, "OK", $user);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $update);
        }

    }
}