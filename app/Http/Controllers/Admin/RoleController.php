<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Repositories\PermissionRepository;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    protected $permission;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permission = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Role::paginate(10);
        return view('admin.role.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role();
        $permissions = $this->permission->listGrouped();
        $displayNames = (new Permission())->listDisplayNames();
        return view('admin.role.form', compact('permissions', 'role', 'displayNames'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $data = $request->only(['name', 'display_name']);
        $role = Role::create($data);
        $role->syncPermissions($request->get('permissions'));
        return redirect()->route('admin.role.index')->with('success', '创建用户组成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = $this->permission->listGrouped();
        $displayNames = (new Permission())->listDisplayNames();
//        dd($role);
        return view('admin.role.form', compact('role', 'permissions', 'displayNames'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $info = Role::findOrFail($id);
        $info->update($request->only(['name', 'display_name']));
        $info->syncPermissions($request->get('permissions'));
        return back()->with("success", "更新成功");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
