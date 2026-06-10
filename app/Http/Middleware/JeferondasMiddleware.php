<?php

namespace App\Http\Middleware;

use Closure;

class JeferondasMiddleware
{
    public function handle($request, Closure $next)
    {
        if (in_array(auth()->user()->role, ['admin', 'jeferondas'])) {
            return $next($request);
        }

        return redirect('/');
    }
}
