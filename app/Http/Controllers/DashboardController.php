<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
class DashboardController extends Controller
{
    public function adminDashboard()
{
    // Mendapatkan waktu login saat ini
    $currentDate = Carbon::now();

    // Menentukan tahun akademik (ta) dan semester
    $year = $currentDate->year;
    $month = $currentDate->month;

    if ($month >= 10 || $month <= 2) {
        $ta=$year;
        $semester = 1;
    } else {
        $ta = $year-1;
        $semester = 2;
    }

    $cacheKey = "dashboard_data_ta_{$ta}_semester_{$semester}";
    $dashboardData = Cache::remember($cacheKey, 60, function() use ($ta, $semester) {
        $countDistinctNpm = DB::table('krs as a')
            ->join('mahasiswa as b', 'b.npm', '=', 'a.npm')
            ->where('a.ta', $ta)
            ->where('a.semester', $semester)
            ->whereNull('b.TGLMEJAHIJAU')
            ->distinct()
            ->count('a.npm');

        $countNpm = DB::table('mahasiswa')
            ->whereYear('tgllulusmh', $ta)
            ->whereNotNull('TGLMEJAHIJAU')
            ->count('npm');

        $countDistinctNpmByProdi = DB::table('krs as a')
            ->join('mahasiswa as b', 'b.npm', '=', 'a.npm')
            ->where('a.ta', $ta)
            ->where('a.semester', $semester)
            ->whereNull('b.TGLMEJAHIJAU')
            ->select(DB::raw('COUNT(DISTINCT a.npm) as count'), 'a.prodi')
            ->groupBy('a.prodi')
            ->get();

        $countNpm2024 = DB::table('mahasiswa')
            ->whereYear('tglmasuk', 2024)
            ->where ('ta',2024)
            ->count('npm');

        $dosen = DB::table('attdosen as a')
            ->join('dosen as b', 'b.iddosen', '=', 'a.iddosen')
            ->where('a.ta', $ta)
            ->where('a.semester', $semester)
            ->distinct()
            ->count('a.iddosen');

        $lulusanprodi = DB::table('krs as a')
            ->join('mahasiswa as b', 'b.npm', '=', 'a.npm')
            ->where('a.ta', $ta)
            ->where('a.semester', $semester)
            ->whereNotNull('b.TGLMEJAHIJAU')
            ->select(DB::raw('COUNT(DISTINCT a.npm) as count'), 'a.prodi')
            ->groupBy('a.prodi')
            ->get();

        // Convert $countDistinctNpmByProdi to arrays for labels and data
        $prodiLabels = $countDistinctNpmByProdi->pluck('prodi')->toArray();
        $prodiCounts = $countDistinctNpmByProdi->pluck('count')->toArray();

        $lulusanProdiLabels = $lulusanprodi->pluck('prodi')->toArray();
        $lulusanProdiCounts = $lulusanprodi->pluck('count')->toArray();

        return compact(
            'countDistinctNpm', 
            'countNpm', 
            'countDistinctNpmByProdi', 
            'countNpm2024', 
            'dosen', 
            'prodiLabels', 
            'prodiCounts', 
            'lulusanProdiLabels', 
            'lulusanProdiCounts'
        );
    });

    return view('admin.index', $dashboardData + compact('ta', 'semester'));

}
}