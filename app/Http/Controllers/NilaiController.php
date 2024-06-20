<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Toastr;

class NilaiController extends Controller
{
    public function showNilai()
{
     $IdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $Prodis = DB::table('prodi')->select( 'prodi')->distinct()->get(); // Replace 'your_prodi_table' with your actual table name
    return view('backend.nilai.nilai',compact('IdKampus','Prodis'));
}
public function submitForm(Request $request)
{
    
    ini_set('max_execution_time', 300);
    session_start();
    $idKampus = $request->input('idkampus');
    $prodi = $request->input('prodi');
    $semester = $request->input('semester');
    $ta = $request->input('ta');
    session(['prodi' => $prodi]);
    session(['idKampus' => $idKampus]);
    session(['ta' => $ta]);
    session(['semester' => $semester]);

    // Query pertama
    $results = DB::table('KRSNilaiWebDetail2')
        ->join('CekIsiNilaiDosenWeb', function ($join) {
            $join->on('KRSNilaiWebDetail2.IdMK', '=', 'CekIsiNilaiDosenWeb.IdMK')
                ->on('KRSNilaiWebDetail2.IdDosen', '=', 'CekIsiNilaiDosenWeb.IdDosen')
                ->on('KRSNilaiWebDetail2.Kelas', '=', 'CekIsiNilaiDosenWeb.Kelas')
                ->on('KRSNilaiWebDetail2.TA', '=', 'CekIsiNilaiDosenWeb.TA')
                ->on('KRSNilaiWebDetail2.SEMESTER', '=', 'CekIsiNilaiDosenWeb.SEMESTER')
                ->on('KRSNilaiWebDetail2.IDKAMPUS', '=', 'CekIsiNilaiDosenWeb.IDKAMPUS')
                ->on('KRSNilaiWebDetail2.Prodi', '=', 'CekIsiNilaiDosenWeb.Prodi');
        })
        ->select(
            'KRSNilaiWebDetail2.IDDosen',
            'KRSNilaiWebDetail2.NamaDosen',
            'KRSNilaiWebDetail2.IdKampus',
            'KRSNilaiWebDetail2.Lokasi',
            'KRSNilaiWebDetail2.Prodi',
            'KRSNilaiWebDetail2.IdMK',
            'KRSNilaiWebDetail2.MataKuliah',
            'KRSNilaiWebDetail2.Kelas'
        )
        ->where('KRSNilaiWebDetail2.TA', '=', $ta)
        ->where('KRSNilaiWebDetail2.SEMESTER', '=', $semester)
        ->where('KRSNilaiWebDetail2.Prodi', '=', $prodi)
        ->where('KRSNilaiWebDetail2.IDKAMPUS', '=', $idKampus)
        ->distinct()
        ->get();

    // Fetch data for both counts in a single query
    $mhsCounts = DB::table('TblSementaraJadwalKuliah')
        ->selectRaw('idmk, kelas, max(jlhmhs) as tbl_mhs_count')
        ->where([
            ['TA', '=', $ta],
            ['semester', '=', $semester],
            ['idkampus', '=', $idKampus],
            ['prodi', '=', $prodi],
        ])
        ->groupBy('idmk', 'kelas')
        ->get()
        ->keyBy(function ($item) {
            return $item->idmk . '-' . $item->kelas;
        });

    $krsCounts = DB::table('KRSNilaiWebDetail2')
        ->selectRaw('idmk, kelas, count(distinct NPM) as krs_mhs_count')
        ->where([
            ['TA', '=', $ta],
            ['semester', '=', $semester],
            ['idkampus', '=', $idKampus],
            ['prodi', '=', $prodi],
        ])
        ->groupBy('idmk', 'kelas')
        ->get()
        ->keyBy(function ($item) {
            return $item->idmk . '-' . $item->kelas;
        });

    // Calculate differences
    $differences = [];
    foreach ($results as $result) {
        $key = $result->IdMK . '-' . $result->Kelas;
        $tblCount = isset($mhsCounts[$key]) ? $mhsCounts[$key]->tbl_mhs_count : 0;
        $krsCount = isset($krsCounts[$key]) ? $krsCounts[$key]->krs_mhs_count : 0;

        if ($tblCount < $krsCount || $krsCount == 0) {
            $tblCount = DB::table('MhsKrsJadwalDetail')
                ->where([
                    ['TA', '=', $ta],
                    ['semester', '=', $semester],
                    ['idkampus', '=', $idKampus],
                    ['prodi', '=', $prodi],
                    ['idmk', '=', $result->IdMK],
                    ['kelas', '=', $result->Kelas],
                ])
                ->count('NPM');
        }

        $difference = $tblCount - $krsCount;
        $differences[] = [
            'IdMK' => $result->IdMK,
            'Kelas' => $result->Kelas,
            'Difference' => $difference,
        ];
    }

    session(['results' => $results, 'differences' => $differences]);
    return view('backend.nilai.cetaknilai', [
        'results' => $results,
        'differences' => $differences,
    ]);
}


public function showCetakNilai()
{
    // You can use the session data or any other logic to retrieve the required data
    $results = session('results');
    ($results);
    $differences = session('differences');
    ($differences);
    return view('backend.nilai.cetaknilai', ['results' => $results],['differences' => $differences]);
}
public function showNilaKelas(Request $request)
{   
    
        // Retrieve parameters from the URL
        $nama = $request->input('nama');
        $iddosen = $request->input('iddosen');
        $matakuliah = $request->input('matakuliah');
        $idMK = $request->input('idMK');
        $kelas = $request->input('kelas');
        $idKampus = $request->input('idKampus');
        $prodi = $request->input('prodi');
        $Lokasi =$request->input('Lokasi');
        session(['prodi' => $prodi]);
        session(['idKampus' => $idKampus]);
        
        session(['idMK' => $idMK]);
        session(['kelas' => $kelas]); 
        
        session(['nama' => $nama]);
        session(['matakuliah' => $matakuliah]);

        session(['iddosen' => $iddosen]);
        session(['Lokasi' => $Lokasi]);
        $TA = session('ta');
        $semester = session('semester');
        

        // Perform the database query using parameters
    $result = DB::table('KRSNilaiWebDetail2')
    ->select('NPM', 'NamaMahasiswa as Nama', 'NilaiAbsen', 'NilaiTugas', 'NilaiMid', 'NilaiUAS', 'NilaiAkhir', 'NilaiHuruf')
    //->where('iddosen', $iddosen)
    ->where('IdMK', $idMK)
    ->where('Kelas', $kelas)
    ->where('IdKampus', $idKampus)
    ->where('Prodi', $prodi)
    ->where('TA', $TA)
    ->where('Semester', $semester)
    ->distinct()
    ->get();
//dd($result);
    $jumlahMahasiswa = DB::table('TblSementaraJadwalKuliah')
    ->where('TA', '=', $TA)
    ->where('semester', '=', $semester)
    ->where('idkampus', '=', $idKampus)
    ->where('prodi', '=', $prodi)
     ->where('IdMK', $idMK)
     ->where('Kelas', $kelas)
     ->max('jlhmhs');

    $jumlahMahasiswaHasilQuery = count($result);
    
    // Hitung jumlah mahasiswa yang belum diberikan nilai
    $jumlahMahasiswaBelumDiberiNilai = $jumlahMahasiswa - $jumlahMahasiswaHasilQuery;
  
    // Check if the counts don't match
    if (($jumlahMahasiswaBelumDiberiNilai) !== 0) {
    // Jumlah tidak sesuai, berikan notifikasi di view
    $mahasiswaBelumDiberiNilai = DB::table('MhsKrsJadwalDetail')
    ->select('MhsKrsJadwalDetail.NPM', 'Mahasiswa.Nama')
    ->Join('Mahasiswa', 'MhsKrsJadwalDetail.NPM', '=', 'Mahasiswa.NPM')
    ->where('MhsKrsJadwalDetail.TA', '=', $TA)
    ->where('MhsKrsJadwalDetail.semester', '=', $semester)
    ->where('MhsKrsJadwalDetail.idkampus', '=', $idKampus)
    ->where('MhsKrsJadwalDetail.prodi', '=', $prodi)
    ->where('MhsKrsJadwalDetail.IdMK', $idMK)
    ->where('MhsKrsJadwalDetail.Kelas', $kelas)
    ->whereNotIn('MhsKrsJadwalDetail.NPM', function ($query) use ($idMK, $kelas, $idKampus, $prodi, $TA, $semester) {
        $query->select('KRSNilaiWebDetail2.NPM')
            ->from('KRSNilaiWebDetail2')
            ->where('KRSNilaiWebDetail2.IdMK', $idMK)
            ->where('KRSNilaiWebDetail2.Kelas', $kelas)
            ->where('KRSNilaiWebDetail2.IdKampus', $idKampus)
            ->where('KRSNilaiWebDetail2.Prodi', $prodi)
            ->where('KRSNilaiWebDetail2.TA', $TA)
            ->where('KRSNilaiWebDetail2.Semester', $semester);
    })
    ->distinct()
    ->get();


return view('backend.nilai.nilaikelas', [
    'result' => $result,
    'jumlahMahasiswa' => $jumlahMahasiswa,
    'kelas' => $kelas,
    'nama' => $nama,
    'idMK' => $idMK,
    'idKampus' => $idKampus,
    'prodi' => $prodi,
    'matakuliah'=>$matakuliah,
    'jumlahTidakSesuai' => $jumlahMahasiswaBelumDiberiNilai, // Tambahkan flag notifikasi
    'mahasiswaBelumDiberiNilai' => $mahasiswaBelumDiberiNilai, // Kirim data mahasiswa yang belum diberi nilai
]);
    } else {
    // Jumlah sesuai, tampilkan normal di view
    return view('backend.nilai.nilaikelas', [
        'result' => $result,
        'jumlahMahasiswa' => $jumlahMahasiswa,
        'kelas' => $kelas,
        'nama' => $nama,
        'idMK' => $idMK,
        'idKampus' => $idKampus,
        'prodi' => $prodi,
        'matakuliah'=>$matakuliah
    ]);
}
  //dd($matakuliah);      

}
}
