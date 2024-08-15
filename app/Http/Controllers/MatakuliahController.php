<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    public function showMatakuliah(Request $request)
    {
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
        $prodi = session('prodi');
        return view('matakuliah.matakuliah', compact('allProdi'));
    }

    public function viewMatakuliah(Request $request)
    {
        $prodi = $request->input('prodi');
        session(['prodi' => $prodi]);
    
        $results = DB::table('matakuliah')
            ->where('prodimatakuliah', $prodi)
            ->paginate(10);
    
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
    
        return view('matakuliah.matakuliah', compact('allProdi', 'results', 'prodi'));
    }
    public function searchMatakuliah(Request $request)
{
    $prodi = $request->input('prodi');
    $search = $request->input('search');

    // Retrieve results based on search criteria
    $results = DB::table('matakuliah')
        ->where('prodimatakuliah', $prodi)
        ->where(function($query) use ($search) {
            $query->where('IDMK', 'LIKE', "%{$search}%")
                  ->orWhere('MATAKULIAH', 'LIKE', "%{$search}%");
        })
        ->paginate(10);

    // Return results as JSON, including pagination 
    return response()->json($results);
}
public function detailMataKuliah (Request $request)
{
    $prodi = $request->input('prodi');
    $idmk = $request->input('idmk');
    
        session(['prodi' => $prodi]);
    
        $results = DB::table('matakuliah')
            ->where('idmk',$idmk)
            ->first();
        //dd($results);
        $result1 = DB::table('MKTASEMESTER2')
        ->where('idmk',$idmk)
        ->get();
        $result2 = DB::table('mkdosen as a')
        ->join('dosen as b', 'a.iddosen', '=', 'b.iddosen')
        ->select('a.iddosen', 'b.nama', 'a.idmk')
        ->where('a.idmk', $idmk)
        ->get();
        //dd($results);
        $allTipe = DB::table('GolongganTipe')->select('idprimary', 'tipe')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
        $allSemester = DB::table('tblsemester')->select('idprimary', 'semester')->distinct()->get();
        //dd($allTipe);
        return view('matakuliah.detailmatakuliah', compact('allProdi', 'results', 'prodi','result1','result2','allSemester','allTipe'));
}
public function updateMatakuliah(Request $request)
{
    // Validasi input
    $request->validate([
        'idPrimary' => 'required',
        'idmk' => 'required',
        'matakuliah' => 'required',
        'prodi' => 'required',
        'tipe' => 'required',
        'sks' => 'required|numeric',
        'teori' => 'required|numeric',
        'praktek' => 'required|numeric',
        'seminar' => 'required|numeric',
        'sikap' => 'required',
        'k_khusus' => 'required',
        'k_umum' => 'required',
        'pengetahuan' => 'required',
        'semester' => 'required',
        'pkl' => 'required',
        'skrpsi' => 'required',
        'rpl' => 'required',
    ]);

    // Ambil data yang diperlukan dari request
    $data = [
        'MATAKULIAH' => $request->input('matakuliah'),
        'PRODIMATAKULIAH' => $request->input('prodi'),
        'TIPE' => $request->input('tipe'),
        'SKS' => $request->input('sks'),
        'T' => $request->input('teori'),
        'P' => $request->input('praktek'),
        'SEMINAR' => $request->input('seminar'),
        'SIKAP' => $request->input('sikap'),
        'K_KHUSUS' => $request->input('k_khusus'),
        'K_UMUM' => $request->input('k_umum'),
        'PENGETAHUAN' => $request->input('pengetahuan'),
        'SEMESTER' => $request->input('semester'),
        'PKL' => $request->input('pkl'),
        'SKRIPSI' => $request->input('skrpsi'),
        'RPL' => $request->input('rpl'),
    ];

    // Lakukan update pada tabel matakuliah
    DB::table('matakuliah')
        ->where('idPrimary', $request->input('idPrimary'))
        ->where('IDMK', $request->input('idmk'))
        ->update($data);

    // Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Data Mata Kuliah berhasil diperbarui');
}
public function editPengampu(Request $request)
{
    $idprimary = $request->input('idPrimary');
    $idmk = $request->input('idmk');
    
    // Fetch the data for the specific Dosen Pengampu
    $results = DB::table('MKTASEMESTER2')
        ->where('idprimary', $idprimary)
        ->where('idmk', $idmk)
        ->first(); // Assuming you'll fetch a single record
    
    // Fetch all semesters
    $allSemester = DB::table('tblsemester')->select('idprimary', 'semester')->distinct()->get();
    //dd($results);
    // Pass the data to the view
    return view('matakuliah.ubahpengampu', compact('results', 'allSemester'));
}
public function updatePengampu(Request $request)
{
    $idprimary = $request->input('idPrimary');
    $idmk = $request->input('idmk');
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $iddosen = $request->input('iddosen');
    DB::table('MKTASEMESTER')
        ->where('idprimary', $idprimary)
        ->where('idmk', $idmk)
        ->update([
            'IdDosenPengampu' => $iddosen,
            'TA' => $ta,
            'Semester' => $semester,
        ]);

        return redirect()->route('detailMataKuliah', ['prodi' => $request->input('prodi'), 'idmk' => $idmk])
        ->with('success', 'Dosen Pengampu updated successfully');
}
public function addMataKuliah (Request $request)
{
        $allTipe = DB::table('GolongganTipe')->select('idprimary', 'tipe')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
        $allSemester = DB::table('tblsemester')->select('idprimary', 'semester')->distinct()->get();
        return view('matakuliah.addmatakuliah', compact('allProdi', 'allSemester','allTipe'));
}
public function tambahMatakuliah(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'idmk' => 'required|string|max:10',
        'matakuliah' => 'required|string|max:255',
        'prodi' => 'required|string|max:50',
        'golmk' => 'required|string|max:50',
        'sks' => 'required|integer',
        'teori' => 'required|integer',
        'praktek' => 'required|integer',
        'seminar' => 'required|integer',
        'sikap' => 'required|string|in:Y,N',
        'k_khusus' => 'required|string|in:Y,N',
        'k_umum' => 'required|string|in:Y,N',
        'pengetahuan' => 'required|string|in:Y,N',
        'semester' => 'required|integer',
        'pkl' => 'required|string|in:Y,N',
        'skrpsi' => 'required|string|in:Y,N',
        'rpl' => 'required|string|in:Y,N',
    ]);

    // Insert the data into the MATAKULIAH table
    DB::table('MATAKULIAH')->insert([
        'IDMK' => $request->input('idmk'),
        'MATAKULIAH' => $request->input('matakuliah'),
        'PRODIMATAKULIAH' => $request->input('prodi'),
        'TIPE' => $request->input('golmk'),
        'SKS' => $request->input('sks'),
        'T' => $request->input('teori'),
        'P' => $request->input('praktek'),
        'SEMINAR' => $request->input('seminar'),
        'SIKAP' => $request->input('sikap'),
        'K_KHUSUS' => $request->input('k_khusus'),
        'K_UMUM' => $request->input('k_umum'),
        'PENGETAHUAN' => $request->input('pengetahuan'),
        'SEMESTER' => $request->input('semester'),
        'PKL' => $request->input('pkl'),
        'SKRIPSI' => $request->input('skrpsi'),
        'RPL' => $request->input('rpl'),
        'MATAKULIAHPRASYARAT' => '',
    ]);

    // Return a JSON response with a success message
    return response()->json(['success' => 'Matakuliah added successfully.']);
}
public function tambahPengampu(Request $request)
{       $idmk = $request->query('idmk');
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get();
        $allSemester = DB::table('tblsemester')->select('idprimary', 'semester')->distinct()->get();
        //dd($idmk);
    return view('matakuliah.addpengampu', compact('allProdi', 'allSemester','allIdKampus','idmk'));
}
public function addPengampu(Request $request)
{
    $idmk = $request->input('idmk');
    $idkampus = $request->input('idkampus');
    $lokasi = $request->input('lokasi'); // Lokasi bisa disimpan jika diperlukan
    $prodi = $request->input('prodi');
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $iddosen = $request->input('iddosen');
    $itemno = '1';

    // Simpan data ke tabel MKTASEMESTER
    DB::table('MKTASEMESTER')->insert([
        'IDMK' => $idmk,
        'IDKAMPUS' => $idkampus,
        'PRODI' => $prodi,
        'TA' => $ta,
        'SEMESTER' => $semester,
        'IdDosenPengampu' => $iddosen,
        'ItemNo' => $itemno,
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('showMatakuliah')->with('success', 'Dosen Pengampu berhasil ditambahkan');
}

}

