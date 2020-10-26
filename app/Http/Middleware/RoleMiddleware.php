<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ... $permission)
    {

        // if(!$request->user()->hasRole($role)) {
        //      abort(401);
        // }

        if( $request->user()->hasPermissionTo($permission)) {
            return $next($request);
        }
        
        return response('unauthorized', 401);

    }
}
