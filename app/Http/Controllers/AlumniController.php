<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function showAlumni(Request $request)
    {
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas','idprodi', 'prodi')->distinct()->get();
        // Pass the data to the view
        return view('alumni.alumni', compact('allIdKampus','allProdi'));
    }

    public function viewAlumni(Request $request)
    {

        $fakultas = $request->input('fakultas');
        $idfakultas = $request->input('id_fakultas');
        $lulus = $request->input('lulus');
        $idkampus = $request->input('idkampus');
        $prodi = $request->input('prodi');
        $lokasi = $request->input('lokasi');
        $results = DB::table('SKLulusMahasiswa as a')
        ->leftJoin('mahasiswa as b', 'a.NPM', '=', 'b.NPM')
        ->select('a.NPM', 'b.NAMA', 'b.PRODI', 'a.TglWisuda','b.hp')
        ->where('a.IdKampus', $idkampus)
        ->where('b.PRODI', $prodi)
        ->whereYear('a.TglWisuda', $lulus)
        ->get();
        //dd($results);
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas','idprodi', 'prodi')->distinct()->get();
        return view('alumni.alumni', compact('allIdKampus','allProdi','results','idkampus','prodi','lokasi','lulus',
        'idfakultas','fakultas'));
    }
}
