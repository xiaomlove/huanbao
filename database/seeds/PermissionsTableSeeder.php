<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
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
        $permissionModel = new Permission();
        $displayNameMap = $permissionModel->listDisplayNames();

        foreach (array_keys($routes) as $name)
        {
            $permission = Permission::where('name', $name)->first();
            if (!$permission)
            {
                $nameParts = explode('.', $name);
                $namePartsEnd = end($nameParts);
                $permission = Permission::create([
                    'name' => $name,
                    'display_name' => $displayNameMap[$namePartsEnd] ?? $name,
                ]);
            }

        }

        $role = Role::create(['name' => User::ROLE_SUPER_ADMIN_NAME, 'display_name' => '超级管理员']);

        User::where('name', 'xiaomiao')->first()->assignRole($role);
    }
}
