<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        //    if (! $request->user()->hasRole($role)) {
        //     abort(401, 'This action is unauthorized.');
        // }

        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
          }else {
            abort('404');
          }

        if (! $request->expectsJson()) {
            return url('/');
        }
    }
}
