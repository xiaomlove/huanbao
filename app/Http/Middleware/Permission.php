<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class Permission
{
    protected $exceptRouteNames = [
        'test',
        'login',
        'logout',
        'home',
        'password',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $exceptJson = $request->expectsJson();
        $routeName = \Route::currentRouteName();
        if (empty($routeName))
        {
            return $this->abort('当前请求没有名称', $exceptJson);
        }

        foreach ($this->exceptRouteNames as $name)
        {
            if (preg_match("/{$name}/i", $routeName))
            {
                \Log::info(sprintf('%s, route: %s is except by: %s', __METHOD__, $routeName, $name));
                return $next($request);
            }
        }

        if (!\Auth::check())
        {
            return $this->abort('没有登录认证', $exceptJson);
        }
        $user = \Auth::user();

        if ($user->hasRole(User::ROLE_SUPER_ADMIN_NAME))
        {
            \Log::info(sprintf('%s, user: %s is super admin', __METHOD__, $user->name));
            return $next($request);
        }
        if ($user->hasPermissionTo($routeName))
        {
            \Log::info(sprintf('%s, user: %s has permission: %s', __METHOD__, $user->name, $routeName));
            return $next($request);
        }
        return $this->abort("没有 $routeName 权限", $exceptJson);
    }
    
    public function abort($msg, $exceptJson)
    {
        if ($exceptJson)
        {
            return response()->json(normalize($msg), 403);
        }
        else
        {
            return back()->with('danger', $msg);
        }
    }
}
