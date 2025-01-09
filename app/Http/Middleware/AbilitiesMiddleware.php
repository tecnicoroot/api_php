<?php
namespace App\Http\Middleware;

use Closure;

class AbilitiesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $abilities)
    {
        $user = $request->user();

       if (!$user || !$user->hasRoles($abilities)){
            return response('Unauthorized.', 403);
       }

        return $next($request);
    }
}