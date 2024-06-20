<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function findMahasiswa(Request $request)
{
    $term = $request->input('term');

    // Perform the search in both tables
    $mahasiswa = DB::table('mahasiswa')
    ->select('npm', 'nama')
    ->where('nama', 'like', '%' . $term . '%')
    ->get();
    // Combine the results from both tables
    return response()->json($mahasiswa);
}
}
