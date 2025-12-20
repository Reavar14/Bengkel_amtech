<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleUser
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'user') {
            return $next($request);
        }

        abort(403, 'Only user can access this page.');
    }
}