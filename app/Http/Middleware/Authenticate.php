<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('userid')) {
            return redirect('/login')->with('message', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        return $next($request);
    }
}
