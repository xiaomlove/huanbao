<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        foreach (array_keys($routes) as $name)
        {
            $permission = Permission::firstOrCreate(['name' => $name]);
        }
    }
}
