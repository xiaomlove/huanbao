<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionRepository
{
    protected $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function listAll(Request $request)
    {
        return $this->permission
            ->when($request->name, function ($query) use ($request) {return $query->where('name', 'like', "%{$request->name}%");})
            ->paginate($request->get('per_page', 15));
    }

    public function listGrouped()
    {
        $results = [];
        $results['other'] = [];
        $this->permission->all()->each(function ($permission) use (&$results) {
            if (!$permission->name)
            {
                return true;
            }
            $permissionNameParts = explode('.', $permission->name);
            $length = count($permissionNameParts);

            if ($length == 1)
            {
                $results['other'][] = $permission;
            }
            elseif ($length == 2)
            {
                if (!isset($results[$permissionNameParts[0]]))
                {
                    $results[$permissionNameParts[0]] = [];
                }
                $results[$permissionNameParts[0]][] = $permission;
            }
            else
            {
                if (!isset($results[$permissionNameParts[0]][$permissionNameParts[1]]))
                {
                    $results[$permissionNameParts[0]][$permissionNameParts[1]] = [];
                }
                $results[$permissionNameParts[0]][$permissionNameParts[1]][] = $permission;
            }

        });
        return $results;
    }
}