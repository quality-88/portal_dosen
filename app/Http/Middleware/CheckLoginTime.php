<?php
// app/Http/Middleware/CheckLoginTime.php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class CheckLoginTime
{
    public function handle($request, Closure $next)
    {
        $lastActivity = session('last_activity');

        if ($lastActivity && Carbon::now()->diffInSeconds($lastActivity) > 12000) { 
            // Sesuaikan 12000 dengan waktu timeout yang diinginkan (dalam detik)
            session()->flush(); // Menghapus semua sesi
            return redirect('/login')->with('message', 'Sesi Anda telah kadaluwarsa. Silakan login kembali.');
        }

        session()->put('last_activity', Carbon::now());

        return $next($request);
    }
}
