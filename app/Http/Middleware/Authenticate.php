<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('userid')) {
            return view('admin.login');
        }

        return $next($request);
    }
}
