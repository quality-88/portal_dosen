<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class JadwalController extends Controller
{
    public function showJadwal (Request $request)
    {
       
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
        
        return view('jadwal.inputjadwal',compact('allIdKampus','allProdi'));   
    }
    public function showJadwalAdmin (Request $request)
    {
       
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
        
        return view('jadwal.inputjadwaladmin',compact('allIdKampus','allProdi'));   
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
    ->leftJoin('dosen AS dosen2', 'jadwalprimary.iddosen2', '=', 'dosen2.iddosen')
    ->leftJoin('dosen AS dosen3', 'jadwalprimary.iddosen3', '=', 'dosen3.iddosen') // Join kembali ke tabel dosen untuk dosen kedua
    ->leftJoin('dosen AS dosen4', 'jadwalprimary.iddosen4', '=', 'dosen4.iddosen')
    ->select('jadwalprimary.idprimary', 'jadwalprimary.kelas', 'jadwalprimary.kurikulum', 'jadwalprimary.idmk',
        'jadwalprimary.sks', 'jadwalprimary.idruang', 'jadwalprimary.jammasuk', 'jadwalprimary.jamkeluar',
        'jadwalprimary.nosilabus', 'matakuliah.matakuliah', 'jadwalprimary.iddosen', 'dosen.nama AS nama',
        'jadwalprimary.Keterangan', 'jadwalprimary.HonorSKS', 'jadwalprimary.iddosen2', 'jadwalprimary.harijadwal',
        'dosen2.nama AS nama_dosen2','jadwalprimary.iddosen3', 'dosen3.nama AS nama_dosen3','jadwalprimary.SK2',
        'jadwalprimary.iddosen4', 'dosen4.nama AS nama_dosen4','jadwalprimary.SK3') // Mengambil nama dosen kedua dengan alias nama_dosen2
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
    $prodi = $request->prodi;

        // Fetch corresponding idFakultas and fakultas based on $prodiId
        $fakultasData = DB::table('prodifakultas')
            ->join('fakultas', 'prodifakultas.idfakultas', '=', 'fakultas.idfakultas')
            ->select('prodifakultas.idfakultas', 'fakultas.fakultas')
            ->where('prodifakultas.prodi', $prodi)
            ->first();
            // Return the data as JSON response
            return response()->json($fakultasData);
}
public function getKelas(Request $request)
{

    $idkampus = $request->input('idkampus');
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('kelas')
    ->select(
        'kelas as kelas1',
    )
    ->where('idkampus', $idkampus);

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('kelas', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->get();

    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function getIDMK(Request $request)
{
    try {
        $ta = $request->input('ta');
        $semester = $request->input('semester');
        $prodi = $request->input('prodi');
        $searchQuery = $request->input('searchQuery');
        
        $results = DB::table('MKTASEMESTER')
            ->select(
                'prodimk.idmk as idmk',
                'matakuliah.matakuliah as matakuliah',
                'prodimk.sks as sks',
                'dosen.nama as nama',
                'MKTASEMESTER.IdDosenPengampu as iddosen'
            )
            ->join('prodimk', 'MKTASEMESTER.idmk', '=', 'prodimk.idmk')
            ->join('matakuliah', 'matakuliah.idmk', '=', 'prodimk.idmk')
            ->join('dosen', 'dosen.iddosen', '=', 'MKTASEMESTER.IdDosenPengampu')
            ->where('MKTASEMESTER.ta', $ta)
            ->where('MKTASEMESTER.semester', $semester)
            ->where('prodimk.prodi', $prodi)
            ->distinct();
        
        if ($searchQuery) {
            $results = $results->where(function($query) use ($searchQuery) {
                $query->where('matakuliah.matakuliah', 'like', '%' . $searchQuery . '%')
                      ->orWhere('dosen.nama', 'like', '%' . $searchQuery . '%');
            });
        }
        
        $results = $results->get();
        
        return response()->json($results);
    } catch (\Exception $e) {
        \Log::error('Error fetching IDMK data: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while fetching data.'], 500);
    }
}

public function getRuang(Request $request)
{
    $sks = number_format($request->input('sks'), 2, '.', '');
    $searchQuery = $request->input('searchQuery');

    \Log::info('Received request for getRuang', ['sks' => $sks, 'searchQuery' => $searchQuery]);

    $results = DB::table('RuangJam')
        ->select('idruang', 'jammasuk', 'jamkeluar')
        ->where('sks', $sks);

    if ($searchQuery) {
        $results = $results->where(function($query) use ($searchQuery) {
            $query->where('idruang', 'like', '%' . $searchQuery . '%')
                  ->orWhere('jammasuk', 'like', '%' . $searchQuery . '%');
        });
    }

    $results = $results->get();

    \Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function getDosen(Request $request)
{
    $idmk = $request->input('idmk');
    $dosen = DB::table('mkdosen2')
    ->select('iddosen', 'nama', 'honorsks')
    ->where('idmk', $idmk)
    ->get();
    return response()->json($dosen);
}
public function getHonor(Request $request)
{
    $prodi = $request->input('prodi');
    $idmk = $request->input('idmk');
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('mkdosen2')
    ->select(
        'iddosen as idpengajar',
        'nama',
        'prodimatakuliah',
        'idmk',
        'matakuliah',
        DB::raw("CASE
            WHEN prodimatakuliah LIKE 'S2%' THEN honorskss2
            ELSE honorsks
        END AS honor")
    )
    ->where('idmk', $idmk)
    ->where('prodimatakuliah', $prodi);

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('matakuliah', 'like', '%' . $searchQuery . '%');
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
public function getDosen2(Request $request)
{
    $prodi = $request->input('prodi');
    $idmk = $request->input('idmk');
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('mkdosen2')
    ->select(
        'iddosen as dosen',
        'nama as nama1',
        'prodimatakuliah',
        'idmk',
        'matakuliah'
    )
    ->where('idmk', $idmk)
    ->where('prodimatakuliah', $prodi);

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('matakuliah', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->get();

    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function getDosen3(Request $request)
{
    $prodi = $request->input('prodi');
    $idmk = $request->input('idmk');
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('mkdosen2')
    ->select(
        'iddosen as dosen1',
        'nama as nama2',
        'prodimatakuliah',
        'idmk',
        'matakuliah'
    )
    ->where('idmk', $idmk)
    ->where('prodimatakuliah', $prodi);

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('matakuliah', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->get();

    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function getGabungan(Request $request)
{
    $idkampus = $request->input('idkampus');
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('kelas')
    ->select(
        'kelas as kelasgabungan',
    )
    ->where('idkampus', $idkampus);

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('kelas', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->get();

    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function getProdiGabungan(Request $request)
{
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('prodifakultas')
    ->select(
        'prodi as prodigabungan',
    );

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('prodi', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->get();

    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function getKurikulum(Request $request)
{
    $searchQuery = $request->input('searchQuery'); // Ensure this matches your AJAX request

    
    $results = DB::table('kurikulum')
    ->select(
        'kurikulum as kurikulum1',
        'tahunajaran1',
    );

    if ($searchQuery) {
        $results = $results->where(function ($query) use ($searchQuery) {
            $query->where('kurikulum', 'like', '%' . $searchQuery . '%');
        });
    }
    $results = $results->get();

    Log::info('Query results', ['results' => $results]);

    return response()->json($results);
}
public function simpan(Request $request)
{
    // Ambil data dari request
    $idkampus = $request->input('idkampus');
    $prodi = $request->input('prodi');
    $idfakultas = $request->input('idfakultas');
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $kelas = $request->input('kelas');
    $kurikulum = $request->input('kurikulum');
    $idmk = $request->input('idmk');
    $matakuliah = $request->input('matakuliah');
    $keterangan = $request->input('keterangan');
    $idruang = $request->input('idruang');
    $jammasuk = $request->input('jammasuk');
    $jamkeluar = $request->input('jamkeluar');
    $silabus = $request->input('silabus');
    $idpengajar = $request->input('idpengajar');
    $iddosen = $request->input('iddosen');
    $nama = $request->input('nama');
    $honor = $request->input('honor');
    $sk2 = $request->input('sk2');
    $dosen = $request->input('dosen');
    $nama1 = $request->input('nama1');
    $sk3 = $request->input('sk3');
    $dosen1 = $request->input('dosen1');
    $sks = $request->input('sks');
    $sk4 = $request->input('sk4');
    $gabungan = $request->input('gabungan');
    $hari = $request->input('hari');
    $harijadwal = $request->input('harijadwal');
    $kelasgabungan = $request->input('kelasgabungan');
    $gabunganprodi = $request->input('gabunganprodi');
    $prodigabungan = $request->input('prodigabungan');
    
    // Tentukan nilai untuk Chk
    $honor = str_replace('.', ',', $honor);
    $chk = ($gabungan == 'Y') ? 'R' : '£';
    $itemno = 1;
    $sk1 = '----';
    $userid = session('userid');
    $validasi = 'F';

    // Peta hari
    $hariMap = [
        '1' => 'Senin',
        '2' => 'Selasa',
        '3' => 'Rabu',
        '4' => 'Kamis',
        '5' => 'Jumat',
        '6' => 'Sabtu',
        '7' => 'Minggu'
    ];
    
    // Cek bentrok jadwal dosen
    $existingClasses = DB::table('jadwalprimary')
        ->where('iddosen', $iddosen)
        ->where('HariJadwal', $harijadwal)
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->where(function ($query) use ($jammasuk, $jamkeluar) {
            $query->whereBetween('jammasuk', [$jammasuk, $jamkeluar])
                  ->orWhereBetween('jamkeluar', [$jammasuk, $jamkeluar])
                  ->orWhere(function ($query) use ($jammasuk, $jamkeluar) {
                      $query->where('jammasuk', '<=', $jammasuk)
                            ->where('jamkeluar', '>=', $jamkeluar);
                  });
        })
        ->get(['idmk', 'kelas', 'jammasuk', 'jamkeluar', 'HariJadwal']);

    if ($existingClasses->count() > 0) {
        // Menambahkan nama hari ke data yang dikembalikan
        $existingClasses->transform(function ($item) use ($hariMap) {
            $item->hari = $hariMap[$item->HariJadwal] ?? 'Unknown';
            return $item;
        });

        // Jika ada bentrok, kembalikan data bentrok
        return response()->json([
            'status' => 'error',
            'message' => 'Dosen sudah memiliki kelas pada jam yang sama',
            'data' => $existingClasses
        ]);
    }

    // Cek apakah dosen sudah memiliki jadwal mengajar idmk tersebut
    $existingIdmkSchedule = DB::table('jadwalprimary')
        ->where('iddosen', $iddosen)
        ->where('idmk', $idmk)
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->first();

    if ($existingIdmkSchedule) {
        // Ambil detail jadwal dosen untuk mata kuliah ini
        $existingDetails = DB::table('jadwalprimary')
            ->where('iddosen', $iddosen)
            ->where('idmk', $idmk)
            ->where('ta', $ta)
            ->where('semester', $semester)
            ->get(['harijadwal', 'kelas', 'jammasuk', 'jamkeluar']);

        // Konversi hari dari angka ke nama hari
        $existingDetails = $existingDetails->map(function ($item) use ($hariMap) {
            $item->harijadwal = $hariMap[$item->harijadwal] ?? 'Tidak Diketahui';
            return $item;
        });

        return response()->json([
            'status' => 'error',
            'message' => 'Dosen sudah memiliki jadwal mengajar untuk mata kuliah ini',
            'matakuliah' => $matakuliah,
            'details' => $existingDetails
        ]);
    }
    
    // Simpan data ke tabel jadwalprimary
    DB::table('jadwalprimary')->insert([
        'idkampus' => $idkampus,
        'prodi' => $prodi,
        'idfakultas' => $idfakultas,
        'ta' => $ta,
        'semester' => $semester,
        'kelas' => $kelas,
        'kurikulum' => $kurikulum,
        'idmk' => $idmk,
        'keterangan' => $keterangan,
        'idruang' => $idruang,
        'jammasuk' => $jammasuk,
        'jamkeluar' => $jamkeluar,
        'NoSilabus' => $silabus,
        'iddosen' => $iddosen,
        'iddosen2' => $idpengajar,
        'HonorSKS' => $honor,
        'sk1' => $sk1,
        'sk2' => $sk2,
        'iddosen3' => $dosen,
        'sk3' => $sk3,
        'iddosen4' => $dosen1,
        'sk4' => $sk4,
        'sks' => $sks,
        'Gabungan' => $kelasgabungan,
        'gabunganprodi' => $gabunganprodi,
        'prodigabungan' => $prodigabungan,
        'chk' => $chk,
        'ItemNo' => $itemno,
        'useridupdate' => $userid,
        'Hari' => $hari,
        'HariJadwal' => $harijadwal,
        'Validasi' => $validasi,
        'lastupdate' => now()
    ]);

    return response()->json(['status' => 'success']);
}

public function validateJadwal(Request $request)
{
    $idprimary = $request->input('idprimary');
    $validasi = 'T';

    // Simpan data ke tabel jadwalprimary
    $affected = DB::table('jadwalprimary')
        ->where('idprimary', $idprimary) // Menambahkan kondisi where
        ->update([
            'Validasi' => $validasi
        ]);

    // Mengembalikan respons dalam format JSON
    if ($affected) {
        return response()->json(['success' => true], 200);
    } else {
        return response()->json(['success' => false], 500);
    }
}
public function deleteJadwal(Request $request)
{
    $idprimary = $request->input('idprimary');

    // Cek apakah data dengan idprimary memiliki validasi = 'T'
    $validasi = DB::table('jadwalprimary')
        ->where('idprimary', $idprimary)
        ->value('Validasi'); // Mengambil nilai Validasi

    // Jika validasi adalah 'T', tolak penghapusan
    if ($validasi === 'T') {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak dapat dihapus karena sudah divalidasi.'
        ], 403);
    }

   
    // Jika validasi bukan 'T', lakukan penghapusan
    $deleted = DB::table('jadwalprimary')
        ->where('idprimary', $idprimary)
        ->delete();

    // Mengembalikan respons dalam format JSON
    if ($deleted) {
        return response()->json([
            'success' => true,
            'message' => 'Data telah dihapus.'
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menghapus data.'
        ], 500);
    }
}
public function validateAllByDay(Request $request)
{
    $idkampus = $request->input('idkampus');
    $prodi = $request->input('prodi');
    $idfakultas = $request->input('idfakultas');
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $harijadwal = $request->input('harijadwal');
    $validasi = 'T';
    
    // Validasi semua data berdasarkan hari
    $updated = DB::table('jadwalprimary')
        ->where('idkampus', $idkampus)
        ->where('prodi', $prodi)
        ->where('idfakultas', $idfakultas)
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->where('harijadwal', $harijadwal)
        ->update(['Validasi' => $validasi]);
    
    if ($updated) {
        return response()->json([
            'success' => true,
            'message' => 'Semua data untuk hari ini telah divalidasi.'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memvalidasi data.'
        ], 500);
    }
}

}
