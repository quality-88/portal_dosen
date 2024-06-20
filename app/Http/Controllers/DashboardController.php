<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function adminDashboard()
    {
    //$fakultasDosen = DB::table('fakultas')
    //->join('dosen', 'fakultas.iddekan', '=', 'dosen.iddosen')
    //->select('fakultas.fakultas', 'dosen.nama as nama')
    //->get();
//
    //$fakultasProdi = DB::table('prodi')
    //->join('fakultas', 'prodi.idfakultas', '=', 'fakultas.idfakultas')
    //->select('fakultas.fakultas', 'prodi.prodi as prodi')
    //->get();

        return view('admin.index');
    }
}