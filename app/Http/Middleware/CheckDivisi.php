<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;

class CheckDivisi
{
    public function handle($request, Closure $next, ...$divisis)
    {
        if (in_array(session('divisi'), $divisis)) {
            return $next($request);
        }
        $userId = session('user_id'); // Adjust based on your application's logic

        // Retrieve the user's division from the database
        $userDivision = DB::table('allsmusers')->where('id', $userId)->value('divisi');

        // Store the user's division in the session
        session(['user_division' => $userDivision]);

        return $next($request);
        return redirect('/login')->with('error', 'Akses ditolak.');
    }
}
