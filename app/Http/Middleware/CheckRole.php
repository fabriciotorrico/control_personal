<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        if (! $request->user()->isRole($role)) {
            return redirect('home');
            //abort(403, "No tienes autorización para ingresar.");
            //abort(404);
        }
    return $next($request);
    }
}
