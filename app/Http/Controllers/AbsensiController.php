<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AbsensiController extends Controller
{
    public function formRekapAbsensiDosen(Request $request)
{
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get();
    return view('absensi.rekapabsensidosen', compact('allIdKampus','allProdi'));
}
public function rekapAbsensiDosen(Request $request)
{
    $request->validate([
        'ta' => 'required|integer|min:2020',
    ], [
        'ta' => 'TA minimal dimulai dari tahun 2020.',
    ]);
    
    $validated = $request->validate([
        'ta' => 'required|string',
        'endDate' => 'required|date',
        'startDate' => 'required|date',
        'semester' => 'required|string',
        'prodi' => 'required|string',
        'idkampus' => 'required|string',
    ]);

    $ta = $validated['ta'];
    $endDate = $validated['endDate'];
    $startDate = $validated['startDate'];
    $semester = $validated['semester'];
    $prodi = $validated['prodi'];
    $idkampus = $validated['idkampus'];
    $lokasi = $request->input('lokasi');
    session(['ta' => $ta]);
    session(['semester' => $semester]);
    session(['idkampus' => $idkampus]);
    session(['prodi' => $prodi]);
    session(['lokasi' => $lokasi]);
    // Fetch attendance records for the selected criteria
    $attendances = DB::table('attdosen')
                ->select('attdosen.iddosen', 'attdosen.idmk', 'attdosen.idkampus', 'attdosen.pertemuan', 
                'attdosen.tgl', 'attdosen.kelas', 'dosen.nama as nama', 'matakuliah.matakuliah as matakuliah')
                ->join('dosen', 'dosen.iddosen', '=', 'attdosen.iddosen')
                ->join('matakuliah', 'matakuliah.idmk', '=', 'attdosen.idmk')
                ->where('attdosen.ta', $ta)
                ->where('attdosen.semester', $semester)
                ->where('attdosen.prodi', $prodi)
                ->where('attdosen.idkampus', $idkampus)
                ->whereBetween('attdosen.tgl', [$startDate, $endDate])
                ->orderBy('attdosen.iddosen')
                ->orderBy('attdosen.pertemuan')
                ->orderBy('attdosen.tgl')
                ->get();


    // Grouping attendance records by iddosen
    $groupedAttendances = $attendances->groupBy('iddosen','idmk');

    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get();

    // Prepare data for PDF generation
    // Prepare data for PDF generation
$pdfData = [];
foreach ($groupedAttendances as $iddosen => $attendances) {
    $pdfData[$iddosen] = [
        'iddosen' => $iddosen,
        'nama' => $attendances->first()->nama, // Ambil nama dosen dari data pertama
        'attendances' => $attendances,
    ];
}


    return view('absensi.rekapabsensidosen', compact('ta', 'endDate', 'startDate', 'pdfData', 'allIdKampus', 'allProdi',
     'idkampus', 'prodi', 'semester','lokasi'));
}
}