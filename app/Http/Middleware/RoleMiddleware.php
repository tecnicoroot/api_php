<?php
namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();

       if (!$user || !$user->hasRoles($role)){
            return response('Unauthorized.', 403);
       }

        return $next($request);
    }
}