<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JabatanFungsiController extends Controller
{
    public function showJabatanFungsi()
{
    // Ambil data jabatan dari database
    $jabatan = DB::table('jabatanfungsi')->get();
    
    // Kirimkan data jabatan ke view
    return view('CMS.jabatanfungsi', compact('jabatan')); 
} 

    public function simpanJabatan(Request $request)
{
    $jabatan = $request->input('jabatan');
    
    // Memeriksa apakah jabatan sudah ada dalam database
    $existingJabatan = DB::table('jabatanfungsi')
                        ->where('jabatanfungsi', $jabatan)
                        ->exists();
    
    // Jika jabatan sudah ada, kirimkan pesan error
    if($existingJabatan) {
        return response()->json(['error' => 'Jabatan sudah ada!']);
    }
    
    // Jika jabatan belum ada, simpan jabatan baru ke dalam database
    DB::table('jabatanfungsi')->insert([
        'jabatanfungsi' => $jabatan
    ]); 
    
    // Mengirimkan pesan sukses jika penyimpanan berhasil
    return response()->json(['success' => 'Data berhasil ditambahkan!']); 
}

}
