<?php

namespace App\Repositories;

use App\User;
use App\Repositories\AttachmentRepository;
use App\Models\AttachmentRelationship;
use Illuminate\Http\Request;

class UserRepository
{

    public function listMainProfileData($id)
    {
        $out = [];
        $user = User::with(['roles', 'avatarAttachment'])->findOrFail($id);
//        dd($user);

        $out['user'] = $user;

        return $out;
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $update = $request->only(['email', 'name', 'avatar']);
        if ($request->password) {
            $update['password'] = bcrypt($request->password);
        }
        if (!empty($update['avatar'])) {
            $update['avatar'] = attachmentKey($update['avatar']);
        }

        \DB::beginTransaction();
        try {
            $user->update($update);
            $user->syncRoles($request->get('roles', []));
            //保存附件
            if (!empty($update['avatar'])) {

            }
            \DB::commit();
            return normalize(0, "OK", $user);
        } catch (\Exception $e) {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $update);
        }

    }

    public function create(Request $request)
    {
        \DB::beginTransaction();
        try {
            $user = User::create([
                'key' => \Uuid::uuid4(),
                'name' => $request->get('username', '用户_' . microtime(true)),
                'email' => $request->username,//现在都是使用邮箱注册
                'password' => bcrypt($request->password),
            ]);
            $user->syncRoles($request->get('roles', []));
            \DB::commit();
            return normalize(0, "OK", $user);
        } catch (\Exception $e) {
            \DB::rollBack();
            return normalize(1, $e->getMessage(), $request->all());
        }


    }
}