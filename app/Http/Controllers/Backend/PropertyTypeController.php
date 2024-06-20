<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// use Barryvdh\DomPDF\PDF;

use PDF;

class PropertyTypeController extends Controller
{
    public function showFormData()
{
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); // Replace 'your_prodi_table' with your actual table name

    // Pass the data to the view
    return view('backend.type.formHonorDosen', compact('allIdKampus','allProdi'));
}
public function processForm(Request $request)
{   
    
    $nama = $request->input('NamaDosen', '');
    $iddosen = $request->input('iddosen', '');
    $TA = $request->input('TA', '');
    $Semester = $request->input('Semester', '');
    $startDate = $request->input('startDate', '');
    $endDate = $request->input('endDate', '');
    $idKampus = $request->input('idkampus', '');
    $prodi = $request->input('prodi', '');
    $lokasi = $request->input('lokasi');
    $idFakultas = $request->input('idFakultas');
    $fakultas = $request->input('fakultas');

    //dd($prodi);
    $prodiData = DB::table('prodi')
    ->join('fakultas', 'fakultas.idfakultas', '=','prodi.idfakultas',)
    ->select('prodi.idfakultas', 'fakultas.fakultas', 'prodi.prodi')
    ->where('prodi.idfakultas', $prodi)
    ->first();

    session(['prodi' => $prodi]);
    session(['TA' => $TA]);
    session(['fakultas' => $fakultas]);
    session(['idkampus' => $idKampus]);
    session(['NamaDosen' => $nama]);
    session(['lokasi' => $lokasi]);
    session(['Semester' => $Semester]);
    $whereClause = [
        'TA' => $TA,
        'Semester' => $Semester,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'idKampus' => $idKampus,
    ];
    info('Parameters:', $whereClause);
    // Periksa apakah input 'NamaDosen' diisi atau tidak
    if (!empty($nama)) {
        $whereClause['NamaDosen'] = $nama;
        $isNamaFilled = true;
    } else {
        $isNamaFilled = false;
    }

    // Periksa apakah input 'iddosen' diisi atau tidak
    if (!empty($iddosen)) {
        $whereClause['iddosen'] = $iddosen;
    }
    if (!empty($prodi)) {
        $whereClause['prodi'] = $prodi;
        $isProdiFilled = true;
    } else {
        $isProdiFilled = false;
    }
    //dd($whereClause);
    

    $results = DB::select("
    SELECT
        a.fingerin as tglin,
        a.lokasi as lokasi,
        a.prodi as prodi,
        a.idDosen as iddosen,
        a.idmk as idmk,
        a.matakuliah as matakuliah,
        a.sks as sks,
        a.masuk as masuk,
        a.keluar as keluar,
        a.kelas as kelas,
        a.jumlahtotal as jumlah,
        a.pertemuanke,
        CASE
            WHEN a.keterangan LIKE '%Gabungan Ke Kelas%' THEN 0
            WHEN (MAX(a.pertemuanke) IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16)
                AND a.jumlahtotal BETWEEN MAX(b.jumlahMHSAwalA) AND MAX(b.JumlahMHSAkhirA)) THEN 0
            WHEN (MAX(a.pertemuanke) IN (5,6,7,8,9,10,11,12,13,14,15,16)
                AND a.jumlahtotal BETWEEN MAX(b.JumlahMhsAwalB) AND MAX(b.JumlahMHSAkhirB)) THEN 0
            WHEN (MAX(a.pertemuanke) IN (9,10,11,12,13,14,15,16)
                AND a.jumlahtotal BETWEEN MAX(b.jumlahMHSAWALC) AND MAX(B.JumlahMHSAkhirC)) THEN 0
            ELSE MAX(a.TotalHonor) 
        END AS honorSKSDosen,
        a.namadosen as namadosen,
        MAX(ISNULL(a.keterangan, '')) as keterangan
    FROM
        TblSementaraHonorDosen a
    INNER JOIN
        TblKoutaHonor b ON b.TA = a.TA AND b.Semester = a.semester
    WHERE
        a.TA = :TA
        AND a.Semester = :Semester
        AND a.fingerin >= :startDate
        AND a.fingerin <= :endDate
        AND a.idkampus = :idKampus
        " . ($isProdiFilled ? "AND a.prodi = :prodi" : "") . "
        " . ($isNamaFilled ? "AND a.NamaDosen = :NamaDosen" : "") . "
        " . (!empty($iddosen) ? "AND a.iddosen = :iddosen" : "") . " 
    GROUP BY
        a.fingerin,
        a.lokasi,
        a.prodi,
        a.idDosen,
        a.idmk,
        a.matakuliah,
        a.sks,
        a.masuk,
        a.keluar,
        a.kelas,
        a.jumlahtotal,
        a.pertemuanke,
        a.namadosen,
        a.keterangan
    ORDER BY
        a.idDosen ASC,
        a.namadosen ASC,
        a.idmk ASC,
        a.matakuliah ASC,
        a.fingerin ASC
", array_merge($whereClause),'prodi');

//dd($whereClause);
//dd($results);
//dd(session()->all());
// ...
foreach ($results as $result) {
    $result->matakuliah = strip_tags($result->matakuliah);
}

    // Calculate the totalHonor
    $totalHonor = !empty($results) ? array_sum(array_column($results, 'honorSKSDosen')) : 0;

    return view('backend.type.all_type', compact('results', 'totalHonor', 'prodi','isNamaFilled'));
}

public function fetchFakultas(Request $request)
{
    try {
        // Log the input parameter
        \Log::info('Input Parameter (prodi):', [$request->input('prodi')]);

        // Fetch corresponding idFakultas and fakultas based on $prodiId
        $fakultasData = DB::table('prodi')
            ->join('fakultas', 'prodi.idfakultas', '=', 'fakultas.idfakultas')
            ->select('prodi.idfakultas', 'fakultas.fakultas')
            ->where('prodi.prodi', $request->input('prodi'))
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


// Modifikasi pada metode searchDosen
public function searchDosen(Request $request)
{
    try {
        $searchTerm = $request->input('term');

        // Logika pencarian berdasarkan $searchTerm, misalnya di tabel Dosen
        $results = DB::table('dosen')
            ->select('iddosen', 'nama')
            ->where('nama', 'like', '%' . $searchTerm . '%')
            ->get();

        // Tambahkan pernyataan log sebelum mengembalikan respons
        \Log::info('SearchDosen request with term: ' . $searchTerm);

        return response()->json($results); // Mengembalikan seluruh hasil pencarian dalam bentuk JSON
    } catch (\Exception $e) {
        // Log the exception
        \Log::error($e->getMessage());

        // Return a response indicating an error
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
 //   public function sampelpdf ( )
 //   {
 //       // $pdf = PDF::loadview('pdf');
 //       // return $pdf->download('laporan-pegawai-pdf');

 //       $pdf = PDF::loadview('backend.type.pdf');
 //       $nama_pdf = date('YmdHi') . '-DataKaryawan.pdf';
 //       return $pdf->download($nama_pdf);
 //   }
     //public function AllType (){

    //    $types = PropertyType::latest()->get();
    //    $data = DB::table('dosen')->get();
    //    return view('backend.type.all_type',compact('types','results'));

    //}
     }