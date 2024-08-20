<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class DosenController extends Controller
{
    public function showKartuMengajar(Request $request)
    {
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
        return view('dosen.kartumengajar', compact('allIdKampus','allProdi'));
    }

    public function getHonorSKS(Request $request)
{
    $prodi = $request->input('prodi');
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('dosen')
    ->select(
        'iddosen as idpengajar',
        'nama',
        'proditerdaftar',
        DB::raw("CASE
            WHEN prodi LIKE 'S2%' THEN honorskss2
            ELSE honorsks
        END AS honor")
    );

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('nama', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->distinct();
    $results = $results->get();
    $results = $results->map(function ($item) {
        $item->honor = number_format($item->honor, 0, ',', '.'); // Format as currency
        return $item;
    });
    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function viewKartuMengajar(Request $request)
{
    $semester = $request->input('semester');
    $ta = $request->input('ta');
    $idkampus = $request->input('idkampus');
    $iddosen = $request->input('iddosen');
    $lokasi = $request->input('lokasi');
    session(['ta' => $ta]);
    session(['iddosen' => $iddosen]);
    session(['idkampus' => $idkampus]);
    session(['lokasi' => $lokasi]);
    session(['semester' => $semester]);
    // Query untuk mendapatkan jadwal berdasarkan data yang dipilih
    $jadwal = DB::table('jadwalprimary')
        ->join('dosen', 'jadwalprimary.iddosen', '=', 'dosen.iddosen')
        ->join('matakuliah', 'jadwalprimary.idmk', '=', 'matakuliah.idmk')
        ->leftJoin('dosen AS dosen2', 'jadwalprimary.iddosen2', '=', 'dosen2.iddosen')
        ->leftJoin('dosen AS dosen3', 'jadwalprimary.iddosen3', '=', 'dosen3.iddosen')
        ->leftJoin('dosen AS dosen4', 'jadwalprimary.iddosen4', '=', 'dosen4.iddosen')
        ->select(
            'jadwalprimary.idprimary',
            'jadwalprimary.kelas',
            'jadwalprimary.idmk',
            'jadwalprimary.sks',
            'jadwalprimary.idruang',
            'jadwalprimary.jammasuk',
            'jadwalprimary.jamkeluar',
            'matakuliah.matakuliah',
            'jadwalprimary.iddosen',
            'jadwalprimary.Keterangan',
            'jadwalprimary.iddosen2',
            'jadwalprimary.harijadwal',
            'jadwalprimary.hari',
            'dosen2.nama AS nama_dosen2',
            'jadwalprimary.iddosen3',
            'dosen3.nama AS nama_dosen3',
            'jadwalprimary.SK2',
            'jadwalprimary.iddosen4',
            'dosen4.nama AS nama_dosen4',
            'jadwalprimary.SK3',
            'jadwalprimary.prodi',
            DB::raw("
                CASE 
                    WHEN jadwalprimary.iddosen2 = $iddosen THEN 'Pengajar 1'
                    WHEN jadwalprimary.iddosen3 = $iddosen THEN 'Pengajar 2'
                    ELSE 'Pengajar Utama'
                END AS peran_pengajar
            ")
        )
        ->where(function($query) use ($iddosen) {
            $query->where('jadwalprimary.iddosen2', $iddosen)
                  ->orWhere('jadwalprimary.iddosen3', $iddosen);
        })
        ->where('jadwalprimary.idkampus', $idkampus)
        ->where('jadwalprimary.ta', $ta)
        ->where('jadwalprimary.semester', $semester)
        ->get();
        $totalSKS = $jadwal->sum('sks');

    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
    $results = DB::table('dosen')
    ->select('nama', 'iddosen', 'NIDNNTBDOS as nidn', 'alamat', 'hp', 'statusdosen', 'emailpribadi')
    ->where('iddosen',$iddosen)
    ->get();
    //dd($results);
    // Return view dengan data jadwal yang dipilih
    return view('dosen.kartumengajar', compact('jadwal', 'allIdKampus', 'allProdi','totalSKS','results'));
}

}
