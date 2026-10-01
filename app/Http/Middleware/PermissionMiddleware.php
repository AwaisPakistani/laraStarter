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
        $role = $user->roles->first();
        $roleName= $role->name;
        // $permissions = $role->getPermissionNames();
        $route_name = $request->route()->getName();
        $permission = Permission::where('name', $route_name)->first();
        // Super admin have all permissions
        if($user->hasRole('super_admin')){
            return $next($request);
        }
        // If the permission doesn't exist in the database, deny access immediately
        if (!$permission || !$route_name=='dashboard' || !$route_name=='dashboard_one') {
            abort(403, 'Unauthorized action. No permission defined for this route.');
        }
        // using hasPermissions is the function defined in the User model to check if the user has the required permission
        if(auth()->user()->hasPermission($permission->name)){
                return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }
}
