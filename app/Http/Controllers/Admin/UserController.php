<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\UserRepository;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use App\Models\Permission;

class UserController extends Controller
{
    protected $user;
    
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = User::when($request->id, function($query) use ($request) {
            return $query->where('id', intval($request->id));
        })->when($request->q, function($query) use ($request) {
            return $query->where(function($query) use ($request) {
                return $query->where('name', 'like', "%{$request->q}%")
                ->orWhere('email', 'like', "%{$request->q}%");
            });
        })->paginate(10);
        
        return view('admin.user.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $roles = Role::all();
        return view('admin.user.form', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:20|confirmed',
        ]);
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->syncRoles($request->get('roles', []));
        
        return redirect()->route('admin.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->user->listMainProfileData($id);
//        dd($result);
        $roles = Role::all();
        return view('admin.user.show', [
            'user' => $result['user'],
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('avatarAttachment')->findOrFail($id);
        $roles = Role::all();
        return view('admin.user.form', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $result = $this->user->update($request, $id);
        if ($result['ret'] == 0)
        {
            return redirect()->route('admin.user.show', $id)->with("success", "更新用户信息成功");
        }
        else
        {
            return back()->with("danger", $result['msg'])->withInput();
        }
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

    public function permission($id)
    {
        $user = User::with('permissions')->findOrFail($id);
        $method = request()->method();
        if ($method == "GET")
        {
            $permissions = app(PermissionRepository::class)->listGrouped();
            $displayNames = (new Permission())->listDisplayNames();
            return view('admin.user.permissions', [
                'user' => $user,
                'permissions' => $permissions,
                'displayNames' => $displayNames,
            ]);
        }
        elseif ($method == "PATCH")
        {
            $user->syncPermissions(request('permissions', []));
            return redirect()->route('admin.user.show', $user->id)->with('success', '修改个人权限成功');
        }

        throw new \Exception("非法请求方法：$method");

    }
}
