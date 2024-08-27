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
public function showRekapSksDosen(Request $request)
{
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('dosen.rekapsksdosen',compact('allIdKampus','allProdi'));
}
public function viewRekapSksDosen(Request $request)
{   
    $semester = $request->input('semester');
    $ta = $request->input('ta');
    $idkampus = $request->input('idkampus');
    $lokasi = $request->input('lokasi');

    // Default query for idkampus not equal to 11 or 16
    $query = "
        WITH LecturerSchedules AS (
            SELECT 
                jadwalPrimary.IDDOSEN2, 
                dosen.NAMA,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'PGSD' THEN jadwalPrimary.sks ELSE 0 END), 0) AS PGSD,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'PPKN' THEN jadwalPrimary.sks ELSE 0 END), 0) AS PPKN,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'HUKUM' THEN jadwalPrimary.sks ELSE 0 END), 0) AS HUKUM,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'MANAJEMEN' THEN jadwalPrimary.sks ELSE 0 END), 0) AS MANAJEMEN,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'TEKNIK SIPIL' THEN jadwalPrimary.sks ELSE 0 END), 0) AS TEKNIKSIPIL,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'AGROTEKNOLOGI' THEN jadwalPrimary.sks ELSE 0 END), 0) AS AGROTEKNOLOGI,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'AGRIBISNIS' THEN jadwalPrimary.sks ELSE 0 END), 0) AS AGRIBISNIS,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'MATEMATIKA' THEN jadwalPrimary.sks ELSE 0 END), 0) AS MATEMATIKA,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi LIKE '%uqb' THEN jadwalPrimary.sks ELSE 0 END), 0) AS uqb,
                -- Total SKS, adding SKS from 'uqb' programs to the sum of specific programs
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi IN ('PGSD', 'PPKN', 'HUKUM', 'MANAJEMEN', 'TEKNIK SIPIL', 'AGROTEKNOLOGI', 'AGRIBISNIS', 'MATEMATIKA') THEN jadwalPrimary.sks ELSE 0 END), 0) +
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi LIKE '%uqb' THEN jadwalPrimary.sks ELSE 0 END), 0) AS TotalSKS,
                COUNT(DISTINCT CASE WHEN jadwalPrimary.Prodi LIKE '%uqb' THEN jadwalPrimary.Prodi END) AS CountUQBProdi,
                COUNT(DISTINCT CASE WHEN jadwalPrimary.Prodi NOT LIKE '%uqb' THEN jadwalPrimary.Prodi END) AS CountNonUQBProdi
            FROM 
                jadwalPrimary 
                INNER JOIN dosen ON jadwalPrimary.IDDOSEN2 = dosen.IDDOSEN 
            WHERE 

                jadwalPrimary.TA = ?
                AND jadwalPrimary.Semester = ?
                AND jadwalPrimary.chk <> 'R'
            GROUP BY 
                jadwalPrimary.IDDOSEN2, 
                dosen.NAMA
        )
        SELECT 
            IDDOSEN2, 
            NAMA,
            PGSD,
            PPKN,
            HUKUM,
            MANAJEMEN,
            TEKNIKSIPIL,
            AGROTEKNOLOGI,
            AGRIBISNIS,
            MATEMATIKA,
            uqb,
            TotalSKS
        FROM 
            LecturerSchedules
        WHERE 
            (CountUQBProdi > 0 AND CountNonUQBProdi > 0)  -- Lecturer has both 'uqb' and non-'uqb' programs
            OR (CountUQBProdi = 0)  -- Lecturer has no 'uqb' programs
        ORDER BY
            NAMA
    ";

    // Conditional query modification for idkampus 11 and 16
    if (in_array($idkampus, [11, 16])) {
        $query = "
           WITH LecturerSchedules AS (
            SELECT 
                jadwalPrimary.IDDOSEN2, 
                dosen.NAMA,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'Arsitektur UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS Arsitektur, 
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'PGSD UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS PGSD, 
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'PPKN UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS PPKN, 
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'HUKUM UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS HUKUM, 
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'MANAJEMEN UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS MANAJEMEN,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'Akuntansi UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS Akuntansi,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'AGROTEKNOLOGI UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS AGROTEKNOLOGI,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'AGRIBISNIS UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS AGRIBISNIS,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'MATEMATIKA UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS MATEMATIKA,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'POR UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS PORUQB,
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi = 'PBING UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS PBINGUQB,
                -- Calculate SKS for prodi without 'UQB' as 'uq'
                ISNULL(SUM(CASE WHEN RIGHT(jadwalPrimary.Prodi, 3) <> 'UQB' THEN jadwalPrimary.SKS ELSE 0 END), 0) AS uq,
                -- Total SKS, adding SKS from all prodi
                ISNULL(SUM(CASE WHEN jadwalPrimary.Prodi IN ('PGSD UQB', 'PPKN UQB', 'HUKUM UQB', 'MANAJEMEN UQB',
                'Akuntansi UQB', 'AGROTEKNOLOGI UQB', 'AGRIBISNIS UQB', 'MATEMATIKA UQB', 'POR UQB', 'PBING UQB') 
                THEN jadwalPrimary.sks ELSE 0 END), 0) +
                ISNULL(SUM(CASE WHEN RIGHT(jadwalPrimary.Prodi, 3) <> 'UQB' THEN jadwalPrimary.sks ELSE 0 END), 0) AS TotalSKS,
                COUNT(DISTINCT CASE WHEN RIGHT(jadwalPrimary.Prodi, 3) = 'UQB' THEN jadwalPrimary.Prodi END) AS CountUQBProdi,
                COUNT(DISTINCT CASE WHEN RIGHT(jadwalPrimary.Prodi, 3) <> 'UQB' THEN jadwalPrimary.Prodi END) AS CountNonUQBProdi
            FROM 
                jadwalPrimary 
                INNER JOIN dosen ON jadwalPrimary.IDDOSEN2 = dosen.IDDOSEN 
            WHERE 
                jadwalPrimary.TA = ?
                AND jadwalPrimary.Semester = ?
                AND jadwalPrimary.chk <> 'R'
            GROUP BY 
                jadwalPrimary.IDDOSEN2, 
                dosen.NAMA
        )
        SELECT 
            IDDOSEN2, 
            NAMA,
            PGSD UQB,
            PPKN UQB,
            HUKUM UQB, 
            MANAJEMEN UQB,
            Akuntansi UQB,
            AGROTEKNOLOGI UQB,
            AGRIBISNIS UQB,
            MATEMATIKA UQB,
            PORUQB AS POR ,
            PBINGUQB AS PBING,
            uq,
            TotalSKS
        FROM 
            LecturerSchedules
        WHERE 
            (CountUQBProdi > 0 AND CountNonUQBProdi > 0)  -- Lecturer has both 'uqb' and non-'uqb' programs
            OR (CountUQBProdi != 0)  -- Lecturer has no 'uqb' programs
        ORDER BY
            NAMA
        ";
    }

    $results = DB::select($query, [ $ta, $semester]);

    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();

    return view('dosen.rekapsksdosen', compact('results', 'allIdKampus', 'allProdi', 'ta', 'semester', 'idkampus', 'lokasi'));
}
public function showRincianSksDosen(Request $request)
{
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('dosen.rinciansksdosen',compact('allIdKampus','allProdi'));
}
public function viewRincianSksDosen(Request $request)
{
    $semester = $request->input('semester');
    $ta = $request->input('ta');
    $idkampus = $request->input('idkampus');
    $lokasi = $request->input('lokasi');
    $prodi = $request->input('prodi');
    $fakultas = $request->input('fakultas');
    $idfakultas = $request->input('id_fakultas');

    $results = DB::table('jadwalPrimary')
    ->distinct()
    ->join('dosen', 'jadwalPrimary.IDDOSEN2', '=', 'dosen.IDDOSEN')
    ->leftJoin('matakuliah', function($join) {
        $join->on('jadwalPrimary.IDMK', '=', 'matakuliah.IDMK')
             ->on('jadwalPrimary.PRODI', '=', 'matakuliah.prodimatakuliah');
    })
    ->where('jadwalPrimary.idkampus', $idkampus)
    ->where('jadwalPrimary.prodi', $prodi)
    ->where('jadwalPrimary.TA', $ta)
    ->where('jadwalPrimary.Semester',$semester)
    ->select([
        'jadwalPrimary.IDDOSEN2', 
        'dosen.NAMA', 
        'jadwalPrimary.IDMK',  
        'jadwalPrimary.IDRUANG', 
        'jadwalPrimary.TA', 
        'jadwalPrimary.JAMMASUK', 
        'jadwalPrimary.JAMKELUAR', 
        'jadwalPrimary.KURIKULUM', 
        'jadwalprimary.SKS',
        'jadwalprimary.kelas',
        'matakuliah.MATAKULIAH', 
        'jadwalPrimary.keterangan', 
        DB::raw("CASE 
            WHEN jadwalPrimary.harijadwal = 1 THEN 'Senin' 
            WHEN jadwalPrimary.harijadwal = 2 THEN 'Selasa' 
            WHEN jadwalPrimary.harijadwal = 3 THEN 'Rabu' 
            WHEN jadwalPrimary.harijadwal = 4 THEN 'Kamis' 
            WHEN jadwalPrimary.harijadwal = 5 THEN 'Jumat' 
            WHEN jadwalPrimary.harijadwal = 6 THEN 'Sabtu' 
            ELSE jadwalPrimary.Keterangan 
        END AS harijadwal")
    ])
    ->get();
    //dd($results);
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('dosen.rinciansksdosen',compact('allIdKampus','allProdi','results','ta','semester','idkampus','lokasi',
    'prodi','fakultas','idfakultas'));
}
}
