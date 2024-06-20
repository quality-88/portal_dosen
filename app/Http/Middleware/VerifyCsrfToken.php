<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*', // Mengabaikan semua permintaan ke dalam route yang dimulai dengan 'api/'
        'logout', // Mengabaikan permintaan ke URI '/logout'
        '/all/type',
        '/formHonorDosen'.
        '/all/type', 
        '/fetchFakultas',
        '/all-type', 
        '/searchDosen', 
        '/nilai/type',
         '/nilai/submit',
        '/cetaknilai',
        '/nilaikelas', 
        'formHonorDosen/bayar-semua',
    ];
    
}
