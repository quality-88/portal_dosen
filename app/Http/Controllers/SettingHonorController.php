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
    $allLevel = DB::table('leveldosen')->select('leveldosen', 'jabatanakademik')->distinct()->get();
    $leveldosen = DB::table('honorpokokdosen')->get();
    //dd($tunjakademik);
    return view('CMS.honorpokokdosen', compact('leveldosen','allLevel'));
}
public function simpanHonorPokok(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $level = $request->input('level');
    $jumlah = $request->input('jumlah');
    $jabatanakademik = $request->input('jabatanakademik');
    $jumlah = str_replace('.', ',', $jumlah);

    DB::table('honorpokokdosen')->insert([
        'ta' => $ta,
        'semester' => $semester,
        'leveldosen' => $level,
        'jumlah' => $jumlah,
        'jabatanakademik' => $jabatanakademik
    ]);

    return response()->json(['success' => 'Data berhasil ditambahkan!']);
}
public function activateHonorPokok(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $leveldosen = $request->input('leveldosen');
    $jabatanakademik = $request->input('jabatanakademik');
    // Retrieve the level criteria from the leveldosen table
    $levelCriteria = DB::table('leveldosen')
        ->where('leveldosen', $leveldosen)
        ->where('jabatanakademik',$jabatanakademik)
        ->first();

    if (!$levelCriteria) {
        return response()->json(['error' => 'Level dosen tidak ditemukan.']);
    }

    // Check if there are dosen records matching the criteria
    $dosen = DB::table('dosen')
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
public function showSettingHonorS1()
    {
        // Ambil data jabatan dari database
        $sethonor = DB::table('setHonorS1')->get();
        foreach ($sethonor as $item) {
            // Jika 'honors2' tidak kosong
            if (!is_null($item->HonorS1)) {
                $item->HonorS1 = number_format($item->HonorS1, 0, ',', '.');
            }
        }
        // Kirimkan data jabatan ke view
        return view('CMS.honorsksdosens1', compact('sethonor')); 
    } 
    public function simpanSettingHonorS1(Request $request)
    {
        $ta = $request->input('ta');
        $semester = $request->input('semester');
        $honors1 = $request->input('honors1');
    
        $honors1 = str_replace('.', ',', $honors1);
    
        // Insert data into the database
        DB::table('setHonorS1')->insert([
            'ta' => $ta,
            'semester' => $semester,
            'honors1' => $honors1
        ]);
    
        // Send success message
        return response()->json(['success' => 'Data berhasil ditambahkan!']);
    }
    public function activateHonorS1(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');

    // Retrieve the 'HonorS2' values from the 'setHonorS2' table based on 'ta' and 'semester'
    $honorS1Values = DB::table('setHonorS1')
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->value('HonorS1');

    // Update the 'HONORSKSS2' column in the 'dosen' table with the retrieved values
    DB::table('dosen')
    ->update(['HONORSKS' => $honorS1Values]);

    // Return a success response
    return response()->json(['success' => 'HONORSKS updated successfully!']);
}
public function showTunjanganDoktor()
    {
        // Ambil data jabatan dari database
        $tunjdoktor = DB::table('tunjdoktor')->get();
        foreach ($tunjdoktor as $item) {
            // Jika 'honors2' tidak kosong
            if (!is_null($item->jumlah)) {
                $item->jumlah = number_format($item->jumlah, 0, ',', '.');
            }
        }
        $allJenis = DB::table('JenisDosen')->select('idprimary', 'nama as statusdosen')->distinct()->get();
        $allJenjang = DB::table('JENJANGAKADEMIK')->select('itemno', 'jenjangakademik as pendidikan')->distinct()->get();
        // Kirimkan data jabatan ke view
        return view('CMS.tunjungandoktor', compact('tunjdoktor','allJenis','allJenjang')); 
    } 
    public function simpanTunganDoktor(Request $request)
    {
        $ta = $request->input('ta');
        $semester = $request->input('semester');
        $jumlah = $request->input('jumlah');
        $statusdosen = $request->input('statusdosen');
        $pendidikan = $request->input('pendidikan');
    
        // Insert data into the database
        DB::table('tunjdoktor')->insert([
            'ta' => $ta,
            'semester' => $semester,
            'jumlah' => $jumlah,
            'statusdosen' => $statusdosen,
            'pendidikan' => $pendidikan
        ]);
    
        // Send success message
        return response()->json(['success' => 'Data berhasil ditambahkan!']);
    }
    public function activateTunganDoktor(Request $request)
{
    $ta = $request->input('ta');
    $semester = $request->input('semester');
    $statusdosen = $request->input('statusdosen');
    $pendidikan = $request->input('pendidikan');
    
    // Retrieve the 'jumlah' values from the 'tunjdoktor' table based on 'ta', 'semester', 'statusdosen', and 'pendidikan'
    $tunjdoktor = DB::table('tunjdoktor')
        ->where('ta', $ta)
        ->where('semester', $semester)
        ->where('statusdosen', $statusdosen)
        ->where('pendidikan', $pendidikan) // Perbaiki typo disini
        ->value('jumlah');
    
        if($tunjdoktor) {
            // Convert the amount from "500.000" format to numeric for database insertion
            $tunjdoktor = str_replace('.', '', $tunjdoktor);
    
            // Update the 'tunjdoktor' column in the 'dosen' table with the retrieved value
            DB::table('dosen')->update(['tunjdoktor' => $tunjdoktor]);
    
            // Return a success response
            return response()->json(['success' => 'Tunjangan Doktor updated successfully!']);
        } else {
            // Jika tidak ada data yang ditemukan, kembalikan pesan error
            return response()->json(['error' => 'Tunjangan Doktor not found!']);
        }
    }
}


