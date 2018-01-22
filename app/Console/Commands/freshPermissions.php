<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\Role;
use App\User;

class freshPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:fresh {--rebuild}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新权限，从路由中获取动作添加到权限';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rebuild = $this->option('rebuild');
        //清空缓存
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $permissionModel = new Permission();
        $routes = \Route::getRoutes()->getRoutesByName();
        $displayNameMap = $permissionModel->listDisplayNames();

        if ($rebuild)
        {
            $this->info('rebuild, going to remove related data...');
            $tableNames = config('permission.table_names');
            foreach ($tableNames as $table)
            {
                \DB::table($table)->delete();
            }
            $this->info('remove all tables done.');
        }

        foreach (array_keys($routes) as $name)
        {
            $permission = $permissionModel::where('name', $name)->first();
            if (!$permission)
            {
                $nameParts = explode('.', $name);
                $namePartsEnd = end($nameParts);
                $permissionModel->create([
                    'name' => $name,
                    'display_name' => $displayNameMap[$namePartsEnd] ?? $name,
                ]);
            }

        }

        $superAdmin = Role::where("name", User::ROLE_SUPER_ADMIN_NAME)->first();
        if (!$superAdmin)
        {
            $this->info('no super admin, creating...');
            $superAdmin = Role::create(['name' => User::ROLE_SUPER_ADMIN_NAME, 'display_name' => '超级管理员']);
            $this->info('super admin create done.');
        }
        $user = User::where("name", "xiaomiao")->first();
        if ($user && !$user->hasRole(User::ROLE_SUPER_ADMIN_NAME))
        {
            $this->info("xiaomiao is exists, assign role 'super admin' to him.");
            $user->assignRole($superAdmin);
        }

        $this->info(__METHOD__ . ' done!');

    }
}
