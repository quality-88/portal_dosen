<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingHonorController extends Controller
{
    public function showSettingHonor()
    {
        // Ambil data jabatan dari database
        $sethonor = DB::table('setHonorS2')->get();
        foreach ($sethonor as $item) {
            // Jika 'honors2' tidak kosong
            if (!is_null($item->HonorS2)) {
                $item->HonorS2 = number_format($item->HonorS2, 0, ',', '.');
            }
        }
        // Kirimkan data jabatan ke view
        return view('CMS.honorsksdosen', compact('sethonor')); 
    } 
    public function simpanSettingHonor(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $honors2 = $request->input('honors2');

    $honors2 = str_replace('.', ',', $honors2);

    // Insert data into the database
    DB::table('setHonorS2')->insert([
        'ta' => $ta,
        'semester' => $semester,
        'honors2' => $honors2
    ]);

    // Send success message
    return response()->json(['success' => 'Data berhasil ditambahkan!']);
}
public function activateHonorSks(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');

    // Retrieve the 'HonorS2' values from the 'setHonorS2' table based on 'ta' and 'semester'
    $honorS2Values = DB::table('setHonorS2')
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->value('HonorS2');

    // Update the 'HONORSKSS2' column in the 'dosen' table with the retrieved values
    DB::table('dosen')
    ->update(['HONORSKSS2' => $honorS2Values]);

    // Return a success response
    return response()->json(['success' => 'HONORSKSS2 updated successfully!']);
}
public function showTunjAkademik()
{
    $tunjakademik = DB::table('tunjakademik')->get();
    $allStatus = DB::table('TblStatusDosen')->select('statusdosen', 'keterangan')->distinct()->get();
    //dd($tunjakademik);
    return view('CMS.tunjakademik', compact('allStatus', 'tunjakademik'));
}

public function simpanTunjAkademik(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $statusdosen = $request->input('statusdosen');
    $jumlah = $request->input('jumlah');

    $jumlah = str_replace('.', ',', $jumlah);

    DB::table('tunjakademik')->insert([
        'ta' => $ta,
        'semester' => $semester,
        'statusdosen' => $statusdosen,
        'jumlah' => $jumlah
    ]);

    return response()->json(['success' => 'Data berhasil ditambahkan!']);
}
public function activateTunjAkademik(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $statusdosen = $request->input('statusdosen');

    // Retrieve the 'jumlah' value from the 'tunjakademik' table based on 'ta', 'semester', and 'statusdosen'
    $tunjakademik = DB::table('tunjakademik')
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->where('statusdosen', $statusdosen)
        ->value('jumlah');

    // Update the 'TUNJAKADEMIK' column in the 'dosen' table where 'statusdosen' equals 'DPK'
    DB::table('dosen')
    ->where('statusdosen', 'like', '%DPK%')
    ->update(['TUNJAKADEMIK' => $tunjakademik]);

    // Return a success response
    return response()->json(['success' => 'TUNJAKADEMIK updated successfully!']);
}
public function showLevelDosen()
{
    $leveldosen = DB::table('leveldosen')->get();
    $allJenjang = DB::table('JENJANGAKADEMIK')->select('itemno', 'jenjangakademik')->distinct()->get();
    $allStatus = DB::table('TblStatusDosen')->select('statusdosen', 'keterangan')->distinct()->get();
    $allJabat = DB::table('TblAkademik')->select('idprimary', 'jabatanakademik')->distinct()->get();
    //dd($tunjakademik);
    return view('CMS.leveldosen', compact('allStatus', 'allJenjang','allJabat','leveldosen'));
}
public function simpanLevelDosen(Request $request)
{
    $level = $request->input('level');
    $jenjangakademik = $request->input('jenjangakademik');
    $statusdosen = $request->input('statusdosen');
    $nidn = $request->input('nidn');
    $jabatanakademik = $request->input('jabatanakademik');
   
    DB::table('leveldosen')->insert([
        'leveldosen' => $level,
        'pendidikan' => $jenjangakademik,
        'statusdosen' => $statusdosen,
        'nidn' => $nidn,
        'jabatanakademik'=>$jabatanakademik
    ]);

    return response()->json(['success' => 'Data berhasil ditambahkan!']);
}
public function showHonorPokok()
{
    $leveldosen = DB::table('honorpokokdosen')->get();
    //dd($tunjakademik);
    return view('CMS.honorpokokdosen', compact('leveldosen'));
}
public function simpanHonorPokok(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $level = $request->input('level');
    $jumlah = $request->input('jumlah');

    $jumlah = str_replace('.', ',', $jumlah);

    DB::table('honorpokokdosen')->insert([
        'ta' => $ta,
        'semester' => $semester,
        'leveldosen' => $level,
        'jumlah' => $jumlah
    ]);

    return response()->json(['success' => 'Data berhasil ditambahkan!']);
}
public function activateHonorPokok(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $leveldosen = $request->input('leveldosen');

    // Retrieve the level criteria from the leveldosen table
    $levelCriteria = DB::table('leveldosen')
        ->where('leveldosen', $leveldosen)
        ->first();

    if (!$levelCriteria) {
        return response()->json(['error' => 'Level dosen tidak ditemukan.']);
    }

    // Check if there are dosen records matching the criteria
    $dosen = DB::table('dosen')
        ->where('pendidikan', $levelCriteria->pendidikan)
        ->where('nidn', $levelCriteria->nidn)
        ->where('statusdosen', $levelCriteria->statusdosen)
        ->where('jabatanakademik', $levelCriteria->jabatanakademik)
        ->exists();

    if (!$dosen) {
        return response()->json(['error' => 'Tidak ada dosen yang sesuai dengan kriteria level dosen ini.']);
    }

    // Retrieve the 'jumlah' value from the 'honorpokokdosen' table based on 'ta', 'semester', and 'leveldosen'
    $honorpokokdosen = DB::table('honorpokokdosen')
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->where('leveldosen', $leveldosen)
        ->value('jumlah');

    if (!$honorpokokdosen) {
        return response()->json(['error' => 'Honor pokok dosen tidak ditemukan.']);
    }

    // Update the 'TUNJAKADEMIK' column in the 'dosen' table where the criteria are met
    DB::table('dosen')
        ->where('pendidikan', $levelCriteria->pendidikan)
        ->where('nidn', $levelCriteria->nidn)
        ->where('statusdosen', $levelCriteria->statusdosen)
        ->where('jabatanakademik', $levelCriteria->jabatanakademik)
        ->update(['honorpokok' => $honorpokokdosen]);

    // Return a success response
    return response()->json(['success' => 'Honor Pokok updated successfully!']);
}

}

