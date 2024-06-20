<?php
// app/Http/Middleware/CheckLoginTime.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CheckLoginTime
{
    public function handle($request, Closure $next)
    {
        $lastActivity = session('last_activity');

        if (!is_null($lastActivity) && time() - $lastActivity > 12000) { // Sesuaikan dengan kebutuhan waktu timeout (dalam detik)
            // Sesuaikan dengan tindakan yang sesuai, contohnya mengarahkan ke halaman login
            return redirect('/login')->with('message', 'Sesi Anda telah kadaluwarsa. Silakan login kembali.');
        }

        session()->put('last_activity', time());

        return $next($request);
    }
}
