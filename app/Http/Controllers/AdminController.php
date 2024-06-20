<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        // Periksa apakah pengguna terotentikasi menggunakan logika AuthController
        $authController = new AuthController();
        if ($authController->isUserAuthenticated()) {
            return view('admin.index');
        } else {
            // Jika tidak terotentikasi, redirect ke halaman login
            return redirect()->route('login')->with('error', 'Anda perlu login untuk mengakses dasbor admin.');
        }
    }
}
