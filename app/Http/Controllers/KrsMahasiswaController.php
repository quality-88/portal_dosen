<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KrsMahasiswaController extends Controller
{
    public function showSummary(Request $request)
    {      
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
        return view('mahasiswa.krs.cetakSummaryKRS',compact('allIdKampus','allProdi'));   
    }

    public function SummaryKRS(Request $request)
{
    // Mengambil data dari permintaan
    $stambuk = $request->input('stambuk', '');
    $TA = $request->input('TA', '');
    $Semester = $request->input('Semester', '');
    $idKampus = $request->input('idkampus', '');
    $prodi = $request->input('prodi', '');
    $lokasi = $request->input('lokasi');
    $idFakultas = $request->input('idFakultas');
    $fakultas = $request->input('fakultas');
    $tipeKelas = $request->input('tipekelas');

    // Menyimpan data dalam sesi
    session(['prodi' => $prodi]);
    session(['TA' => $TA]);
    session(['fakultas' => $fakultas]);
    session(['idkampus' => $idKampus]);
    session(['stambuk' => $stambuk]);
    session(['lokasi' => $lokasi]);
    session(['Semester' => $Semester]);

    // Mengambil data mahasiswa dari database
    $results = DB::table('mahasiswa as a')
        ->selectRaw('ROW_NUMBER() OVER (ORDER BY a.Npm) AS Urut,
                     a.NPM AS npm,
                     a.nama AS nama,
                     a.StatusMHS AS statusmhs,
                     a.TIPEKELAS as TIPEKELAS,
                     a.idkampus AS idkampus,
                     a.kurikulum AS kurikulum,
                     (SELECT ISNULL(SUM(b.SKS), 0) 
                      FROM prodimk b 
                      WHERE b.idkampus = a.idkampus
                            AND b.prodi = a.prodi 
                            AND b.kurikulum = a.kurikulum) AS totalskskurikulum,
                     (SELECT ISNULL(SUM(c.SKS), 0) 
                      FROM krsm c 
                      WHERE c.idkampus = a.idkampus 
                            AND c.prodi = a.prodi 
                            AND c.Nilaiawal <> \'\' 
                            AND a.NPM = c.NPM ) AS totalskskonversi,
                     (SELECT ISNULL(SUM(d.TotalSKS), 0) 
                      FROM KRS d 
                      WHERE d.NPM = a.NPM 
                            AND d.idkampus = a.idkampus 
                            AND d.prodi = a.prodi 
                            AND d.SEMESTER <= ' . $Semester . ' 
                            AND d.ta <= ' . $TA . ') AS skskrs,
                     ((SELECT ISNULL(SUM(c.SKS), 0) 
                       FROM krsm c 
                       WHERE a.NPM = c.NPM 
                             AND c.Nilaiawal <> \'\' ) 
                      + 
                      (SELECT ISNULL(SUM(d.TotalSKS), 0) 
                       FROM KRS d 
                       WHERE d.SEMESTER <= ' . $Semester . ' 
                             AND d.ta <= ' . $TA . ' 
                             AND d.idkampus = a.idkampus 
                             AND d.prodi = a.prodi 
                             AND d.NPM = a.NPM)) AS TotalSKS')
        ->where('a.idkampus', $idKampus)
        ->where('a.prodi', $prodi)
        ->where('a.ta', $stambuk)
        ->orderBy('a.NPM', 'ASC');
    // Jika tipekelas tidak diisi, tampilkan data seperti biasa
    if ($tipeKelas === 'BARU') {
        $results = $results->whereRaw("LEFT(a.TIPEKELAS, 4) = 'BARU'");
    } elseif ($tipeKelas === 'PINDAHAN REGULER') {
        $results = $results->where('a.TIPEKELAS', 'PINDAHAN REGULER');
    }
    
    // Melakukan pengurutan dan mengambil hasil
    $results = $results->get();

    // Mengirimkan data ke view
    return view('mahasiswa.krs.summarykrs', compact('results','tipeKelas'));
}


    public function showRincian(Request $request)
    {      
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
        return view('mahasiswa.krs.cetakRincianKRS',compact('allIdKampus','allProdi'));   
    }


public function rincianKRS(Request $request)
    {
    $stambuk =$request->input('stambuk');
    $TA = $request->input('TA');
    $Semester = $request->input('Semester');
    $idKampus = $request->input('idkampus');
    $prodi = $request->input('prodi');
    $lokasi = $request->input('lokasi');
    $idFakultas = $request->input('idFakultas');
    $fakultas = $request->input('fakultas');
    $statusMHS = $request->input('statusMHS');


    session(['prodi' => $prodi]);
    session(['TA' => $TA]);
    session(['fakultas' => $fakultas]);
    session(['idkampus' => $idKampus]);
    session(['stambuk' => $stambuk]);
    session(['lokasi' => $lokasi]);
    session(['Semester' => $Semester]);
    
    $results = DB::table('mahasiswa as a')
    ->select(
        'a.NPM', 
        'a.nama', 
        'a.StatusMHS', 
        DB::raw("(SELECT ISNULL(SUM(o.Totalsks), 0) 
            FROM KRS o 
            WHERE o.NPM = a.NPM 
              AND o.IDKAMPUS = a.idkampus 
              AND o.prodi = a.PRODI 
              AND o.ta = '$TA' 
              AND o.Semester = '$Semester') AS SKS"), 
        'a.tipekelas as Keterangan', 
        'a.ta', 
        'a.semester', 
        'a.idkampus', 
        'a.prodi'
    )
    ->where('a.idkampus', '=', $idKampus)
    ->where('a.prodi', '=', $prodi)
    ->where('a.StatusMHS', '!=', 'lulus')
    ->where('a.ta', '=', $stambuk);

// Menambahkan kondisi berdasarkan pilihan status mahasiswa
if ($statusMHS == 'aktif') {
    $results->whereRaw("(SELECT ISNULL(SUM(o.Totalsks), 0) 
        FROM KRS o 
        WHERE o.NPM = a.NPM 
          AND o.IDKAMPUS = a.idkampus 
          AND o.prodi = a.PRODI 
          AND o.ta = '$TA' 
          AND o.Semester = '$Semester') > 0");
} elseif ($statusMHS == 'tidak aktif') {
    $results->whereRaw("(SELECT ISNULL(SUM(o.Totalsks), 0) 
        FROM KRS o 
        WHERE o.NPM = a.NPM 
          AND o.IDKAMPUS = a.idkampus 
          AND o.prodi = a.PRODI 
          AND o.ta = '$TA' 
          AND o.Semester = '$Semester') = 0");
}

$results = $results->orderBy('a.NPM')->get();

    //$sql = $results->toSql();
//dd($TA,$stambuk,$Semester,$idKampus,$prodi);
//dd($sql);
        //dd($results);

return view('mahasiswa.krs.rinciankrs', compact('results','statusMHS'));

    }

 public function showCetakKRS(Request $request)
 {
     return view('mahasiswa.krs.cetakKRS');
 }
 public function showMahasiswa(Request $request)
{
    try {
        $npm = $request->input('npm'); // Menerima 'npm' dari permintaan

        // Melakukan query ke database untuk mencari mahasiswa dengan 'npm' yang diberikan
        $mahasiswa = DB::table('mahasiswa')
        ->select(
            'mahasiswa.tipekelas',
            'mahasiswa.kurikulum',
            'mahasiswa.NPM',
            'mahasiswa.NAMA',
            'mahasiswa.PRODI',
            'mahasiswa.IDKAMPUS',
            'kampus.LOKASI',
            'mahasiswa.IDFAKULTAS',
            'fakultas.FAKULTAS',
            'mahasiswa.STAMBUK',
            'dosen.nama AS NamaDosen',
            'mahasiswa.Alamat'
        )
        ->leftJoin('FAKULTAS', 'mahasiswa.IDFAKULTAS', '=', 'FAKULTAS.IDFAKULTAS')
        ->leftJoin('KAMPUS', 'mahasiswa.IDKAMPUS', '=', 'KAMPUS.IDKAMPUS')
        ->leftJoin('DOSEN', 'DOSEN.IDDOSEN', '=', 'mahasiswa.IDDOSEN')
        ->where('mahasiswa.NPM', '=', $npm)
        ->first();
    
        if (!$mahasiswa) {
            return response()->json(['error' => 'Mahasiswa tidak ditemukan'], 404);
        }

        session(['mahasiswa' => $mahasiswa]);
        return response()->json($mahasiswa);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function cetakKRS(Request $request)
{
    $mahasiswa = session('mahasiswa');
    $nama = $mahasiswa->NAMA;
    $idKampus = $mahasiswa->IDKAMPUS;
    $lokasi = $mahasiswa->LOKASI;
    $idFakultas = $mahasiswa->IDFAKULTAS;
    $fakultas = $mahasiswa->FAKULTAS;
    $prodi = $mahasiswa->PRODI;
    $kurikulum = $mahasiswa->kurikulum;
    $tipeKelas = $mahasiswa->tipekelas;
    $namaDosen = $mahasiswa->NamaDosen;
    $alamat = $mahasiswa->Alamat;

    $npm = $request->input('npm');
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    session(['semester' => $semester]);
    session(['ta' => $ta]); 
    session(['npm' => $npm]);
    
    $results = DB::table('krsdetail')
        ->selectRaw('ROW_NUMBER() OVER (ORDER BY krsdetail.Npm) AS Urut, krsdetail.NPM, krsdetail.Idkampus,
            krsdetail.Prodi, krsdetail.TA, matakuliah.IDMK, matakuliah.Matakuliah, matakuliah.SKS')
        ->join('matakuliah', 'matakuliah.idmk', '=', 'krsdetail.idmk')
        ->where('krsdetail.npm', $npm)
        ->where('krsdetail.TA', $ta)
        ->where('krsdetail.Semester', $semester)
        ->orderBy('krsdetail.npm', 'ASC')
        ->get();
        $totalSKS = $results->sum('SKS');

        $wali = DB::table('mahasiswa')
                ->select ('mahasiswa.iddosen', 'dosen.nama as nama')
                ->join('dosen', 'dosen.iddosen', '=', 'mahasiswa.iddosen')
                ->where('mahasiswa.npm', $npm)
                ->get();
         $kaprodi = DB::table('ProdiFakultas')
        ->select ('ProdiFakultas.iddosen as iddosen', 'dosen.nama as nama')
        ->join('dosen', 'dosen.iddosen', '=', 'ProdiFakultas.iddosen')
        ->where('ProdiFakultas.prodi', $prodi)
        ->get();
    // Menyiapkan data untuk digunakan dalam JavaScript
    $data = [
        'TA' => session('ta'),
        'semester' => session('semester'),
        'prodi' => $prodi,
        'idKampus' => $idKampus,
        'lokasi' => $lokasi,
        'nama' => $nama,
        'npm' => session('npm'),
        'idFakultas' => $idFakultas,
        'fakultas' => $fakultas,
        'kurikulum' => $kurikulum,
        'tipeKelas' => $tipeKelas,
        'namaDosen' => $namaDosen,
        'alamat' => $alamat,
        'results' => $results,
        'totalSKS' => $totalSKS,
        'wali'=>$wali,
        'kaprodi'=>$kaprodi,
    ];
//dd($wali,$kaprodi);
    return view('mahasiswa.krs.viewKRSMahasiswa', compact('data','results','wali','kaprodi'));
}
public function showKonversi(Request $request)
{
    return view('mahasiswa.konversi.cetaktranskripnilai');
}
public function showKonversiNilai (Request $request)
{
    try {
        $npm = $request->input('npm');

        $mahasiswa = DB::table('mahasiswa')
        ->select(
            'mahasiswa.tipekelas as tipekelas',
            'mahasiswa.kurikulum as kurikulum',
            'mahasiswa.NPM as NPM',
            'mahasiswa.NAMA as NAMA',
            'mahasiswa.PRODI as PRODI',
            'mahasiswa.IDKAMPUS as IDKAMPUS',
            'kampus.LOKASI as LOKASI',
            'mahasiswa.IDFAKULTAS as IDFAKULTAS',
            'fakultas.FAKULTAS as FAKULTAS',
            'mahasiswa.STAMBUK as STAMBUK',
            'dosen.nama as NamaDosen',
            'mahasiswa.Alamat as Alamat'
        )
        ->leftJoin('FAKULTAS', 'mahasiswa.IDFAKULTAS', '=', 'FAKULTAS.IDFAKULTAS')
        ->leftJoin('KAMPUS', 'mahasiswa.IDKAMPUS', '=', 'KAMPUS.IDKAMPUS')
        ->leftJoin('DOSEN', 'DOSEN.IDDOSEN', '=', 'mahasiswa.IDDOSEN')
        ->where('mahasiswa.NPM', $npm)
        ->first();
        $kurikulum = DB::table('mahasiswa')
        ->select('kurikulum')
        ->where('npm',$npm);
        if (!$mahasiswa) {
            return response()->json(['error' => 'Mahasiswa tidak ditemukan'], 404);
        }
        session(['mahasiswa' => $mahasiswa]);
        return response()->json($mahasiswa);
        session(['kurikulum' => $kurikulum]);
        return response()->json($kurikulum);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function cetakTranskripNilai(Request $request)
{
    $mahasiswa = session('mahasiswa');
    $nama = $mahasiswa->NAMA;
    $idKampus = $mahasiswa->IDKAMPUS;
    $lokasi = $mahasiswa->LOKASI;
    $idFakultas = $mahasiswa->IDFAKULTAS;
    $fakultas = $mahasiswa->FAKULTAS;
    $prodi = $mahasiswa->PRODI;
    $kurikulum = $mahasiswa->kurikulum;
    $tipeKelas = $mahasiswa->tipekelas;
    $namaDosen = $mahasiswa->NamaDosen;
    $alamat = $mahasiswa->Alamat;

    $npm = $request->input('npm');
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    session(['semester' => $semester]);
    session(['ta' => $ta]); 
    session(['npm' => $npm]);
    $urut=1;
    $result1 = DB::select("
    WITH CTE AS (
        SELECT 
            b.NPM,
            b.IDKAMPUS,
            b.PRODI,
            b.kurikulum,
            b.TA,
            b.SEMESTER,
           MAX(b.idmk) AS idmk,
            MAX(a.MATAKULIAH) AS MATAKULIAH,
            b.SKS,
            a.SEMESTER AS MatakuliahSemester,
            b.NilaiAkhir,
            CASE 
                WHEN c.NilaiAngka IS NOT NULL THEN c.NilaiAngka * b.SKS 
                ELSE 0 
            END AS kali,
            CONCAT(b.TA, b.SEMESTER) AS TASemester,
            MAX(b.JumlahBobotNilai) AS JumlahBobotNilai, -- Menambahkan JumlahBobotNilai ke dalam fungsi agregat MAX()
            ROW_NUMBER() OVER (PARTITION BY b.idmk ORDER BY c.NilaiAngka DESC) AS rn
        FROM 
            krsdetail AS b
        JOIN 
            matakuliah AS a ON b.IdMK = a.idmk
        LEFT JOIN 
            SettingNilai AS c ON c.NilaiHuruf = b.NilaiAkhir
        WHERE 
            b.npm = '$npm'
        GROUP BY 
            b.NPM,
            b.IDKAMPUS,
            b.PRODI,
            b.kurikulum,
            b.TA,
            b.SEMESTER,
            b.idmk,
            b.SKS,
            a.SEMESTER,
            b.NilaiAkhir,
            c.NilaiAngka,
            CONCAT(b.TA, b.SEMESTER) -- Diperlukan karena digunakan dalam SELECT
    )
    SELECT 
        NPM,
        IDKAMPUS,
        PRODI,
        kurikulum,
        TA,
        SEMESTER,
        idmk,
        MATAKULIAH,
        SKS,
        MatakuliahSemester,
        NilaiAkhir,
        kali,
        TASemester,
        JumlahBobotNilai
    FROM 
        CTE
    WHERE 
        rn = 1
");

//dd($result1);
   //$result1 = DB::table('krsdetail as b')
   //->selectRaw('DISTINCT ROW_NUMBER() OVER (ORDER BY b.Npm) AS Urut')
   //->select('b.NPM', 'b.IDKAMPUS', 'b.PRODI', 'b.kurikulum', 'b.TA', 'b.SEMESTER', 'b.idmk', 'a.MATAKULIAH', 'b.SKS',
   //    'a.SEMESTER', 'b.NilaiAkhir', DB::raw('c.NilaiAngka * a.SKS AS kali'), DB::raw("CONCAT(b.TA, b.SEMESTER)"),
   //    'b.JumlahBobotNilai')
   //->join('matakuliah as a', 'b.IdMK', '=', 'a.idmk')
   //->join('SettingNilai as c', 'c.NilaiHuruf', '=', 'b.NilaiAkhir')
   //->where('b.npm',$npm)
   //->orderBy('b.semester', 'asc')
   //->get();
    // Query kedua
    $result2 = DB::table('krsm as b')
    ->select('b.npm', 'b.IDKAMPUS', 'b.PRODI', 'b.kurikulum', 'b.TA', 'b.SEMESTER', 'b.idmk', 'a.MATAKULIAH', 'b.SKS',
        'a.SEMESTER', 'b.NilaiAkhir', DB::raw('c.NilaiAngka * a.SKS AS kali'), DB::raw("CONCAT(b.TA, '1') as TASemester"),
        'b.JumlahBobotNilai')
    ->selectRaw('ROW_NUMBER() OVER (PARTITION BY b.idmk ORDER BY c.NilaiAngka DESC) AS rn') // Menambahkan window function
    ->join('matakuliah as a', 'b.IdMK', '=', 'a.idmk')
    ->join('settingnilai as c', 'c.nilaihuruf', '=', 'b.nilaiakhir')
    ->where('b.npm', $npm)
    ->whereNotNull('b.nilaiakhir')
    ->orderBy('b.semester', 'asc')
    ->get();

// Filter untuk hanya mempertahankan baris pertama (nilai tertinggi) dari setiap idmk
$result2 = collect($result2)->filter(function ($item) {
    return $item->rn == 1;
})->values()->all();

        foreach ($result2 as $result) {
            $result->Urut = $urut; // Menambahkan nomor urut pada setiap baris
            $urut++; // Menaikkan nomor urut
        }

        
    $wali = DB::table('mahasiswa')
        ->select('mahasiswa.iddosen', 'dosen.nama as nama')
        ->join('dosen', 'dosen.iddosen', '=', 'mahasiswa.iddosen')
        ->where('mahasiswa.npm', $npm)
        ->get();

    $kaprodi = DB::table('ProdiFakultas')
        ->select('ProdiFakultas.iddosen as iddosen', 'dosen.nama as nama')
        ->join('dosen', 'dosen.iddosen', '=', 'ProdiFakultas.iddosen')
        ->where('ProdiFakultas.prodi', $prodi)
        ->get();

    // Mendapatkan mata kuliah yang tidak ada pada result1 dan result2 dari tabel prodimk
    $missingCourses = DB::table('prodimk')
    ->select('prodimk.sks', 'matakuliah.matakuliah', 'prodimk.semester', 'prodimk.idmk')
    ->join('matakuliah', 'prodimk.idmk', '=', 'matakuliah.idmk')
    ->where('prodimk.kurikulum', $kurikulum)
    ->where('prodimk.IDKAMPUS', $idKampus)
    ->where('prodimk.prodi',$prodi)
    ->distinct()
    ->orderBy('prodimk.semester', 'asc')
    ->get();
   
// Convert $result1 to collection
$result1 = collect($result1);

// Convert $result2 to collection
$result2 = collect($result2);
//dd($result2);
    // Menyimpan mata kuliah yang tidak ada pada result1 dan result2
    $missingCourseData = [];
    foreach ($missingCourses as $course) {
        // Periksa apakah mata kuliah tidak ada di result1 atau result2
        $existsInResult1 = $result1->where('idmk', $course->idmk)->count() > 0;
        $existsInResult2 = $result2->where('idmk', $course->idmk)->count() > 0;
        if (!$existsInResult1 && !$existsInResult2) {
            $missingCourseData[] = [
                'sks' => $course->sks,
                'matakuliah' => $course->matakuliah,
                'semester' => $course->semester,
                'idmk' => $course->idmk,
                'nilaiAkhir' => '', // Set nilai akhir menjadi kosong
                'kali' => '', // Set kali menjadi kosong
               
            ];
        }
    }
    $totalSKS = 0;
    foreach ($result1 as $result) {
        if ($result->NilaiAkhir !== null) {
            $totalSKS += $result->SKS;
        }
    }

    // Hitung total SKS yang memiliki nilai dari result2
    foreach ($result2 as $result) {
        if ($result->NilaiAkhir !== null) {
            $totalSKS += $result->SKS;
        }
    }
    $totalNilaiResult1 = 0;
    foreach ($result1 as $result) {
        if ($result->NilaiAkhir !== null) {
            $totalNilaiResult1 += $result->kali;
        }
    }

    $totalNilaiResult2 = 0;
    foreach ($result2 as $result) {
        if ($result->NilaiAkhir !== null) {
            $totalNilaiResult2 += $result->kali;
        }
    }
    $totalNilai = $totalNilaiResult1 + $totalNilaiResult2;
    $IPK = $totalSKS != 0 ? $totalNilai / $totalSKS : 0;
    //dd($ipk);
// Menggabungkan data mata kuliah yang tidak ada pada result1 dan result2 ke dalam data
$data = [
    'TA' => session('ta'),
    'semester' => session('semester'),
    'prodi' => $prodi,
    'idKampus' => $idKampus,
    'lokasi' => $lokasi,
    'nama' => $nama,
    'npm' => session('npm'),
    'idFakultas' => $idFakultas,
    'fakultas' => $fakultas,
    'kurikulum' => $kurikulum,
    'tipeKelas' => $tipeKelas,
    'namaDosen' => $namaDosen,
    'alamat' => $alamat,
    'result1' => $result1,
    'result2' => $result2,
    'wali' => $wali,
    'kaprodi' => $kaprodi,
    'missingCourses' => $missingCourseData,
    'totalSKS' => $totalSKS,
    'totalNilai' => $totalNilai,
    'IPK' => $IPK,
];

//dd($wali,$kaprodi);
return view('mahasiswa.konversi.viewtranskripnilai', compact('data','result1','result2','missingCourses','wali','kaprodi'));

}

public function showTambahKonversi(Request $request)
{
    return view('mahasiswa.konversi.tambahkonversinilai');
}
public function showInputKonversiNilai (Request $request)
{
    try {
        $npm = $request->input('npm');
        $mahasiswa = DB::table('mahasiswa')
            ->select(
                DB::raw("COALESCE(mahasiswa.tipekelas, '') AS tipekelas"),
                DB::raw("COALESCE(mahasiswa.kurikulum, '') AS kurikulum"),
                DB::raw("COALESCE(mahasiswa.NPM, '') AS NPM"),
                DB::raw("COALESCE(mahasiswa.NAMA, '') AS NAMA"),
                DB::raw("COALESCE(mahasiswa.PRODI, '') AS PRODI"),
                DB::raw("COALESCE(mahasiswa.IDKAMPUS, '') AS IDKAMPUS"),
                DB::raw("COALESCE(kampus.LOKASI, '') AS LOKASI"),
                DB::raw("COALESCE(mahasiswa.IDFAKULTAS, '') AS IDFAKULTAS"),
                DB::raw("COALESCE(fakultas.FAKULTAS, '') AS FAKULTAS"),
                DB::raw("COALESCE(mahasiswa.npmasal, '') AS npmasal"),
                DB::raw("COALESCE(mahasiswa.kdptasal, '') AS KDPTASAL"),
                DB::raw("COALESCE(mahasiswa.kdprodiasal, '') AS IDPRODIASAL"),
                DB::raw("COALESCE(mahasiswa.JenjangAkhir, '') AS JenjangAkhir"),
                DB::raw("COALESCE(mahasiswa.ta, '') AS ta"),
                DB::raw("COALESCE(AsalProdiFeeder.nama, '') AS PRODIASAL")
            )
            ->leftJoin('AsalProdiFeeder', 'mahasiswa.kdprodiasal', '=', 'AsalProdiFeeder.IDASALPRODI')
            ->leftJoin('FAKULTAS', 'mahasiswa.IDFAKULTAS', '=', 'FAKULTAS.IDFAKULTAS')
            ->leftJoin('KAMPUS', 'mahasiswa.IDKAMPUS', '=', 'KAMPUS.IDKAMPUS')
            ->where('NPM', '=', $npm)
            ->first();

        if (!$mahasiswa) {
            return response()->json(['error' => 'Mahasiswa tidak ditemukan'], 404);
        }
        session(['mahasiswa' => $mahasiswa]);
        return response()->json($mahasiswa);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function viewTambahKonversi(Request $request)
{
    $userid = session('userid');
    $mahasiswa = session('mahasiswa');
    $nama = $mahasiswa->NAMA;
    $idKampus = $mahasiswa->IDKAMPUS;
    $lokasi = $mahasiswa->LOKASI;
    $idFakultas = $mahasiswa->IDFAKULTAS;
    $fakultas = $mahasiswa->FAKULTAS;
    $prodi = $mahasiswa->PRODI;
    $kurikulum = $mahasiswa->kurikulum;
    $npm = $mahasiswa->NPM;
    session(['npm' => $npm]);
    //dd($npm);
    //dd($mahasiswa);
    $hasilKonversi = DB::table('KRSMPRIMARY')
    ->select(
        'krsmPrimary.NPM AS npm',
        DB::raw('ROW_NUMBER() OVER (ORDER BY krsmPrimary.IDMKASAL) AS NoUrut'),
        'krsmPrimary.idPrimary AS idPrimary',
        'krsmPrimary.HasilPengakuan AS HasilPengakuan',
        'krsmPrimary.IDMKASAL AS IDMK',
        'krsmPrimary.MATAKULIAHASAL AS MATAKULIAH',
        'krsmPrimary.SKSASAL AS SKS',
        'krsmPrimary.IDMK AS IDMKTUJUAN',
        'krsmPrimary.MATAKULIAH AS MATAKULIAHTUJUAN',
        'krsmPrimary.SKS AS SKSTUJUAN',
        'krsmPrimary.NILAIAKHIR AS NILAIAKHIR',
        DB::raw('ISNULL(SettingNilai.NilaiAngka, 0) * krsmPrimary.SKS AS BobotNilai'),
        DB::raw("CASE 
                    WHEN krsmPrimary.statuskonversi = 'T' THEN 'Tidak Ada Pasangan' 
                    WHEN krsmPrimary.statuskonversi = 'F' THEN 'Ada Pasangan' 
                    WHEN krsmPrimary.statuskonversi = '' THEN 'Tidak Ada Status' 
                    ELSE 'Tidak Ada Status' 
                END AS HasilKonversi")
    )
    ->join('SettingNilai', 'krsmPrimary.NILAIAKHIR', '=', 'SettingNilai.NilaiHuruf')
    ->where('krsmPrimary.NPM', $npm)
    ->get();
        //dd($userid);
    //dd($hasilKonversi);
    return view('mahasiswa.konversi.viewtambahkonversi', compact('hasilKonversi','npm'));
}
public function deleteKonversiNilai(Request $request)
{
    $idPrimary = $request->input('idPrimary');
    $npm = $request->input('npm');
    $idMkAsal = $request->input('idMkAsal');
    $idMk = $request->input('idMk');

    // Check if entry exists in krsm table
    $krsmExists = DB::table('krsm')
        ->where('NPM', $npm)
        ->where('idmk', $idMk)
        ->where('IDMKASAL', $idMkAsal)
        ->exists();

    // Check if entry exists in krsmPrimary table
    $krsmPrimaryExists = DB::table('krsmPrimary')
        ->where('idPrimary', $idPrimary)
        ->exists();

    if ($krsmExists && $krsmPrimaryExists) {
        // Delete from krsm table
        DB::table('krsm')
            ->where('NPM', $npm)
            ->where('idmk', $idMk)
            ->where('IDMKASAL', $idMkAsal)
            ->delete();

        // Delete from krsmPrimary table
        DB::table('krsmPrimary')
            ->where('idPrimary', $idPrimary)
            ->delete();

        return response()->json(['message' => 'Data berhasil dihapus dari kedua tabel.']);
    } elseif ($krsmExists) {
        // Delete from krsm table only
        DB::table('krsm')
        ->where('idPrimary', $idPrimary)
            ->delete();

        return response()->json(['message' => 'Data berhasil dihapus dari tabel krsm.']);
    } elseif ($krsmPrimaryExists) {
        // Delete from krsmPrimary table only
        DB::table('krsmPrimary')
            ->where('idPrimary', $idPrimary)
            ->delete();

        return response()->json(['message' => 'Data berhasil dihapus dari tabel krsmPrimary.']);
    } else {
        return response()->json(['message' => 'Data tidak ditemukan di kedua tabel.']);
    }
}
public function searchMatkul (Request $request)
{
    $mahasiswa = session('mahasiswa');
    $prodi = $mahasiswa->PRODI;
    $searchTerm = $request->input('term');
    // Modifikasi query untuk mengambil data matakuliah sesuai prodi dan kurikulum yang dipilih
    $results = DB::table('matakuliah')
        ->select('IDMK', 'MATAKULIAH', 'SKS', 'semester')
        ->whereIn('IDMK', function($query) use ($prodi) {
            $query->select('idmk')
                  ->from('prodimk')
                  ->where('prodi', $prodi);
        })
        ->where('MATAKULIAH', 'like', '%' . $searchTerm . '%')
        ->get();
    // Tambahkan pernyataan log sebelum mengembalikan respons
    \Log::info('searchidmk request with term: ' . $searchTerm);
    return response()->json($results);
}

public function simpanKonversi(Request $request)
{   $userid = session('userid');
    // Mengambil data dari session
    $mahasiswa = session('mahasiswa');
    $nama = $mahasiswa->NAMA;
    $idKampus = $mahasiswa->IDKAMPUS;
    $lokasi = $mahasiswa->LOKASI;
    $idFakultas = $mahasiswa->IDFAKULTAS;
    $fakultas = $mahasiswa->FAKULTAS;
    $prodi = $mahasiswa->PRODI;
    $kurikulum = $mahasiswa->kurikulum;
    $ta =  $mahasiswa->ta;
    $idkampusasal =$mahasiswa->KDPTASAL;
    $idprodiasal =$mahasiswa->IDPRODIASAL;
    $npm = session('npm');

    // Mengambil data dari request
    $idmkasal = $request->input('idmkasal');
    $sksasal = $request->input('sksasal');
    $hasil = $request->input('hasil');
    $matkulasal = $request->input('matkulasal');
    $matakuliah = $request->input('matakuliah');
    $semester = $request->input('semester');
    $idmk = $request->input('idmk');
    $sks = $request->input('sks');
    $nilai = $request->input('nilai');
    $status = $request->input('status');
    $nilaiawal = "";

    $itemno = 1;

    if (in_array($idKampus, ['02', '04'])) {
        $universitas = 'UQM';
    } elseif (in_array($idKampus, ['11', '12', '13', '14', '15', '16'])) {
        $universitas = 'UQB';
    }

    $bobot = 0;
    if ($nilai === 'A') {
        $bobot = $sks * 4.00;
    } else if ($nilai === 'A-') {
        $bobot = $sks * 3.75;
    } else if ($nilai === 'B+') {
        $bobot = $sks * 3.50;
    } else if ($nilai === 'B') {
        $bobot = $sks * 3.00;
    } else if ($nilai === 'B-') {
        $bobot = $sks * 2.75;
    } else if ($nilai === 'C+') {
        $bobot = $sks * 2.50;
    } else if ($nilai === 'C') {
        $bobot = $sks * 2.00;
    } else if ($nilai === 'C-') {
        $bobot = $sks * 1.75;
    }else if ($nilai === 'D') {
        $bobot = $sks * 1.00;
    }
    $ket = 'CRUD';

        // Memeriksa keberadaan idmkasal dan idmk berdasarkan npm
        $existingKrsm = DB::table('krsm')
        ->where('NPM', $npm)
        ->where(function ($query) use ($idmkasal, $idmk) {
            $query->where('IDMKASAL', $idmkasal)
                  ->orWhere('IdMK', $idmk);
        })
        ->exists();

$existingKrsmPrimary = DB::table('krsmPrimary')
        ->where('NPM', $npm)
        ->where(function ($query) use ($idmkasal, $idmk) {
            $query->where('IDMKASAL', $idmkasal)
                  ->orWhere('IdMK', $idmk);
        })
        ->exists();

// Jika idmkasal atau idmk sudah digunakan sebelumnya, kirimkan respons error
if ($existingKrsm || $existingKrsmPrimary) {
return response()->json(['message' => 'IDMK Asal atau IDMK sudah pernah digunakan sebelumnya.'], 400);
}
    if ($status == 'F') {
        DB::table('krsm')->insert([
            'Kurikulum' => $kurikulum,
            'Prodi' => $prodi,
            'IDKampus' => $idKampus,
            'TA' => $ta,
            'TglKRS' => now(),
            'NPM' => $npm,
            'ItemNo' => $itemno,
            'IdMK' => $idmk,
            'SKS' => $sks,
            'Semester' => '1', // Atur sesuai dengan nilai default atau kebutuhan Anda
            'NilaiAkhir' => $nilai,
            'NILAIAWAL' => $nilai,
            'UserId' => $userid,
            'IDMKASAL' => $idmkasal,
            'SKSASAL' => $sksasal,
            'IDKAMPUSASAL' => $idkampusasal,
            'PRODIASAL' => $idprodiasal,
            'HasilPengakuan' => $hasil,
            'statuskonversi' => $status,
            'Keterangan' => $ket,
            'DETAILNILAIAKHIR' => $bobot,
            'Universitas' => $universitas
        ]);

        // Lakukan proses penyimpanan ke tabel krsmprimary
        DB::table('krsmPrimary')->insert([
            'Kurikulum' => $kurikulum,
            'Prodi' => $prodi,
            'IDKampus' => $idKampus,
            'TA' => $ta,
            'TglKRS' => now(),
            'NPM' => $npm,
            'ItemNo' => $itemno,
            'IdMK' => $idmk,
            'Matakuliah' => $matakuliah,
            'SKS' => $sks,
            'Semester' => '1', // Sesuaikan dengan kebutuhan Anda
            'NilaiAkhir' => $nilai,
            'NILAIAWAL' => $nilai,
            'UserId' => $userid,
            'IDMKASAL' => $idmkasal,
            'MATAKULIAHASAL' => $matkulasal,
            'SKSASAL' => $sksasal,
            'IDKAMPUSASAL' => $idkampusasal,
            'PRODIASAL' => $idprodiasal,
            'HasilPengakuan' => $hasil,
            'statuskonversi' => $status,
            'keterangan' => $ket,
            'DETAILNILAIAKHIR' => $bobot,
            'IdmKPrimary'=>$idmkasal,
            'Universitas' => $universitas
        ]);
    }
    // Jika status = T, simpan ke dalam krsmprimary saja
    elseif ($status == 'T') {
        // Lakukan proses penyimpanan ke tabel krsmprimary
        DB::table('krsmPrimary')->insert([
            'Kurikulum' => $kurikulum,
            'Prodi' => $prodi,
            'IDKampus' => $idKampus,
            'TA' => $ta,
            'TglKRS' => now(),
            'NPM' => $npm,
            'ItemNo' => $itemno,
            'IdMK' => $idmk,
            'Matakuliah' => $matakuliah,
            'SKS' => $sks,
            'Semester' => '1', // Sesuaikan dengan kebutuhan Anda
            'NilaiAkhir' => $nilaiawal,
            'NILAIAWAL' => $nilaiawal,
            'UserId' => $userid,
            'IDMKASAL' => $idmkasal,
            'MATAKULIAHASAL' => $matkulasal,
            'SKSASAL' => $sksasal,
            'IDKAMPUSASAL' => $idkampusasal,
            'PRODIASAL' => $idprodiasal,
            'HasilPengakuan' => $hasil,
            'statuskonversi' => $status,
            'keterangan' => $ket,
            'DETAILNILAIAKHIR' => $bobot,
            'Universitas' => $universitas
        ]);
    }
    return response()->json(['message' => 'data berhasil disimpan'], 200);
}
public function editKonversi(Request $request)
{
    $idPrimary = $request->input('idPrimary');
    $konversi = DB::table('krsmprimary')->where('idPrimary', $idPrimary)->first();
    if (!$konversi) {
        // Jika tidak ditemukan, mungkin menampilkan pesan error atau mengembalikan ke halaman sebelumnya
        return redirect()->back()->with('error', 'Data pendidikan tidak ditemukan.');
    }
    // Mengirim data pendidikan ke halaman ubahpendidikan.blade.php
    return view('mahasiswa.konversi.edittambahkonversi', compact('konversi'));
}
public function sendEdit(Request $request)
{

   $idPrimary = $request->input('idPrimary');
   $npm = $request->input('npm');
   $idmkasal = $request->input('idmkasal');
   $sksasal = $request->input('sksasal');
   $hasil = $request->input('hasil');
   $matkulasal = $request->input('matkulasal');
   $matakuliah = $request->input('matakuliah');
   $semester = $request->input('semester');
   $idmk = $request->input('idmk');
   $sks = $request->input('sks');
   $nilai = $request->input('nilai');
   $status = $request->input('status');

$bobot = 0;
if ($nilai === 'A') {
    $bobot = $sks * 4.00;
} else if ($nilai === 'A-') {
    $bobot = $sks * 3.75;
} else if ($nilai === 'B+') {
    $bobot = $sks * 3.50;
} else if ($nilai === 'B') {
    $bobot = $sks * 3.00;
} else if ($nilai === 'B-') {
    $bobot = $sks * 2.75;
} else if ($nilai === 'C+') {
    $bobot = $sks * 2.50;
} else if ($nilai === 'C') {
    $bobot = $sks * 2.00;
} else if ($nilai === 'C-') {
    $bobot = $sks * 1.75;
}else if ($nilai === 'D') {
    $bobot = $sks * 1.00;
}

 DB::table('krsmprimary')
   ->where('idPrimary', $idPrimary)
   ->update([
    'IdMK' => $idmk,
    'Matakuliah' => $matakuliah,
    'SKS' => $sks,
    //'Semester' => '1', // Sesuaikan dengan kebutuhan Anda
    'NilaiAkhir' => $nilai,
    'NILAIAWAL' => $nilai,
    'IDMKASAL' => $idmkasal,
    'MATAKULIAHASAL' => $matkulasal,
    'SKSASAL' => $sksasal,
    'HasilPengakuan' => $hasil,
    'statuskonversi' => $status,
    'DETAILNILAIAKHIR' => $bobot
   ]);
   //dd($npm);
   return response()->json(['success' => 'Data berhasil diperbarui.', 'npm' => $npm]);

}
public function changeView(Request $request)
{
    $npm = $request->input('npm');
    session(['npm' => $npm]);
    //dd($mahasiswa);
    $hasilKonversi = DB::table('KRSMPRIMARY')
    ->select(
        'krsmPrimary.NPM AS npm',
        DB::raw('ROW_NUMBER() OVER (ORDER BY krsmPrimary.IDMKASAL) AS NoUrut'),
        'krsmPrimary.idPrimary AS idPrimary',
        'krsmPrimary.HasilPengakuan AS HasilPengakuan',
        'krsmPrimary.IDMKASAL AS IDMK',
        'krsmPrimary.MATAKULIAHASAL AS MATAKULIAH',
        'krsmPrimary.SKSASAL AS SKS',
        'krsmPrimary.IDMK AS IDMKTUJUAN',
        'krsmPrimary.MATAKULIAH AS MATAKULIAHTUJUAN',
        'krsmPrimary.SKS AS SKSTUJUAN',
        'krsmPrimary.NILAIAKHIR AS NILAIAKHIR',
        DB::raw('ISNULL(SettingNilai.NilaiAngka, 0) * krsmPrimary.SKS AS BobotNilai'),
        DB::raw("CASE 
                    WHEN krsmPrimary.statuskonversi = 'T' THEN 'Tidak Ada Pasangan' 
                    WHEN krsmPrimary.statuskonversi = 'F' THEN 'Ada Pasangan' 
                    WHEN krsmPrimary.statuskonversi = '' THEN 'Tidak Ada Status' 
                    ELSE 'Tidak Ada Status' 
                END AS HasilKonversi")
    )
    ->join('SettingNilai', 'krsmPrimary.NILAIAKHIR', '=', 'SettingNilai.NilaiHuruf')
    ->where('krsmPrimary.NPM', $npm)
    ->get();
        //dd($userid);
    //dd($npm);
    return view('mahasiswa.konversi.viewtambahkonversi', compact('hasilKonversi'));
}
public function showLDIKTI()
{
    return view('mahasiswa.konversi.cetakldikti');
}
public function cetakLDIKTI (Request $request)
{
    $universitas = $request->input('universitas');
    $ta = $request->input('ta');

    $results = DB::table('mahasiswa as a')
    ->select(
        DB::raw('ROW_NUMBER() OVER (ORDER BY a.npm) AS NoUrut'),
        'a.NPM AS NPM',
        'a.Nama AS Nama',
        'a.NPMASAL AS NPMASAL',
        'a.kurikulum AS kurikulum',
        'a.TA AS TA',
        'a.IDKAMPUS AS IDKAMPUS',
        'b.LOKASI AS LOKASI',
        'a.idFakultas AS idfakultas',
        'c.Fakultas AS fakultas',
        'a.PRODI AS PRODI',
        'a.kdptasal AS KDPTASAL',
        'd.nama_perguruan_tinggi AS UNIVERSITAS',
        'a.kdprodiasal AS IDPRODIASAL',
        'e.nama AS PRODIASAL'
    )
    ->leftJoin('kampus as b', 'b.idkampus', '=', 'a.idkampus')
    ->leftJoin('fakultas as c', 'c.IDFAKULTAS', '=', 'a.IDFAKULTAS')
    ->leftJoin('AsalPTIFeeder as d', 'd.kode_perguruan_tinggi', '=', 'a.KDPTASAL')
    ->leftJoin('AsalProdiFeeder as e', function ($join) {
        $join->on('e.IDASALPRODI', '=', 'a.KDPRODIASAL')
             ->on('e.IDASALPTI', '=', 'a.KDPTASAL');
    })
    ->whereIn('a.npm', function($query) use($ta) {
        $query->select('npm')
            ->from('krsm')
            ->where('ta', $ta);
    })
    ->where('a.universitas', $universitas)

    ->orderBy('a.npm')
    ->paginate(10); // 10 items per page

    //dd($results);
    session(['results' => $results]);
    return view('mahasiswa.konversi.cetakldikti', compact('results','universitas', 'ta'));
return response()->json($results);
}

// Tambahkan fungsi pencarian di controller
public function searchLDIKTI(Request $request)
{
    $universitas = $request->input('universitas');
    $ta = $request->input('ta');
    $search = $request->input('search');

    // Lakukan query pencarian berdasarkan $search
    $results = DB::table('mahasiswa as a')
    ->select(
        DB::raw('ROW_NUMBER() OVER (ORDER BY a.npm) AS NoUrut'),
        'a.NPM AS NPM',
        'a.Nama AS Nama',
        'a.NPMASAL AS NPMASAL',
        'a.kurikulum AS kurikulum',
        'a.TA AS TA',
        'a.IDKAMPUS AS IDKAMPUS',
        'b.LOKASI AS LOKASI',
        'a.idFakultas AS idfakultas',
        'c.Fakultas AS fakultas',
        'a.PRODI AS PRODI',
        'a.kdptasal AS KDPTASAL',
        'd.nama_perguruan_tinggi AS UNIVERSITAS',
        'a.kdprodiasal AS IDPRODIASAL',
        'e.nama AS PRODIASAL'
    )
    ->leftJoin('kampus as b', 'b.idkampus', '=', 'a.idkampus')
    ->leftJoin('fakultas as c', 'c.IDFAKULTAS', '=', 'a.IDFAKULTAS')
    ->leftJoin('AsalPTIFeeder as d', 'd.kode_perguruan_tinggi', '=', 'a.KDPTASAL')
    ->leftJoin('AsalProdiFeeder as e', function ($join) {
        $join->on('e.IDASALPRODI', '=', 'a.KDPRODIASAL')
             ->on('e.IDASALPTI', '=', 'a.KDPTASAL');
    })
    ->whereIn('a.npm', function($query) use($ta) {
        $query->select('npm')
            ->from('krsm')
            ->where('ta', $ta);
    })
        ->where('a.universitas', $universitas)
        ->where('a.Nama', 'LIKE', "%$search%") // Sesuaikan dengan kolom pencarian
        ->orderBy('a.npm')
        ->get(); // Dapatkan semua hasil pencarian tanpa paginate

    return response()->json($results);
}


public function detailLDIKTI(Request $request)
{
    $npmasal = $request->input('npmasal');
    $nama = $request->input('nama');
    $idKampus = $request->input('idKampus');
    $prodi = $request->input('prodi');
    $kurikulum = $request->input('kurikulum');
    $npm = $request->input('npm');
    $lokasi = $request->input('lokasi');
    $ta = $request->input('ta');
    $fakultas = $request->input('fakultas');
    $prodiasal = $request->input('prodiasal');
    $universitas = $request->input('universitas');
    //dd($kurikulum, $idKampus, $prodi, $universitas);
    $result1 = DB::table('krsmPrimary')
        ->leftJoin('mahasiswa', 'krsmPrimary.NPM', '=', 'mahasiswa.NPM')
        ->join('SettingNilai', 'krsmPrimary.NILAIAKHIR', '=', 'SettingNilai.NilaiHuruf')
        ->select(
            'krsmPrimary.NPM',
            'krsmPrimary.IDKAMPUS',
            'krsmPrimary.PRODI',
            'krsmPrimary.KURIKULUM',
            DB::raw("ISNULL(krsmPrimary.IDMK, '') AS IDMK"),
            DB::raw("ISNULL(krsmPrimary.IDMKASAL, '') AS IDMKASAL"),
            DB::raw("REPLACE(krsmPrimary.MATAKULIAHASAL, '&amp;', '&') AS MATAKULIAHASAL"),
            'krsmPrimary.SKSASAL AS SKSAsal',
            DB::raw("REPLACE(ISNULL(krsmPrimary.MATAKULIAH, ''), '&amp;', '&') AS Matakuliah"),
            'krsmPrimary.SKS',
            'krsmPrimary.NILAIAKHIR',
            DB::raw("ISNULL(SettingNilai.NilaiAngka, 0) * krsmPrimary.SKS AS BobotNilai"),
            'krsmPrimary.HasilPengakuan',
            'krsmPrimary.StatusKonversi'
        )
        ->where('krsmPrimary.NPM', $npm)
        ->where('krsmPrimary.StatusKonversi', 'F')
        ->get();

    $result2 = DB::table('prodimk as a')
        ->select(DB::raw("'' as NPM, a.idkampus as idkampus, a.prodi as prodi, a.kurikulum as kurikulum,
            a.idmk as idmk, '' as idmkasal, '' as matakuliahasal, 0 as sksasal, REPLACE(b.MATAKULIAH, '&amp;', '&')
             as matakuliah, a.sks as sks,
            '' as NilaiAkhir, 0 as bobotnilai, 'Wajib Ambil' as HasilPengakuan, '' as statuskonversi"))
        ->join('matakuliah as b', function ($join) {
            $join->on('b.IDMK', '=', 'a.IDMK')
                ->whereColumn('b.PRODIMATAKULIAH', '=', 'a.PRODI');
        })
        ->where('a.kurikulum', $kurikulum)
        ->where('a.IDKAMPUS', $idKampus)
        ->where('a.prodi', $prodi)
        ->whereNotIn('a.idmk', function ($query) use ($idKampus, $prodi, $npm) {
            $query->select('IDMK')
                ->from('krsmprimary')
                ->where('IDKAMPUS', $idKampus)
                ->where('prodi', $prodi)
                ->where('npm', $npm);
        })
        ->get();
        $totalsks1 = DB::table('krsmPrimary')
        ->where('NPM', $npm)
        ->where('HasilPengakuan', 'Di Akui Lansung')
        ->sum('SKS');
        $totalsks2 = DB::table('krsmPrimary')
        ->where('NPM', $npm)
        ->where('HasilPengakuan', 'Di Akui Dengan Syarat Tertentu')
        ->sum('SKS');
        $total = $totalsks1 + $totalsks2;
        // Menghitung jumlah SKS dari result1
        $totalSKSResult1 = 0;
        foreach ($result1 as $result) {
            $totalSKSResult1 += $result->SKS;
        }

        // Menghitung jumlah SKS dari result2
        $totalSKSResult2 = 0;
        foreach ($result2 as $result) {
            $totalSKSResult2 += $result->sks;
        }

        // Menggabungkan total SKS dari result1 dan result2
        $totalSKS = $totalSKSResult1 + $totalSKSResult2;
//dd($lokasi);
    $data = [
        'result1' => $result1,
        'result2' => $result2,
        'lokasi' => $lokasi,
        'nama'=>$nama,
        'idKampus' => $idKampus,
        'prodi' => $prodi,
        'npm'=>$npm,
        'ta'=>$ta,
        'fakultas'=>$fakultas,
        'npmasal'=>$npmasal,
        'prodiasal'=>$prodiasal,
        'universitas'=>$universitas,
        'totalsks1' => $totalsks1,
        'totalsks2' => $totalsks2,
        'total'=>$total,
        'totalSKS' => $totalSKS,
        'totalSKSResult2'=>$totalSKSResult2,
    ];
    //dd($data);
    return view('mahasiswa.konversi.detailldikti', compact('data', 'result1', 'result2','lokasi'));
}

}
