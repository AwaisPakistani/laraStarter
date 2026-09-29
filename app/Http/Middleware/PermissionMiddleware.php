<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Permission;
class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user->hasRole('super_admin')){
            return $next($request);
        }else{
            $roleName = auth()->user()->roles->first();
            $permissions = $roleName->getPermissionNames();
            $url = $request->route()->url();

            dd($url);
            $permission = Permission::where('name', $url)->first();
            dd($permission);
            // if($user->hasPermissionTo())
            return $next($request);
        }
    }
}
