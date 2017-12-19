<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //清空缓存
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $routes = \Route::getRoutes()->getRoutesByName();
        $displayNameMap = [
            'admin' => '后台',
            'api' => '接口',
            'index' => '列表',
            'create' => '创建',
            'store' => '存储',
            'edit' => '编辑',
            'show' => '详情',
            'destroy' => '删除',
            'update' => '更新',
            'topic' => '帖子',
            'comment' => '回复',
            'attachment' => '附件',
            'forum' => '论坛版块',
            'huisuo' => '会所',
            'jishi' => '技师',
            'role' => '角色',
            'permission' => '权限',
            'contact' => '联系方式',
            'password' => '密码',
            'user' => '用户',
            'image' => '图片',
            'upload' => '上传',
            'login' => '登录',
            'logout' => '退出',
            'email' => '邮箱',
            'reset' => '重置',
            'register' => '注册',

        ];
        $search = array_keys($displayNameMap);
        $search[] = '.';
        $replace = array_values($displayNameMap);
        $replace[] = '-';

        foreach (array_keys($routes) as $name)
        {
            $permission = Permission::where('name', $name)->first();
            if (!$permission)
            {
                $permission = Permission::create([
                    'name' => $name,
                    'display_name' => str_replace($search, $replace, $name),
                ]);
            }

        }

        $role = Role::create(['name' => User::ROLE_SUPER_ADMIN_NAME, 'display_name' => '超级管理员']);

        User::where('name', 'xiaomiao')->first()->assignRole($role);
    }
}
