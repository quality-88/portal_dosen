<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function showKurikulum(Request $request)
    {
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
    
        // Check if session data is available
        $sessionData = [
            'prodi' => session('prodi'),
            'fakultas' => session('fakultas'),
            'idkampus' => session('idkampus'),
            'kurikulum' => session('kurikulum'),
            'lokasi' => session('lokasi'),
            'idFakultas' => session('idFakultas')
        ];
    
        // Log session data for debugging
        \Log::info('Session data on showKurikulum:', $sessionData);
    
        return view('kurikulum.kurikulumprodi', compact('allIdKampus', 'allProdi', 'sessionData'));
    }
    
    public function viewKurikulum(Request $request)
    {
        // Capture form data
        $kurikulum = $request->input('kurikulum');
        $idKampus = $request->input('idkampus');
        $prodi = $request->input('prodi');
        $lokasi = $request->input('lokasi');
        $idFakultas = $request->input('id_fakultas');
        $fakultas = $request->input('fakultas');
    
        // Store in session
        session([
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'idkampus' => $idKampus,
            'kurikulum' => $kurikulum,
            'lokasi' => $lokasi,
            'idFakultas' => $idFakultas
        ]);
    
        // Fetch results from database
        $results = DB::table('ProdiMK')
            ->select('ProdiMK.idPrimary as idPrimary',
                     'ProdiMK.IDMK as IDMK',
                     'ProdiMK.SKS as SKS',
                     'ProdiMK.SEMESTER as SEMESTER',
                     'matakuliah.matakuliah as matakuliah')
            ->join('matakuliah', 'matakuliah.idmk', '=', 'ProdiMK.IDMK')
            ->where('ProdiMK.kurikulum', '=', $kurikulum)
            ->where('ProdiMK.idkampus', '=', $idKampus)
            ->where('ProdiMK.prodi', '=', $prodi)
            ->get();
    
        // Log results for debugging
        \Log::info('Results fetched from DB:', $results->toArray());
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
        // Return view with results
        return view('kurikulum.kurikulumprodi', compact('results', 'allIdKampus', 'allProdi'));
    }
    
    public function showTambahKurikulum (Request $request)
    {
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
        $allMatkul = DB::table('matakuliah')->select('idmk', 'matakuliah')->distinct()->get();
        $allKurikulum  = DB::table('kurikulum')->select('kurikulum', 'tahunajaran1')->distinct()->get();
        return view('kurikulum.tambahkurikulum',compact('allIdKampus','allProdi','allKurikulum','allMatkul')); 
    }
    public function searchIdmk(Request $request)
{

        $searchTerm = $request->input('term');


        // Modifikasi query untuk mengambil data matakuliah sesuai prodi dan kurikulum yang dipilih
        $results = DB::table('matakuliah')
            ->select('IDMK', 'MATAKULIAH', 'SKS')
            ->whereIn('IDMK', function($query) {
                $query->select('idmk')
                      ->from('matakuliah');
            })
            ->where('MATAKULIAH', 'like', '%' . $searchTerm . '%')
            ->get();

            return response()->json($results);
}
public function tambahKurikulum(Request $request)
{
    $idmk = $request->input('idmk');
    $prodi = $request->input('prodi');
    $kurikulum = $request->input('kurikulum');
    $idKampus = $request->input('idKampus');
    $idFakultas = $request->input('idFakultas');
    $prodi = $request->input('prodi');
    $semester = $request->input('semester');
    $ta = $request->input('ta');
    $pilihan = $request->input('pilihan');
    $sks = $request->input('sks');

    // Periksa apakah data sudah ada dalam database
    $existingData = DB::table('prodimk')
                    ->where('IDMK', $idmk)
                    ->where('prodi', $prodi)
                    ->where('kurikulum', $kurikulum)
                    ->where('idkampus', $idKampus)
                    ->where('idfakultas', $idFakultas)
                    ->where('semester', $semester)
                    ->where('MKPilihan', $pilihan)
                    ->where('sks', $sks)
                    ->first();

    // Jika data sudah ada, kirimkan notifikasi
    if ($existingData) {
        return response()->json(['error' => 'Data sudah ada dalam database!']);
    }

    // Mengambil itemno terakhir untuk idDosen tertentu
    $lastItemNo = DB::table('prodimk')
                    ->where('prodi', $prodi)
                    ->max('ITEMNO');
    // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
    $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;

    // Lakukan penyisipan data ke dalam tabel pendidikandosen
    DB::table('prodimk')->insert([
        'ITEMNO' => $itemNo,
        'IDMK' => $idmk,
        'prodi' => $prodi,
        'kurikulum' => $kurikulum,
        'idkampus' => $idKampus,
        'idfakultas' => $idFakultas,
        'semester' => $semester,
        'MKPilihan' => $pilihan,
        'sks' => $sks,
        'ta' => $kurikulum
    ]);

    return response()->json(['success' => 'Data berhasil ditambahkan!']);
}

public function fetchFakultash(Request $request)
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
public function editKurikulum($id)
{
  
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    $allMatkul = DB::table('matakuliah')->select('idmk', 'matakuliah')->distinct()->get();
    $allKurikulum  = DB::table('kurikulum')->select('kurikulum', 'tahunajaran1')->distinct()->get();
    // Cari data kurikulum berdasarkan ID
    
    $kurikulum = DB::table('ProdiMK')
    ->select(
        'ProdiMK.idPrimary as idPrimary',
        'ProdiMK.IDMK as IDMK',
        'ProdiMK.SKS as SKS',
        'ProdiMK.SEMESTER as SEMESTER',
        'matakuliah.matakuliah as matakuliah',
        'ProdiMK.kurikulum as kurikulum'
    )
    ->join('matakuliah', 'matakuliah.idmk', '=', 'ProdiMK.IDMK')
    ->where('ProdiMK.idPrimary', $id)  // Tambahkan alias ProdiMK di sini
    ->first();
    // Pastikan data ditemukan
    if (!$kurikulum) {
        return redirect()->route('viewKurikulum')->with('error', 'Data Kurikulum tidak ditemukan.');
    }

    // Kembalikan view untuk halaman edit dengan data kurikulum
    return view('kurikulum.editkurikulum', compact('kurikulum','allIdKampus','allProdi','allKurikulum','allMatkul'));
}

public function updateKurikulum(Request $request, $id)
{
    // Validasi data yang diterima dari form
    $validatedData = $request->validate([
        'kurikulum' => 'required',
        'semester' => 'required',
        'sks' => 'required',
        'idmk' => 'required',
    ]);

    // Update data di database
    DB::table('prodimk')->where('idPrimary', $id)->update($validatedData);

    // Set notifikasi sukses di session
    return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteKurikulum(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    try {
        // Hapus data dari tabel prodimk berdasarkan idPrimary
        DB::table('prodimk')->where('idPrimary', $idPrimary)->delete();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

}
