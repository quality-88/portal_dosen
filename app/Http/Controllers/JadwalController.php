<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function showJadwal (Request $request)
    {
       
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
        $allKurikulum = DB::table('kurikulum')->select('kurikulum', 'tahunajaran1')->distinct()->get();
        return view('jadwal.inputjadwal',compact('allIdKampus','allProdi','allKurikulum'));   
    }
    public function fetchJadwal(Request $request)
{
    // Ambil data dari permintaan
    $harijadwal = $request->harijadwal;
    $idkampus = $request->idkampus;
    $prodi = $request->prodi;
    $idfakultas = $request->idfakultas;
    $ta = $request->ta;
    $semester = $request->semester;

    // Query untuk mendapatkan jadwal berdasarkan data yang dipilih
    $jadwal = DB::table('jadwalprimary')
    ->join('dosen', 'jadwalprimary.iddosen', '=', 'dosen.iddosen')
    ->join('matakuliah', 'jadwalprimary.idmk', '=', 'matakuliah.idmk')
    ->leftJoin('dosen AS dosen2', 'jadwalprimary.iddosen2', '=', 'dosen2.iddosen') // Join kembali ke tabel dosen untuk dosen kedua
    ->select('jadwalprimary.idprimary', 'jadwalprimary.kelas', 'jadwalprimary.kurikulum', 'jadwalprimary.idmk',
        'jadwalprimary.sks', 'jadwalprimary.idruang', 'jadwalprimary.jammasuk', 'jadwalprimary.jamkeluar',
        'jadwalprimary.nosilabus', 'matakuliah.matakuliah', 'jadwalprimary.iddosen', 'dosen.nama AS nama',
        'jadwalprimary.Keterangan', 'jadwalprimary.HonorSKS', 'jadwalprimary.iddosen2', 'jadwalprimary.harijadwal',
        'dosen2.nama AS nama_dosen2') // Mengambil nama dosen kedua dengan alias nama_dosen2
    ->where('jadwalprimary.harijadwal', $harijadwal)
    ->where('jadwalprimary.idkampus', $idkampus)
    ->where('jadwalprimary.prodi', $prodi)
    ->where('jadwalprimary.idfakultas', $idfakultas)
    ->where('jadwalprimary.ta', $ta)
    ->where('jadwalprimary.semester', $semester)
    ->get();


    // Return view dengan data jadwal yang dipilih
    return response()->json($jadwal);
}
public function fetchFakultas(Request $request)
{
    try {
        // Log the input parameter
        \Log::info('Input Parameter (prodi):', [$request->input('prodi')]);

        // Fetch corresponding idFakultas and fakultas based on $prodiId
        $fakultasData = DB::table('prodifakultas')
            ->join('fakultas', 'prodifakultas.idfakultas', '=', 'fakultas.idfakultas')
            ->select('prodifakultas.idfakultas', 'fakultas.fakultas')
            ->where('prodifakultas.prodi', $request->input('prodi'))
            ->first();

        // Log the raw SQL query
        \Log::info('SQL Query:', [DB::getQueryLog()]);

        if ($fakultasData) {
            // Log the retrieved data
            \Log::info('Retrieved Data:', [$fakultasData]);

            // Return the data as JSON response
            return response()->json($fakultasData);
        } else {
            // Log that no data was found
            \Log::info('No Data Found');

            // If no data is found, return a response indicating no data
            return response()->json(['no_data' => true]);
        }
    } catch (\Exception $e) {
        // Log the exception
        \Log::error('Exception:', [$e->getMessage()]);

        // Return a response indicating an error (adjust this based on your needs)
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
public function getKelas(Request $request)
{
    $idKampus = $request->input('idkampus');
    $allKelas = DB::table('kelas')
        ->select('kelas')
        ->where('idkampus', $idKampus)
        ->distinct()
        ->get();
    return response()->json($allKelas);
}
public function getIDMK(Request $request)
{
$kurikulum = $request->input('kurikulum');
$ta = $request->input('ta');
$semester = $request->input('semester');
$prodi = $request->input('prodi');
$data = DB::table('MKTASEMESTER')
    ->select('MKTASEMESTER.IdDosenPengampu', 'prodimk.idmk', 'prodimk.sks', 'dosen.nama AS NamaDosen', 'matakuliah.matakuliah AS NamaMataKuliah')
    ->join('prodimk', 'MKTASEMESTER.idmk', '=', 'prodimk.idmk')
    ->join('dosen', 'MKTASEMESTER.IdDosenPengampu', '=', 'dosen.iddosen')
    ->join('matakuliah', 'matakuliah.idmk', '=', 'prodimk.idmk')
    ->where('MKTASEMESTER.ta', $ta)
    ->where('MKTASEMESTER.semester', $semester)
    ->where('prodimk.kurikulum', $kurikulum)
    ->where('prodimk.prodi', $prodi)
    ->distinct()
    ->get();
    return response()->json($data);
}
}
