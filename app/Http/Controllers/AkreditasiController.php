<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AkreditasiController extends Controller
{
   public function showUPPS()
   {
    
    return view('akreditasi.upps');
   }

   public function viewUPPS(Request $request)
   {
      $universitas = $request->input('universitas');
      $ta = $request->input('ta'); // Contoh, jika ingin menggunakan ini di query
      $program = $request->input('program'); // Misalnya, user memilih S1 atau S2

        session(['universitas' => $universitas]);
        
        session(['ta' => $ta]);

      $query = DB::table('prodifakultas')
      ->select(
          'prodi', 
          'kajur', 
          'akreditasi', 
          'izinTgl', 
          'NoAkreditasiProdi',
        'GelarPanjang');
  
      if ($universitas) {
          if ($universitas == 'UQB') {
              $query->where('prodi', 'LIKE', '%UQB');
          } else if ($universitas == 'UQM') {
              $query->where('prodi', 'NOT LIKE', '%UQB');
          }
      }
  

      $prodiList = $query->get();
      $countLulusByProdi = [];
      foreach ($prodiList as $prodi) {
          $countLulus = DB::table('mahasiswa')
                          ->where('thnLulusmejahijau', $ta)
                          ->where('prodi', $prodi->prodi)
                          ->count();
          $countLulusByProdi[$prodi->prodi] = $countLulus;
      }
  
      // Mendapatkan jumlah total mahasiswa untuk setiap prodi
      $countTotalByProdi = [];
      foreach ($prodiList as $prodi) {
          $countTotal = DB::table('mahasiswa')
                          ->where('ta', $ta)
                          ->where('prodi', $prodi->prodi)
                          ->count();
          $countTotalByProdi[$prodi->prodi] = $countTotal;
      }
      $countDosenByProdi = [];
      foreach ($prodiList as $prodi) {
          $countDosen = DB::table('dosen')
                         ->where('StatusDosenAktif', 'aktif')
                         ->whereIn('PRODITERDAFTAR',explode(',', $prodi->prodi))
                         ->count();
          $countDosenByProdi[$prodi->prodi] = $countDosen;
      }
      // Hitung tglkadaluarsa untuk setiap entri
      foreach ($prodiList as $prodi) {
         $tglizin = Carbon::parse($prodi->izinTgl);
         $tglkadaluarsa = $tglizin->addYears(5);
         $prodi->tglkadaluarsa = $tglkadaluarsa->format('Y-m-d'); // Tambahkan tglkadaluarsa ke objek $prodi
     } 
     $averageStudyPeriods = DB::table('mahasiswa')
     ->select(
         DB::raw('YEAR(CAST(tgllulusmh AS DATE)) AS tahun_lulus'),
         DB::raw('AVG(DATEDIFF(MONTH, CAST(tglmasuk AS DATE), CAST(tgllulusmh AS DATE)) / 12.0) AS rata_rata'),
         'prodi'
     )
     ->where('thnLulusmejahijau', $ta)
     ->whereRaw('CAST(tgllulusmh AS DATE) >= CAST(tglmasuk AS DATE)')
     ->groupBy(DB::raw('YEAR(CAST(tgllulusmh AS DATE))'), 'prodi')
     ->get()
     ->keyBy('prodi'); // Key by prodi for easy access
 
      // Mengembalikan data sebagai JSON atau ke view
      //return response()->json($prodiList);
      $data = [
         'prodiList' => $prodiList,
         'countLulusByProdi' => $countLulusByProdi,
         'countTotalByProdi' => $countTotalByProdi,
         'countDosenByProdi'=>$countDosenByProdi,
         'averageStudyPeriods'=>$averageStudyPeriods,

     ];
     //dd($data);
      return view('akreditasi.upps',compact('data','program','ta','universitas'));
   }



   public function showUPPSFakultas()
{
    // Mengambil data dari tabel 'fakultas'
    $allFakultas = DB::table('fakultas')->select('idfakultas', 'fakultas')->distinct()->get();
    
    // Mengirim data ke view 'akreditasi.uppsfakultas'
    return view('akreditasi.uppsfakultas', compact('allFakultas'));
}
   public function uppsFakultas (Request $request)
   {
    $idfakultas = $request->input('idfakultas');
    $fakultas = $request->input('fakultas');
    $ta = $request->input('ta'); // Contoh, jika ingin menggunakan ini di query
    $program = $request->input('program'); // Misalnya, user memilih S1 atau S2
    session(['idfakultas' => $idfakultas]);
    session(['fakultas' => $fakultas]);
    session(['ta' => $ta]);

    $query = DB::table('prodifakultas')
    ->select('prodi', 'kajur', 'akreditasi', 'izinTgl', 'NoAkreditasiProdi','GelarPanjang')
    ->where('idfakultas', $idfakultas);
    $prodiList = $query->get();
    $countLulusByProdi = [];
    foreach ($prodiList as $prodi) {
        $countLulus = DB::table('mahasiswa')
                        ->where('thnLulusmejahijau', $ta)
                        ->where('prodi', $prodi->prodi)
                        ->count();
        $countLulusByProdi[$prodi->prodi] = $countLulus;
    }
    // Mendapatkan jumlah total mahasiswa untuk setiap prodi
    $countTotalByProdi = [];
    foreach ($prodiList as $prodi) {
        $countTotal = DB::table('mahasiswa')
                        ->where('ta', $ta)
                        ->where('prodi', $prodi->prodi)
                        ->count();
        $countTotalByProdi[$prodi->prodi] = $countTotal;
    }
    $countDosenByProdi = [];
    foreach ($prodiList as $prodi) {
        $countDosen = DB::table('dosen')
                       ->where('StatusDosenAktif', 'aktif')
                       ->whereIn('PRODITERDAFTAR',explode(',', $prodi->prodi))
                       ->count();
        $countDosenByProdi[$prodi->prodi] = $countDosen;
    }
    // Hitung tglkadaluarsa untuk setiap entri
    foreach ($prodiList as $prodi) {
       $tglizin = Carbon::parse($prodi->izinTgl);
       $tglkadaluarsa = $tglizin->addYears(5);
       $prodi->tglkadaluarsa = $tglkadaluarsa->format('Y-m-d'); // Tambahkan tglkadaluarsa ke objek $prodi
   } 
   $averageStudyPeriods = DB::table('mahasiswa')
   ->select(
       DB::raw('YEAR(CAST(tgllulusmh AS DATE)) AS tahun_lulus'),
       DB::raw('AVG(DATEDIFF(MONTH, CAST(tglmasuk AS DATE), CAST(tgllulusmh AS DATE)) / 12.0) AS rata_rata'),
       'prodi'
   )
   ->where('thnLulusmejahijau', $ta)
   ->whereRaw('CAST(tgllulusmh AS DATE) >= CAST(tglmasuk AS DATE)')
   ->where('idfakultas', $idfakultas)
   ->groupBy(DB::raw('YEAR(CAST(tgllulusmh AS DATE))'), 'prodi')
   ->get()
   ->keyBy('prodi'); // Key by prodi for easy access

 
    // Mengembalikan data sebagai JSON atau ke view
    //return response()->json($prodiList);
    $data = [
        'prodiList' => $prodiList,
        'countLulusByProdi' => $countLulusByProdi,
        'countTotalByProdi' => $countTotalByProdi,
        'countDosenByProdi' => $countDosenByProdi,
        'averageStudyPeriods' => $averageStudyPeriods,
    ];
   $allFakultas = DB::table('fakultas')->select('idfakultas', 'fakultas')->distinct()->get();
   //dd($data);
    return view('akreditasi.uppsfakultas',compact('data','allFakultas', 'idfakultas', 'ta', 'fakultas'));
   }
   public function SKprodi(Request $request)
   {
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $Prodis = DB::table('prodi')->select( 'prodi')->distinct()->get(); 
    $sk = DB::table('prodi')->get();
    //dd($allIdKampus);
    return view('akreditasi.skprodi',compact('Prodis','allIdKampus','sk'));
   }
   public function showSKprodi(Request $request)
   {
    $prodi = $request->input('prodi');
    $tanggalmulai = $request->input('tanggalmulai');
    $tanggalakhir = $request->input('tanggalakhir');
    $idKampus = $request->input('idkampus');
    DB::table('prodi')
    ->where('idkampus',$idKampus)
    ->where('prodi', $prodi)
    ->update([
        'tanggalsk' => $tanggalmulai,
        'tglskselesai' => $tanggalakhir,
    ]);    
   }

   public function showjlhMahasiswa()
   {
    return view('akreditasi.jlhmahasiswa');
   }
   public function viewjlhMahasiswa(Request $request)
   {
    $universitas = $request->input('universitas');
    $ta_start = $request->input('ta_mulai');
    $ta_end = $request->input('ta_akhir'); // Contoh, jika ingin menggunakan ini di query
    session(['universitas' => $universitas]);
    session(['ta_start' => $ta_start]);
    session(['ta_end' => $ta_end]);
    $result = DB::table('PMBRegistrasi')
    ->selectRaw('year(tgldaftar) AS ta, COUNT(NOPESERTA) AS jumlah')
    ->whereYear('tgldaftar', '>=', $ta_start) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tgldaftar', '<=', $ta_end)
    ->where('universitas', $universitas)
    ->groupBy(DB::raw('year(tgldaftar)'))
    ->get();
    $result1 = DB::table('PMBRegistrasi')
    ->selectRaw('year(tgldaftar) AS ta, COUNT(NOPESERTA) AS jumlah')
    ->whereYear('tgldaftar', '>=', $ta_start) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tgldaftar', '<=', $ta_end)
    ->where('universitas', $universitas)
    ->whereNotNull('NOPESERTA')
    ->groupBy(DB::raw('year(tgldaftar)'))
    ->get();
    $result2 = DB::table('mahasiswa')
    ->selectRaw('year(tglmasuk) AS ta, COUNT(npm) AS jumlah') // pastikan rentang tahun ta sesuai
    ->whereYear('tglmasuk', '>=', $ta_start) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tglmasuk', '<=', $ta_end) 
    ->where('universitas', $universitas)
    ->whereNotNull('npm')
    ->where('tipekelas', 'NOT LIKE', 'pindahan%')
    ->groupBy(DB::raw('year(tglmasuk)'))
    ->get();
    //dd($result2);
    $result3 =DB::table('mahasiswa')
    ->selectRaw('YEAR(tglmasuk) AS ta, COUNT(npm) AS jumlah')
    ->whereYear('tglmasuk', '>=', $ta_start)
    ->whereYear('tglmasuk', '<=', $ta_end)
    ->where('universitas', $universitas)
    ->whereNotNull('npm')
    ->groupBy(DB::raw('YEAR(tglmasuk)'))
    ->get();
    $data = [
        'result' => $result,
        'result1' => $result1,
        'result2' => $result2,
        'result3' => $result3,
    ];
    return view('akreditasi.jlhmahasiswa',compact('data', 'ta_start','ta_end', 'universitas'));
   }
   public function showjlhMahasiswaProdi()
   {
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.jlhmahasiswaprodi',compact('prodis'));
   }
   public function viewjlhMahasiswaProdi(Request $request)
   {
    $universitas = $request->input('universitas');
    $ta_start = $request->input('ta_mulai');
    $ta_end = $request->input('ta_akhir'); 
    $prodi = $request->input('prodi');// Contoh, jika ingin menggunakan ini di query
    session(['universitas' => $universitas]);
    session(['ta_start' => $ta_start]);
    session(['ta_end' => $ta_end]);
    session(['prodi' => $prodi]);
    $result = DB::table('PMBRegistrasi')
    ->selectRaw('year(tgldaftar) AS ta, COUNT(NOPESERTA) AS jumlah')
    ->whereYear('tgldaftar', '>=', $ta_start) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tgldaftar', '<=', $ta_end)
    ->where('universitas', $universitas)
    ->where('prodi',$prodi)
    ->groupBy(DB::raw('year(tgldaftar)'))
    ->get();
    $result1 = DB::table('PMBRegistrasi')
    ->selectRaw('year(tgldaftar) AS ta, COUNT(NOPESERTA) AS jumlah')
    ->whereYear('tgldaftar', '>=', $ta_start) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tgldaftar', '<=', $ta_end)
    ->where('universitas', $universitas)
    ->whereNotNull('NOPESERTA')
    ->where('prodi',$prodi)
    ->groupBy(DB::raw('year(tgldaftar)'))
    ->get();
    $result2 = DB::table('mahasiswa')
    ->selectRaw('year(tglmasuk) AS ta, COUNT(npm) AS jumlah') // pastikan rentang tahun ta sesuai
    ->whereYear('tglmasuk', '>=', $ta_start) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tglmasuk', '<=', $ta_end) 
    ->where('universitas', $universitas)
    ->whereNotNull('npm')
    ->where('prodi',$prodi)
    ->where('tipekelas', 'NOT LIKE', 'pindahan%')
    ->groupBy(DB::raw('year(tglmasuk)'))
    ->get();
    //dd($result2);
    $result3 =DB::table('mahasiswa')
    ->selectRaw('YEAR(tglmasuk) AS ta, COUNT(npm) AS jumlah')
    ->whereYear('tglmasuk', '>=', $ta_start)
    ->whereYear('tglmasuk', '<=', $ta_end)
    ->where('universitas', $universitas)
    ->whereNotNull('npm')
    ->where('prodi',$prodi)
    ->groupBy(DB::raw('YEAR(tglmasuk)'))
    ->get();

    $data = [
        'result' => $result,
        'result1' => $result1,
        'result2' => $result2,
        'result3' => $result3,
    ];
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.jlhmahasiswaprodi',compact('data', 'ta_start','ta_end', 'universitas','prodi','prodis'));
   }
   //Rekap Mahasiswa Aktif/Tidak Aktif
   public function showRekap()
   {
    
    return view('akreditasi.rekapmahasiswa');
   }

   public function viewRekap(Request $request)
   {
    $universitas = $request->input('universitas');
    $tipeKelas = $request->input('tipekelas');
    $ta_start = $request->input('ta_mulai');
    $ta_end = $request->input('ta_akhir'); // Contoh, jika ingin menggunakan ini di query
    session(['universitas' => $universitas]);
    session(['ta_start' => $ta_start]);
    session(['ta_end' => $ta_end]);
    session(['tipekelas' => $tipeKelas]);
    $results = DB::table('krsdetail AS A')
    ->select(DB::raw("
        CASE 
            WHEN SUBSTRING(A.npm, 1, 2) = '14' THEN 'Angkatan 2014'
            WHEN SUBSTRING(A.npm, 1, 2) = '15' THEN 'Angkatan 2015'
            WHEN SUBSTRING(A.npm, 1, 2) = '16' THEN 'Angkatan 2016'
            WHEN SUBSTRING(A.npm, 1, 2) = '17' THEN 'Angkatan 2017'
            WHEN SUBSTRING(A.npm, 1, 2) = '18' THEN 'Angkatan 2018'
            WHEN SUBSTRING(A.npm, 1, 2) = '19' THEN 'Angkatan 2019'
            WHEN SUBSTRING(A.npm, 1, 2) = '20' THEN 'Angkatan 2020'
            WHEN SUBSTRING(A.npm, 1, 2) = '21' THEN 'Angkatan 2021'
            WHEN SUBSTRING(A.npm, 1, 2) = '22' THEN 'Angkatan 2022'
            WHEN SUBSTRING(A.npm, 1, 2) = '23' THEN 'Angkatan 2023'
            WHEN SUBSTRING(A.npm, 1, 2) = '24' THEN 'Angkatan 2024'
            ELSE 'Angkatan Lain'
        END AS Angkatan,
        COUNT(DISTINCT A.npm) AS JumlahMahasiswa
    "))
    ->leftJoin('krs AS B', 'A.NPM', '=', 'B.NPM')
    ->leftJoin('mahasiswa AS C', 'A.NPM', '=', 'C.NPM')
    ->where('A.TA', $ta_start)
    ->where('B.STATUSMHS', 'AKTIF')
    ->where('C.TIPEKELAS', 'like', '%baru reguler%')
    ->groupBy(DB::raw("
        CASE 
            WHEN SUBSTRING(A.npm, 1, 2) = '14' THEN 'Angkatan 2014'
            WHEN SUBSTRING(A.npm, 1, 2) = '15' THEN 'Angkatan 2015'
            WHEN SUBSTRING(A.npm, 1, 2) = '16' THEN 'Angkatan 2016'
            WHEN SUBSTRING(A.npm, 1, 2) = '17' THEN 'Angkatan 2017'
            WHEN SUBSTRING(A.npm, 1, 2) = '18' THEN 'Angkatan 2018'
            WHEN SUBSTRING(A.npm, 1, 2) = '19' THEN 'Angkatan 2019'
            WHEN SUBSTRING(A.npm, 1, 2) = '20' THEN 'Angkatan 2020'
            WHEN SUBSTRING(A.npm, 1, 2) = '21' THEN 'Angkatan 2021'
            WHEN SUBSTRING(A.npm, 1, 2) = '22' THEN 'Angkatan 2022'
            WHEN SUBSTRING(A.npm, 1, 2) = '23' THEN 'Angkatan 2023'
            WHEN SUBSTRING(A.npm, 1, 2) = '24' THEN 'Angkatan 2024'
            ELSE 'Angkatan Lain'
        END
    "))
    ->orderBy('Angkatan')
    ->get();
    //dd($data);
    return view('akreditasi.rekapmahasiswa',compact('results', 'ta_start', 'universitas'));
   }
//Rekap mahasiswa /Prodi
public function showRekapProdi()
   {
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.rekapmahasiswaprodi',compact('prodis'));
   }

   public function viewRekapProdi(Request $request)
{
    $universitas = $request->input('universitas');
    $prodi = $request->input('prodi');
    $ta_start = $request->input('ta_mulai');
    
    session(['universitas' => $universitas]);
    session(['prodi' => $prodi]);
    session(['ta_start' => $ta_start]);

    $results = DB::table('krsdetail AS A')
    ->select(DB::raw("
        CASE 
            WHEN SUBSTRING(A.npm, 1, 2) = '14' THEN 'Angkatan 2014'
            WHEN SUBSTRING(A.npm, 1, 2) = '15' THEN 'Angkatan 2015'
            WHEN SUBSTRING(A.npm, 1, 2) = '16' THEN 'Angkatan 2016'
            WHEN SUBSTRING(A.npm, 1, 2) = '17' THEN 'Angkatan 2017'
            WHEN SUBSTRING(A.npm, 1, 2) = '18' THEN 'Angkatan 2018'
            WHEN SUBSTRING(A.npm, 1, 2) = '19' THEN 'Angkatan 2019'
            WHEN SUBSTRING(A.npm, 1, 2) = '20' THEN 'Angkatan 2020'
            WHEN SUBSTRING(A.npm, 1, 2) = '21' THEN 'Angkatan 2021'
            WHEN SUBSTRING(A.npm, 1, 2) = '22' THEN 'Angkatan 2022'
            WHEN SUBSTRING(A.npm, 1, 2) = '23' THEN 'Angkatan 2023'
            WHEN SUBSTRING(A.npm, 1, 2) = '24' THEN 'Angkatan 2024'
            ELSE 'Angkatan Lain'
        END AS Angkatan,
        COUNT(DISTINCT A.npm) AS JumlahMahasiswa
    "))
    ->leftJoin('krs AS B', 'A.NPM', '=', 'B.NPM')
    ->leftJoin('mahasiswa AS C', 'A.NPM', '=', 'C.NPM')
    ->where('A.TA', $ta_start)
    ->where('A.prodi', $prodi)
    ->where('B.STATUSMHS', 'AKTIF')
    ->where('C.TIPEKELAS', 'like', '%baru reguler%')
    ->groupBy(DB::raw("
        CASE 
            WHEN SUBSTRING(A.npm, 1, 2) = '14' THEN 'Angkatan 2014'
            WHEN SUBSTRING(A.npm, 1, 2) = '15' THEN 'Angkatan 2015'
            WHEN SUBSTRING(A.npm, 1, 2) = '16' THEN 'Angkatan 2016'
            WHEN SUBSTRING(A.npm, 1, 2) = '17' THEN 'Angkatan 2017'
            WHEN SUBSTRING(A.npm, 1, 2) = '18' THEN 'Angkatan 2018'
            WHEN SUBSTRING(A.npm, 1, 2) = '19' THEN 'Angkatan 2019'
            WHEN SUBSTRING(A.npm, 1, 2) = '20' THEN 'Angkatan 2020'
            WHEN SUBSTRING(A.npm, 1, 2) = '21' THEN 'Angkatan 2021'
            WHEN SUBSTRING(A.npm, 1, 2) = '22' THEN 'Angkatan 2022'
            WHEN SUBSTRING(A.npm, 1, 2) = '23' THEN 'Angkatan 2023'
            WHEN SUBSTRING(A.npm, 1, 2) = '24' THEN 'Angkatan 2024'
            ELSE 'Angkatan Lain'
        END
    "))
    ->orderBy('Angkatan')
    ->get();
    // Fetch prodi list for dropdown selection
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 

    return view('akreditasi.rekapmahasiswaprodi', compact('results', 'ta_start', 'universitas', 'prodis', 'prodi'));
}
//Rekap mahasiswa karyawan /Prodi
public function showRekapKaryawanProdi()
   {
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.rekapmahasiswakaryawan',compact('prodis'));
   }

   public function viewRekapKaryawanProdi(Request $request)
{
    $universitas = $request->input('universitas');
    $prodi = $request->input('prodi');
    $ta_start = $request->input('ta_mulai');
    
    session(['universitas' => $universitas]);
    session(['prodi' => $prodi]);
    session(['ta_start' => $ta_start]);

    $results = DB::table('krsdetail AS A')
    ->select(DB::raw("
        CASE 
            WHEN SUBSTRING(A.npm, 1, 2) = '14' THEN 'Angkatan 2014'
            WHEN SUBSTRING(A.npm, 1, 2) = '15' THEN 'Angkatan 2015'
            WHEN SUBSTRING(A.npm, 1, 2) = '16' THEN 'Angkatan 2016'
            WHEN SUBSTRING(A.npm, 1, 2) = '17' THEN 'Angkatan 2017'
            WHEN SUBSTRING(A.npm, 1, 2) = '18' THEN 'Angkatan 2018'
            WHEN SUBSTRING(A.npm, 1, 2) = '19' THEN 'Angkatan 2019'
            WHEN SUBSTRING(A.npm, 1, 2) = '20' THEN 'Angkatan 2020'
            WHEN SUBSTRING(A.npm, 1, 2) = '21' THEN 'Angkatan 2021'
            WHEN SUBSTRING(A.npm, 1, 2) = '22' THEN 'Angkatan 2022'
            WHEN SUBSTRING(A.npm, 1, 2) = '23' THEN 'Angkatan 2023'
            WHEN SUBSTRING(A.npm, 1, 2) = '24' THEN 'Angkatan 2024'
            ELSE 'Angkatan Lain'
        END AS Angkatan,
        COUNT(DISTINCT A.npm) AS JumlahMahasiswa
    "))
    ->leftJoin('krs AS B', 'A.NPM', '=', 'B.NPM')
    ->leftJoin('mahasiswa AS C', 'A.NPM', '=', 'C.NPM')
    ->where('A.TA', $ta_start)
    ->where('A.prodi', $prodi)
    ->where('B.STATUSMHS', 'AKTIF')
    ->where('C.TIPEKELAS', 'like', '%pindahan khusus%')
    ->groupBy(DB::raw("
        CASE 
            WHEN SUBSTRING(A.npm, 1, 2) = '14' THEN 'Angkatan 2014'
            WHEN SUBSTRING(A.npm, 1, 2) = '15' THEN 'Angkatan 2015'
            WHEN SUBSTRING(A.npm, 1, 2) = '16' THEN 'Angkatan 2016'
            WHEN SUBSTRING(A.npm, 1, 2) = '17' THEN 'Angkatan 2017'
            WHEN SUBSTRING(A.npm, 1, 2) = '18' THEN 'Angkatan 2018'
            WHEN SUBSTRING(A.npm, 1, 2) = '19' THEN 'Angkatan 2019'
            WHEN SUBSTRING(A.npm, 1, 2) = '20' THEN 'Angkatan 2020'
            WHEN SUBSTRING(A.npm, 1, 2) = '21' THEN 'Angkatan 2021'
            WHEN SUBSTRING(A.npm, 1, 2) = '22' THEN 'Angkatan 2022'
            WHEN SUBSTRING(A.npm, 1, 2) = '23' THEN 'Angkatan 2023'
            WHEN SUBSTRING(A.npm, 1, 2) = '24' THEN 'Angkatan 2024'
            ELSE 'Angkatan Lain'
        END
    "))
    ->orderBy('Angkatan')
    ->get();
    // Fetch prodi list for dropdown selection
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 

    return view('akreditasi.rekapmahasiswakaryawan', compact('results', 'ta_start', 'universitas', 'prodis', 'prodi'));
}

public function detailKaryawanAktif (Request $request)
{
    $prodi = $request->input('prodi');
    $ta = $request->input('ta');
    $result = DB::table('krs as a')
    ->distinct()
    ->join('mahasiswa as b', 'b.npm', '=', 'a.npm')
    ->select('a.npm', 'b.nama', 'b.hp')
    ->where('a.STATUSMHS', 'aktif')
    ->where('a.ta', $ta)
    ->where('a.prodi', $prodi)
    ->where('b.TIPEKELAS', 'pindahan khusus')
    ->get();
    //dd($result);
    return view('akreditasi.detailkaryawan', compact('result'));
}
   //IPK LULUSAN
   public function showIPKLulusan()
   {
    return view('akreditasi.ipklulusan');
   }

   public function IPKLulusan(Request $request)
{
    $universitas = $request->input('universitas');
    $ta_start = $request->input('ta_mulai');
    $ta_end = $request->input('ta_akhir');
    
    session(['universitas' => $universitas]);
    session(['ta_start' => $ta_start]);
    session(['ta_end' => $ta_end]);

    $results = DB::select("
        SELECT 
            TA,
            MIN(IPK) AS Minimum_IPK,
            AVG(IPK) AS Rata_rata_IPK,
            MAX(IPK) AS Maksimum_IPK
        FROM (
            SELECT 
                krs.ta as TA,
                COALESCE(krs.NPM, krsm.NPM) AS NPM,
                COALESCE(krs.TotalNilai, 0) + COALESCE(krsm.TotalNilai, 0) AS TotalNilai,
                COALESCE(krs.TotalSKS, 0) + COALESCE(krsm.TotalSKS, 0) AS TotalSKS,
                (COALESCE(krs.TotalNilai, 0) + COALESCE(krsm.TotalNilai, 0)) / 
                (COALESCE(krs.TotalSKS, 0) + COALESCE(krsm.TotalSKS, 0)) AS IPK
            FROM
                (SELECT
                    YEAR(d.tgllulusmh) AS TA,
                    b.NPM,
                    SUM(CASE WHEN c.NilaiAngka IS NOT NULL THEN c.NilaiAngka * a.SKS ELSE 0 END) AS TotalNilai,
                    SUM(b.SKS) AS TotalSKS
                FROM krsdetail AS b
                JOIN matakuliah AS a ON b.IdMK = a.idmk
                LEFT JOIN SettingNilai AS c ON c.NilaiHuruf = b.NilaiAkhir
                LEFT JOIN mahasiswa d ON d.npm = b.npm
                WHERE b.universitas = ?
                AND YEAR(d.tgllulusmh) BETWEEN ? AND ?
                AND d.StatusMahasiswa = 'lulus'
                AND NOT EXISTS (
                    SELECT 1
                    FROM krsdetail AS d2
                    JOIN matakuliah AS e ON d2.IdMK = e.idmk
                    LEFT JOIN SettingNilai AS f ON f.NilaiHuruf = d2.NilaiAkhir
                    WHERE d2.npm = b.npm
                    AND d2.idmk = b.idmk
                    AND (f.NilaiAngka > c.NilaiAngka OR (f.NilaiAngka IS NULL AND c.NilaiAngka IS NOT NULL))
                    AND (f.NilaiAngka IS NOT NULL OR c.NilaiAngka IS NULL)
                )
                GROUP BY YEAR(d.tgllulusmh), b.NPM) AS krs
            FULL OUTER JOIN
                (SELECT 
                    YEAR(d.tgllulusmh) AS TA,
                    b.NPM,
                    SUM(c.NilaiAngka * a.SKS) AS TotalNilai,
                    SUM(b.SKS) AS TotalSKS
                FROM krsm AS b
                JOIN matakuliah AS a ON b.IdMK = a.idmk
                JOIN settingnilai AS c ON c.nilaihuruf = b.nilaiakhir
                LEFT JOIN mahasiswa d ON d.npm = b.npm
                WHERE b.universitas = ?
                AND d.statusmahasiswa = 'lulus'
                AND b.nilaiakhir IS NOT NULL
                AND YEAR(d.tgllulusmh) BETWEEN ? AND ?
                GROUP BY YEAR(d.tgllulusmh), b.NPM) AS krsm
            ON krs.NPM = krsm.NPM AND krs.TA = krsm.TA
        ) AS IPK_Results
        GROUP BY TA
    ", [$universitas, $ta_start, $ta_end, $universitas, $ta_start, $ta_end]);

    $total = DB::table('mahasiswa')
        ->select(DB::raw('YEAR(tgllulusmh) as TA, count(npm) as jumlah'))
        ->where('universitas', $universitas)
        ->whereBetween(DB::raw('YEAR(tgllulusmh)'), [$ta_start, $ta_end])
        ->groupBy(DB::raw('YEAR(tgllulusmh)'))
        ->get();

    $data = [
        'results' => $results,
        'total' => $total,
    ];

    return view('akreditasi.ipklulusan', compact('data', 'ta_start', 'ta_end', 'universitas'));
}
public function showIPKProdi(Request $request)
{
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.ipklulusanprodi',compact('prodis'));
}
public function HitungIPK(Request $request)
{
    $prodi = $request->input('prodi');
    $universitas = $request->input('universitas');
    $ta = $request->input('ta');
    $tipekelas = $request->input('tipekelas');
     // Menyesuaikan nilai tipekelas
     if ($tipekelas == 'BARU') {
        $tipekelas = 'BARU REGULER';
    } elseif ($tipekelas == 'PINDAHAN') {
        $tipekelas = 'PINDAHAN REGULER';
    }
    session(['prodi' => $prodi]);
    session(['universitas' => $universitas]);
    session(['ta' => $ta]);
    
    // Gabungkan krs dan krsm
    $results = DB::select(
        DB::raw("
            SELECT 
                COALESCE(krs.NPM, krsm.NPM) AS NPM,
                COALESCE(mahasiswa1.Nama, mahasiswa2.Nama) AS Nama,
                COALESCE(krs.TotalNilai, 0) + COALESCE(krsm.TotalNilai, 0) AS TotalNilai,
                COALESCE(krs.TotalSKS, 0) + COALESCE(krsm.TotalSKS, 0) AS TotalSKS,
                (COALESCE(krs.TotalNilai, 0) + COALESCE(krsm.TotalNilai, 0)) / 
                (COALESCE(krs.TotalSKS, 0) + COALESCE(krsm.TotalSKS, 0)) AS IPK
            FROM
                (SELECT
                    b.NPM,
                    SUM(CASE WHEN c.NilaiAngka IS NOT NULL THEN c.NilaiAngka * a.SKS ELSE 0 END) AS TotalNilai,
                    SUM(b.SKS) AS TotalSKS
                FROM krsdetail AS b
                JOIN matakuliah AS a ON b.IdMK = a.idmk
                LEFT JOIN SettingNilai AS c ON c.NilaiHuruf = b.NilaiAkhir
                LEFT JOIN mahasiswa d ON b.npm = d.npm
                WHERE b.universitas = ?
                AND YEAR(d.tgllulusmh) = ?
                AND d.prodi = ?
                AND d.statusmahasiswa = 'lulus'
                and d.tipekelas = ?
                AND NOT EXISTS (
                    SELECT 1
                    FROM krsdetail AS d
                    JOIN matakuliah AS e ON d.IdMK = e.idmk
                    LEFT JOIN SettingNilai AS f ON f.NilaiHuruf = d.NilaiAkhir
                    WHERE d.npm = b.npm
                    AND d.idmk = b.idmk
                    AND (f.NilaiAngka > c.NilaiAngka OR (f.NilaiAngka IS NULL AND c.NilaiAngka IS NOT NULL))
                    AND (f.NilaiAngka IS NOT NULL OR c.NilaiAngka IS NULL)
                )
                GROUP BY b.NPM) AS krs
            FULL OUTER JOIN
                (SELECT 
                    b.NPM,
                    SUM(c.NilaiAngka * a.SKS) AS TotalNilai,
                    SUM(b.SKS) AS TotalSKS
                FROM krsm AS b
                JOIN matakuliah AS a ON b.IdMK = a.idmk
                JOIN settingnilai AS c ON c.nilaihuruf = b.nilaiakhir
                LEFT JOIN mahasiswa d ON d.npm = b.npm
                WHERE b.universitas = ?
                AND d.statusmahasiswa = 'lulus'
                AND b.nilaiakhir IS NOT NULL
                AND YEAR(d.tgllulusmh) = ? AND d.prodi= ?
                and tipekelas = ?
                GROUP BY b.NPM) AS krsm
            ON krs.NPM = krsm.NPM
            LEFT JOIN mahasiswa mahasiswa1 ON krs.NPM = mahasiswa1.NPM
            LEFT JOIN mahasiswa mahasiswa2 ON krsm.NPM = mahasiswa2.NPM
            ORDER BY COALESCE(krs.NPM, krsm.NPM) ASC,IPK DESC
        "), [$universitas, $ta, $prodi,$tipekelas, $universitas, $ta,$prodi,$tipekelas]
    );

    //dd($results);
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); // Inisialisasi $prodis di sini
    //dd($prodis);
    return view('akreditasi.ipklulusanprodi', compact('results', 'prodis', 'prodi', 'ta','universitas')); // Sertakan $prodis dalam compact()
}
public function showIPKPPRODI()
   {
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.ipklulusanpprodi',compact('prodis'));
   }

   public function IPKLulusanPPRODI(Request $request)
   {
       $universitas = $request->input('universitas');
       $ta_start = $request->input('ta_mulai');
       $ta_end = $request->input('ta_akhir');
       $prodi = $request->input('prodi');
       session(['universitas' => $universitas]);
       session(['ta_start' => $ta_start]);
       session(['ta_end' => $ta_end]);
   
       $results = DB::select("
       SELECT 
           TA,
           MIN(IPK) AS Minimum_IPK,
           AVG(IPK) AS Rata_rata_IPK,
           MAX(IPK) AS Maksimum_IPK
       FROM (
           SELECT 
               krs.TA AS TA,
               COALESCE(krs.NPM, krsm.NPM) AS NPM,
               COALESCE(krs.TotalNilai, 0) + COALESCE(krsm.TotalNilai, 0) AS TotalNilai,
               COALESCE(krs.TotalSKS, 0) + COALESCE(krsm.TotalSKS, 0) AS TotalSKS,
               (COALESCE(krs.TotalNilai, 0) + COALESCE(krsm.TotalNilai, 0)) / 
                   NULLIF((COALESCE(krs.TotalSKS, 0) + COALESCE(krsm.TotalSKS, 0)), 0) AS IPK
           FROM
               (SELECT
                   YEAR(d.tgllulusmh) AS TA,
                   b.NPM,
                   SUM(CASE WHEN c.NilaiAngka IS NOT NULL THEN c.NilaiAngka * a.SKS ELSE 0 END) AS TotalNilai,
                   SUM(b.SKS) AS TotalSKS
               FROM krsdetail AS b
               JOIN matakuliah AS a ON b.IdMK = a.idmk
               LEFT JOIN SettingNilai AS c ON c.NilaiHuruf = b.NilaiAkhir
               LEFT JOIN mahasiswa d ON d.npm = b.npm
               WHERE b.universitas = ? 
               AND d.prodi = ?
               AND YEAR(d.tgllulusmh) BETWEEN ? AND ?
               AND d.StatusMahasiswa = 'lulus'
               AND NOT EXISTS (
                   SELECT 1
                   FROM krsdetail AS d2
                   JOIN matakuliah AS e ON d2.IdMK = e.idmk
                   LEFT JOIN SettingNilai AS f ON f.NilaiHuruf = d2.NilaiAkhir
                   WHERE d2.npm = b.npm
                   AND d2.idmk = b.idmk
                   AND (f.NilaiAngka > c.NilaiAngka OR (f.NilaiAngka IS NULL AND c.NilaiAngka IS NOT NULL))
                   AND (f.NilaiAngka IS NOT NULL OR c.NilaiAngka IS NULL)
               )
               GROUP BY YEAR(d.tgllulusmh), b.NPM
           ) AS krs
           FULL OUTER JOIN
           (
               SELECT 
                   YEAR(d.tgllulusmh) AS TA,
                   b.NPM,
                   SUM(c.NilaiAngka * a.SKS) AS TotalNilai,
                   SUM(b.SKS) AS TotalSKS
               FROM krsm AS b
               JOIN matakuliah AS a ON b.IdMK = a.idmk
               JOIN settingnilai AS c ON c.nilaihuruf = b.nilaiakhir
               LEFT JOIN mahasiswa d ON d.npm = b.npm
               WHERE b.universitas = ?
               AND d.prodi = ?
               AND d.statusmahasiswa = 'lulus'
               AND b.nilaiakhir IS NOT NULL
               AND YEAR(d.tgllulusmh) BETWEEN ? AND ?
               GROUP BY YEAR(d.tgllulusmh), b.NPM
           ) AS krsm ON krs.NPM = krsm.NPM AND krs.TA = krsm.TA
       ) AS IPK_Results
       GROUP BY TA
   ", [
       $universitas, $prodi, $ta_start, $ta_end,
       $universitas, $prodi, $ta_start, $ta_end
   ]);
   
   $total = DB::table('krsdetail')
   ->join('matakuliah', 'krsdetail.idmk', '=', 'matakuliah.idmk')
   ->join('mahasiswa', 'mahasiswa.npm', '=', 'krsdetail.npm')
   ->select(DB::raw('count(krsdetail.npm) as jumlah, krsdetail.ta as TA'))
   ->where('matakuliah.matakuliah', 'skripsi')
   ->whereBetween('krsdetail.ta', [$ta_start, $ta_end])
   ->where('krsdetail.universitas', $universitas)
   ->where('krsdetail.prodi', $prodi)
   ->where('mahasiswa.tipekelas', 'like', 'baru%')
   ->groupBy('krsdetail.ta')
   ->get();
   
       $data = [
           'results' => $results,
           'total' => $total,
       ];
       $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get();
       return view('akreditasi.ipklulusanpprodi', compact('data', 'ta_start', 'ta_end', 'universitas','prodis','prodi'));
   }
   public function showIPKPPRODIRegular()
   {
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('akreditasi.ipklulusanregulerprodi',compact('prodis'));
   }
   public function IPKLulusanPPRODIRegular(Request $request)
   {
       $universitas = $request->input('universitas');
       $ta_start = $request->input('ta_mulai');
       $ta_end = $request->input('ta_akhir');
       $prodi = $request->input('prodi');
       session(['universitas' => $universitas]);
       session(['ta_start' => $ta_start]);
       session(['ta_end' => $ta_end]);
   
       $results = DB::select("
        SELECT 
            TA,
            MIN(IPK) AS Minimum_IPK,
            AVG(IPK) AS Rata_rata_IPK,
            MAX(IPK) AS Maksimum_IPK
        FROM (
            SELECT 
                d.stambuk AS TA,
                b.NPM,
                SUM(CASE WHEN c.NilaiAngka IS NOT NULL THEN c.NilaiAngka * a.SKS ELSE 0 END) AS TotalNilai,
                SUM(a.SKS) AS TotalSKS,
                CASE 
                    WHEN SUM(a.SKS) > 0 THEN SUM(CASE WHEN c.NilaiAngka IS NOT NULL THEN c.NilaiAngka * a.SKS ELSE 0 END) / SUM(a.SKS)
                    ELSE 0 
                END AS IPK
            FROM krsdetail AS b
            JOIN matakuliah AS a ON b.IdMK = a.idmk
            LEFT JOIN SettingNilai AS c ON c.NilaiHuruf = b.NilaiAkhir
            LEFT JOIN mahasiswa d ON d.npm = b.npm
            WHERE d.universitas = ?
                AND b.prodi = ?
                AND d.tipekelas LIKE 'baru reguler'
                and b.ta between ? and ?
                AND EXISTS (
            SELECT 1
            FROM krsdetail AS kd
            JOIN matakuliah AS e ON kd.IdMK = e.idmk
            WHERE kd.npm = b.npm
            AND e.matakuliah = 'skripsi'
            AND kd.ta BETWEEN ? AND ?
        )
                AND NOT EXISTS (
                    SELECT 1
                    FROM krsdetail AS d2
                    JOIN matakuliah AS e ON d2.IdMK = e.idmk
                    LEFT JOIN SettingNilai AS f ON f.NilaiHuruf = d2.NilaiAkhir
                    WHERE d2.npm = b.npm
                        AND d2.idmk = b.idmk
                        AND (f.NilaiAngka > c.NilaiAngka OR (f.NilaiAngka IS NULL AND c.NilaiAngka IS NOT NULL))
                        AND (f.NilaiAngka IS NOT NULL OR c.NilaiAngka IS NULL)
                )
            GROUP BY d.stambuk, b.NPM
        ) AS IPK_Results
        GROUP BY TA
   ", [
       $universitas, $prodi, $ta_start, $ta_end,$ta_start, $ta_end
   ]);
   
   $total = DB::table('krsdetail')
   ->join('matakuliah', 'krsdetail.idmk', '=', 'matakuliah.idmk')
   ->join('mahasiswa', 'mahasiswa.npm', '=', 'krsdetail.npm')
   ->select(DB::raw('count(krsdetail.npm) as jumlah, krsdetail.ta as TA'))
   ->where('matakuliah.matakuliah', 'skripsi')
   ->whereBetween('krsdetail.ta', [$ta_start, $ta_end])
   ->where('krsdetail.universitas', $universitas)
   ->where('krsdetail.prodi', $prodi)
   ->where('mahasiswa.tipekelas', 'like', 'baru%')
   ->groupBy('krsdetail.ta')
   ->get();
   
       $data = [
           'results' => $results,
           'total' => $total,
       ];
       //dd($results);
       $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get();
       return view('akreditasi.ipklulusanregulerprodi', compact('data', 'ta_start', 'ta_end', 'universitas','prodis','prodi'));
   }
}

