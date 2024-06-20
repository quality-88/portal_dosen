<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {   
        session(['last_activity' => now()]);
        $userid = $request->input('userid');
        $password = $request->input('password');

        $user = DB::table('AllSMUsers')
            ->where('userid', $userid)
            ->where('password', $password)
            ->first();

        if ($user) {
            // Simpan informasi pengguna dalam sesi
            session(['userid' => $user->userid, 'divisi' => $user->Divisi]);
            $userAgent = $request->header('User-Agent');
            $browserSignature = sha1($userAgent);
            session(['browser_signature' => $browserSignature]);

            // Redirect sesuai dengan divisi pengguna
            return redirect()->route($this->getDashboardRoute($user->Divisi));
        } else {
            return redirect()->route('login')->withErrors(['login' => 'Invalid credentials.']);
        }
    }

    private function getDashboardRoute($divisi)
    {
        switch ($divisi) {
            case 'Administrator':
                return 'dashboard';
                break;

            case 'Biro Keuangan':
                return 'dashboard';
                break;

            case 'Biro Akademik':
             return 'dashboard';
              break;

              case 'Ka Biro Akademik':
                return 'dashboard';
                 break;
                case 'Sekretariat':
                    return 'dashboard';
                     break;
                case 'Kaprodi':
                    return 'dashboard';
                        break;
                case 'Fungsionaris UQ':
                  return 'dashboard';
                 break;  
            case 'Dosen':
                return 'login';
                break;
            case 'Yayasan':
                return 'dashboard';
                break;
                case 'Marketing':
                    return 'dashboard';
                    break;
            default:
            return 'login';    
            
            
        }
    }
    public function logout()
    {
        session()->forget(['userid', 'password', 'divisi']);
        // Hapus juga token CSRF
        csrf_token(); // Ini akan menghasilkan token CSRF baru
        return redirect('/login');
    }
}
