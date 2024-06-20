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
}
