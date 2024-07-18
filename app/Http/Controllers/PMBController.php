<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PMBController extends Controller
{
    public function showMonitoringPMB()
    {
        return view('pmb.cetakmonitoring');
    }

    public function viewMonitoringPMB(Request $request)
    {
        $request->validate([
            'ta' => 'required|integer|min:2020',
        ], [
            'ta' => 'TA minimal dimulai dari tahun 2020.',
        ]);
        $validated = $request->validate([
            'universitas' => 'required|string',
            'ta' => 'required|string',
            'endDate' => 'required|date',
        ]);

        $universitas = $validated['universitas'];
        $ta = $validated['ta'];
        $endDate = $validated['endDate'];
        $previousDay = date('Y-m-d', strtotime($endDate . ' -1 day'));
        $endDateSql = DB::raw("CONVERT(datetime, '$endDate')");
        //dd($endDateSql);
        if ($universitas === 'UQB') {
            $resultFakultas = DB::table('TblmonitoringPMBUQB')
            ->select('idkampus', 'fakultas', 'singkatan', 'ProdiMaster', 'prodi','urut')
            ->whereIn('idkampus', ['11', '16']) // Tambahkan ini
            ->orderBy('urut')
            ->get();
        
        } else {
            $resultFakultas = DB::table('TblmonitoringPMB')
                ->select('idkampus', 'fakultas', 'singkatan', 'ProdiMaster', 'prodi')
                ->orderBy('idPrimary', 'ASC')
                ->get();
        }
    

    // Replace 'KEGURUAN DAN PENDIDIKAN' with 'KEGURUAN DAN ILMU PENDIDIKAN' in the results
    $resultFakultas->transform(function ($item, $key) {
        if ($item->fakultas === 'KEGURUAN DAN PENDIDIKAN') {
            $item->fakultas = 'KEGURUAN DAN ILMU PENDIDIKAN';
        }
        return $item;
    });

    $resultPMBRegistrasi = DB::table('PMBRegistrasi as a')
    ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
    ->select(
        DB::raw('COUNT(a.nopeserta) AS Jumlah'),
        DB::raw("
            COALESCE(
                UPPER(
                    CASE 
                        WHEN a.prodi = 'MATEMATIKA' THEN 'PENDIDIKAN MATEMATIKA'
                        WHEN a.prodi = 'PPKN' THEN 'PENDIDIKAN PPKN'
                        ELSE 
                            CASE 
                                WHEN CHARINDEX(' UQB', a.prodi) > 0 THEN REPLACE(a.prodi, ' UQB', '')
                                ELSE a.prodi
                            END
                    END) + 
                CASE 
                    WHEN a.pindahan = 'S' AND a.universitas = 'UQB' THEN
                        CASE 
                           WHEN a.idkampus = '16' THEN ' 16 (KIP)'
                    WHEN a.idkampus = '15' THEN ' 15 (KIP)'
                    WHEN a.idkampus = '14' THEN ' 14 (KIP)'
                    WHEN a.idkampus = '13' THEN ' 13 (KIP)'
                    WHEN a.idkampus = '12' THEN ' 12 (KIP)'
                    WHEN a.idkampus = '11' THEN ' (KIP)'
                            ELSE ''
                        END
                    WHEN a.pindahan = 'S' THEN ' (KIP)'
                    ELSE ''
                END + 
                CASE 
                    WHEN a.idkampus = '04' THEN ' (04 - LANGKAT)'
                    WHEN a.idkampus = '12' THEN ' 13 (SAMOOSIR)'
                    WHEN a.idkampus = '13' THEN ' 13 (SIDIKALANG)'
                    WHEN a.idkampus = '14' THEN ' 14 (LANGKAT)'
                    WHEN a.idkampus = '15' THEN ' 15 (MARDINDING)'
                    WHEN a.idkampus = '16' THEN ' 16 (DINAS)'
                    ELSE ''
                END, 
                ''
            ) AS prodi
        "),
        'a.idfakultas',
        'b.fakultas',
        'a.idkampus'
    )
    ->whereDate('a.postingdate', '=', $endDateSql)
    ->where('a.Universitas', '=', $universitas)
    ->where('a.ta', '=', $ta)
    ->where('a.posted', '=', 'y')
    ->whereNotNull('a.NOPESERTA')
    ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
    ->get();

        $resultPMBRegistrasiS2 = DB::table('PMBRegistrasiS2 as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
            DB::raw("
                COALESCE(
                    CASE 
                        WHEN a.pindahan = 'S' THEN CONCAT(a.prodi, ' KIP')
                       WHEN a.idkampus = '04' THEN a.prodi + ' (04 - LANGKAT)'
                        ELSE a.prodi 
                    END, 
                    ''
                ) AS prodi"
            ),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPMB', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->whereNotNull('a.NOPESERTA')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
                //dd($resultPMBRegistrasiS2);
        $merged = $resultPMBRegistrasi->merge($resultPMBRegistrasiS2);

        $resultPMBRegistrasiSebelumnya =DB::table('PMBRegistrasi as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
             DB::raw("
            COALESCE(
                UPPER(
                    CASE 
                        WHEN a.prodi = 'MATEMATIKA' THEN 'PENDIDIKAN MATEMATIKA'
                        WHEN a.prodi = 'PPKN' THEN 'PENDIDIKAN PPKN'
                        ELSE 
                            CASE 
                                WHEN CHARINDEX(' UQB', a.prodi) > 0 THEN REPLACE(a.prodi, ' UQB', '')
                                ELSE a.prodi
                            END
                    END) + 
                CASE 
                    WHEN a.pindahan = 'S' AND a.universitas = 'UQB' THEN
                        CASE 
                           WHEN a.idkampus = '16' THEN ' 16 (KIP)'
                    WHEN a.idkampus = '15' THEN ' 15 (KIP)'
                    WHEN a.idkampus = '14' THEN ' 14 (KIP)'
                    WHEN a.idkampus = '13' THEN ' 13 (KIP)'
                    WHEN a.idkampus = '12' THEN ' 12 (KIP)'
                    WHEN a.idkampus = '11' THEN ' (KIP)'
                            ELSE ''
                        END
                    WHEN a.pindahan = 'S' THEN ' (KIP)'
                    ELSE ''
                END + 
                CASE 
                   WHEN a.idkampus = '04' THEN ' (04 - LANGKAT)'
                    WHEN a.idkampus = '12' THEN ' 13 (SAMOOSIR)'
                    WHEN a.idkampus = '13' THEN ' 13 (SIDIKALANG)'
                    WHEN a.idkampus = '14' THEN ' 14 (LANGKAT)'
                    WHEN a.idkampus = '15' THEN ' 15 (MARDINDING)'
                    WHEN a.idkampus = '16' THEN ' 16 (DINAS)'
                    ELSE ''
                END, 
                ''
            ) AS prodi
        "),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.postingdate', '<', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->where('a.posted', '=', 'y')
        ->whereNotNull('a.NOPESERTA')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
        ->get();
        //dd($resultPMBRegistrasiSebelumnya);
        $resultPMBRegistrasiS2Sebelumnya = DB::table('PMBRegistrasiS2 as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
            DB::raw("
            COALESCE(
                CASE 
                    WHEN a.pindahan = 'S' THEN CONCAT(a.prodi, ' KIP')
                   WHEN a.idkampus = '04' THEN a.prodi + ' (04 - LANGKAT)'
                    ELSE a.prodi 
                END, 
                ''
            ) AS prodi"
        ),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPMB', '<', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->whereNotNull('a.NOPESERTA')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();

        $mergedSebelumnya = $resultPMBRegistrasiSebelumnya->merge($resultPMBRegistrasiS2Sebelumnya);
        //dd($mergedSebelumnya);
       
            // Menghitung jumlah berdasarkan prodi untuk data saat ini
            $mergedCount = $merged->groupBy('prodi')->map(function ($item) {
            return $item->sum('Jumlah');
                });

        // Menghitung jumlah berdasarkan prodi untuk data sebelumnya
        $mergedSebelumnyaCount = $mergedSebelumnya->groupBy('prodi')->map(function ($item) {
            return $item->sum('Jumlah');
        });
        //dd($mergedSebelumnyaCount);
        // Menggabungkan data dan menghitung total jumlah untuk setiap prodi
        $totalCounts = $mergedCount->mergeRecursive($mergedSebelumnyaCount)->map(function ($item) {
            // Jika $item bukan array (berarti ini adalah total jumlah langsung)
            if (!is_array($item)) {
                return $item;
            }
        
            // Jika $item adalah array (berarti ini adalah koleksi jumlah per prodi)
            return collect($item)->sum();
        });

        //Daftar Ulang
        $UlangS1= DB::table('PMBRegistrasi as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
             DB::raw("
            COALESCE(
                UPPER(
                    CASE 
                        WHEN a.prodi = 'MATEMATIKA' THEN 'PENDIDIKAN MATEMATIKA'
                        WHEN a.prodi = 'PPKN' THEN 'PENDIDIKAN PPKN'
                        ELSE 
                            CASE 
                                WHEN CHARINDEX(' UQB', a.prodi) > 0 THEN REPLACE(a.prodi, ' UQB', '')
                                ELSE a.prodi
                            END
                    END) + 
                CASE 
                    WHEN a.pindahan = 'S' AND a.universitas = 'UQB' THEN
                        CASE 
                           WHEN a.idkampus = '16' THEN ' 16 (KIP)'
                    WHEN a.idkampus = '15' THEN ' 15 (KIP)'
                    WHEN a.idkampus = '14' THEN ' 14 (KIP)'
                    WHEN a.idkampus = '13' THEN ' 13 (KIP)'
                    WHEN a.idkampus = '12' THEN ' 12 (KIP)'
                    WHEN a.idkampus = '11' THEN ' (KIP)'
                            ELSE ''
                        END
                    WHEN a.pindahan = 'S' THEN ' (KIP)'
                    ELSE ''
                END + 
                CASE 
                   WHEN a.idkampus = '04' THEN ' (04 - LANGKAT)'
                    WHEN a.idkampus = '12' THEN ' 13 (SAMOOSIR)'
                    WHEN a.idkampus = '13' THEN ' 13 (SIDIKALANG)'
                    WHEN a.idkampus = '14' THEN ' 14 (LANGKAT)'
                    WHEN a.idkampus = '15' THEN ' 15 (MARDINDING)'
                    WHEN a.idkampus = '16' THEN ' 16 (DINAS)'
                    ELSE ''
                END, 
                ''
            ) AS prodi
        "),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPUMB', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->where('a.posted','=','y')
        ->whereNotNull('a.NPM')
        ->whereNotNull('a.tgllunaspumb')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
        ->get();
        // /dd($resultPMBRegistrasiSebelumnya);
       
        //dd($UlangS1);
        $UlangS2= DB::table('PMBRegistrasiS2 as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
            DB::raw("
                COALESCE(
                    CASE 
                        WHEN a.pindahan = 'S' THEN CONCAT(a.prodi, ' KIP')
                       WHEN a.idkampus = '04' THEN a.prodi + ' (04 - LANGKAT)'
                        ELSE a.prodi 
                    END, 
                    ''
                ) AS prodi"
            ),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPUMB', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->whereNotNull('a.NPM')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
        $mergedUlang = $UlangS1->merge($UlangS2);
        //dd($mergedUlang);
        $UlangS1Sebelumnya =DB::table('PMBRegistrasi as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
             DB::raw("
            COALESCE(
                UPPER(
                    CASE 
                        WHEN a.prodi = 'MATEMATIKA' THEN 'PENDIDIKAN MATEMATIKA'
                        WHEN a.prodi = 'PPKN' THEN 'PENDIDIKAN PPKN'
                        ELSE 
                            CASE 
                                WHEN CHARINDEX(' UQB', a.prodi) > 0 THEN REPLACE(a.prodi, ' UQB', '')
                                ELSE a.prodi
                            END
                    END) + 
                CASE 
                    WHEN a.pindahan = 'S' AND a.universitas = 'UQB' THEN
                        CASE 
                           WHEN a.idkampus = '16' THEN ' 16 (KIP)'
                    WHEN a.idkampus = '15' THEN ' 15 (KIP)'
                    WHEN a.idkampus = '14' THEN ' 14 (KIP)'
                    WHEN a.idkampus = '13' THEN ' 13 (KIP)'
                    WHEN a.idkampus = '12' THEN ' 12 (KIP)'
                    WHEN a.idkampus = '11' THEN ' (KIP)'
                            ELSE ''
                        END
                    WHEN a.pindahan = 'S' THEN ' (KIP)'
                    ELSE ''
                END + 
                CASE 
                   WHEN a.idkampus = '04' THEN ' (04 - LANGKAT)'
                    WHEN a.idkampus = '12' THEN ' 13 (SAMOOSIR)'
                    WHEN a.idkampus = '13' THEN ' 13 (SIDIKALANG)'
                    WHEN a.idkampus = '14' THEN ' 14 (LANGKAT)'
                    WHEN a.idkampus = '15' THEN ' 15 (MARDINDING)'
                    WHEN a.idkampus = '16' THEN ' 16 (DINAS)'
                    ELSE ''
                END, 
                ''
            ) AS prodi
        "),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPUMB', '<', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->where('a.posted','=','y')
        ->whereNotNull('a.NPM')
        ->whereNotNull('a.tgllunaspumb')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
        ->get();
        //dd($resultPMBRegistrasiSebelumnya);
        
        //dd($UlangS1Sebelumnya);
        $UlangS2Sebelumnya =DB::table('PMBRegistrasiS2 as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
            DB::raw("
                COALESCE(
                    CASE 
                        WHEN a.pindahan = 'S' THEN CONCAT(a.prodi, ' KIP')
                       WHEN a.idkampus = '04' THEN a.prodi + ' (04 - LANGKAT)'
                        ELSE a.prodi 
                    END, 
                    ''
                ) AS prodi"
            ),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPUMB', '<', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->whereNotNull('a.NPM')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
        //dd($UlangS2Sebelumnya);
        $mergedUlangSebelumnya = $UlangS1Sebelumnya->merge($UlangS2Sebelumnya);
        //dd($mergedUlangSebelumnya);

        $mergedUlangCount = $mergedUlang->groupBy('prodi')->map(function ($item) {
            return $item->sum('Jumlah');
                });

        // Menghitung jumlah berdasarkan prodi untuk data sebelumnya
        $mergedUlangSebelumnyaCount = $mergedUlangSebelumnya->groupBy('prodi')->map(function ($item) {
            return $item->sum('Jumlah');
        });
    
        // Menggabungkan data dan menghitung total jumlah ulang untuk setiap prodi
        $totalUlang = $mergedUlangCount->mergeRecursive($mergedUlangSebelumnyaCount)->map(function ($item) {
            // Jika $item bukan array (berarti ini adalah total jumlah langsung)
            if (!is_array($item)) {
                return $item;
            }

            // Jika $item adalah array (berarti ini adalah koleksi jumlah per prodi)
            return collect($item)->sum();
        });

        $TidakUlangS1=DB::table('PMBRegistrasi as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
             DB::raw("
            COALESCE(
                UPPER(
                    CASE 
                        WHEN a.prodi = 'MATEMATIKA' THEN 'PENDIDIKAN MATEMATIKA'
                        WHEN a.prodi = 'PPKN' THEN 'PENDIDIKAN PPKN'
                        ELSE 
                            CASE 
                                WHEN CHARINDEX(' UQB', a.prodi) > 0 THEN REPLACE(a.prodi, ' UQB', '')
                                ELSE a.prodi
                            END
                    END) + 
                CASE 
                    WHEN a.pindahan = 'S' AND a.universitas = 'UQB' THEN
                        CASE 
                           WHEN a.idkampus = '16' THEN ' 16 (KIP)'
                    WHEN a.idkampus = '15' THEN ' 15 (KIP)'
                    WHEN a.idkampus = '14' THEN ' 14 (KIP)'
                    WHEN a.idkampus = '13' THEN ' 13 (KIP)'
                    WHEN a.idkampus = '12' THEN ' 12 (KIP)'
                    WHEN a.idkampus = '11' THEN ' (KIP)'
                            ELSE ''
                        END
                    WHEN a.pindahan = 'S' THEN ' (KIP)'
                    ELSE ''
                END + 
                CASE 
                   WHEN a.idkampus = '04' THEN ' (04 - LANGKAT)'
                    WHEN a.idkampus = '12' THEN ' 13 (SAMOOSIR)'
                    WHEN a.idkampus = '13' THEN ' 13 (SIDIKALANG)'
                    WHEN a.idkampus = '14' THEN ' 14 (LANGKAT)'
                    WHEN a.idkampus = '15' THEN ' 15 (MARDINDING)'
                    WHEN a.idkampus = '16' THEN ' 16 (DINAS)'
                    ELSE ''
                END, 
                ''
            ) AS prodi
        "),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
         ->whereDate('a.postingdate', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->where('a.posted', '=', 'y')
        ->where(function ($query) use ($endDateSql) {
            $query->whereDate('a.tgllunaspumb', '>', $endDateSql)
                  ->orWhereNull('a.tgllunaspumb');
        })
        ->where(function ($query) use ($endDateSql) {
            $query->whereDate('a.tgllunaspumb', '>', $endDateSql)
                  ->orWhereNull('a.NPM');
        })
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
        ->get();
        //dd($TidakUlangS1);
        $TidakUlangS2=DB::table('PMBRegistrasiS2 as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
            DB::raw("
                COALESCE(
                    CASE 
                        WHEN a.pindahan = 'S' THEN CONCAT(a.prodi, ' KIP')
                       WHEN a.idkampus = '04' THEN a.prodi + ' (04 - LANGKAT)'
                        ELSE a.prodi 
                    END, 
                    ''
                ) AS prodi"
            ),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.TGLLUNASPMB', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->where(function ($query) use ($endDateSql) {
        $query->whereDate('a.tgllunaspumb', '>', $endDateSql)
              ->orWhereNull('a.tgllunaspumb');
        })
        ->where(function ($query) use ($endDateSql) {
            $query->whereDate('a.tgllunaspumb', '>', $endDateSql)
                ->orWhereNull('a.NPM');
        })
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
        $mergedTidakUlang = $TidakUlangS1->merge($TidakUlangS2);
        //dd($TidakUlangS2);
        $TidakUlangSebelumnya=DB::table('PMBRegistrasi as a')
        ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
        ->select(
            DB::raw('COUNT(a.nopeserta) AS Jumlah'),
             DB::raw("
            COALESCE(
                UPPER(
                    CASE 
                        WHEN a.prodi = 'MATEMATIKA' THEN 'PENDIDIKAN MATEMATIKA'
                        WHEN a.prodi = 'PPKN' THEN 'PENDIDIKAN PPKN'
                        ELSE 
                            CASE 
                                WHEN CHARINDEX(' UQB', a.prodi) > 0 THEN REPLACE(a.prodi, ' UQB', '')
                                ELSE a.prodi
                            END
                    END) + 
                CASE 
                    WHEN a.pindahan = 'S' AND a.universitas = 'UQB' THEN
                        CASE 
                           WHEN a.idkampus = '16' THEN ' 16 (KIP)'
                    WHEN a.idkampus = '15' THEN ' 15 (KIP)'
                    WHEN a.idkampus = '14' THEN ' 14 (KIP)'
                    WHEN a.idkampus = '13' THEN ' 13 (KIP)'
                    WHEN a.idkampus = '12' THEN ' 12 (KIP)'
                    WHEN a.idkampus = '11' THEN ' (KIP)'
                            ELSE ''
                        END
                    WHEN a.pindahan = 'S' THEN ' (KIP)'
                    ELSE ''
                END + 
                CASE 
                   WHEN a.idkampus = '04' THEN ' (04 - LANGKAT)'
                    WHEN a.idkampus = '12' THEN ' 13 (SAMOOSIR)'
                    WHEN a.idkampus = '13' THEN ' 13 (SIDIKALANG)'
                    WHEN a.idkampus = '14' THEN ' 14 (LANGKAT)'
                    WHEN a.idkampus = '15' THEN ' 15 (MARDINDING)'
                    WHEN a.idkampus = '16' THEN ' 16 (DINAS)'
                    ELSE ''
                END, 
                ''
            ) AS prodi
        "),
            'a.idfakultas',
            'b.fakultas',
            'a.idkampus'
        )
        ->whereDate('a.postingdate', '<', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->where('a.posted', '=', 'y')
        ->where(function ($query) use ($endDateSql) {
            $query->whereDate('a.tgllunaspumb',  '>', [$endDateSql])
                  ->orWhereNull('a.tgllunaspumb');
        })
        ->where(function ($query) use ($endDateSql) {
            $query->whereDate('a.tgllunaspumb',  '>', [$endDateSql])
                  ->orWhereNull('a.NPM');
        })
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
        ->get();
        //dd($TidakUlangSebelumnya);
       $TidakUlangS2Sebelumnya = DB::table('PMBRegistrasiS2 as a')
    ->join('fakultas as b', 'a.idfakultas', '=', 'b.idfakultas')
    ->select(
        DB::raw('COUNT(a.nopeserta) AS Jumlah'),
        DB::raw("
            COALESCE(
                CASE 
                    WHEN a.pindahan = 'S' THEN CONCAT(a.prodi, ' KIP')
                    WHEN a.idkampus = '04' THEN CONCAT(a.prodi, ' (04 - LANGKAT)')
                    ELSE a.prodi 
                END, 
                ''
            ) AS prodi"
        ),
        'a.idfakultas',
        'b.fakultas',
        'a.idkampus'
    )
    ->whereDate('a.TGLLUNASPMB', '<', $endDateSql)
    ->where('a.Universitas', '=', $universitas)
    ->where('a.ta', '=', $ta)
    ->where(function ($query) use ($endDateSql) {
        $query->whereDate('a.tgllunaspumb', '>', $endDateSql)
              ->orWhereNull('a.tgllunaspumb');
    })
    ->where(function ($query) use ($endDateSql) {
        $query->whereDate('a.tgllunaspumb', '>', $endDateSql)
              ->orWhereNull('a.NPM');
    })
    ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
    ->get();

        //dd($TidakUlangS2);
        $mergedTidakUlangSebelumnya = $TidakUlangSebelumnya->merge($TidakUlangS2Sebelumnya);
        //dd($TidakUlangSebelumnya);

        $mergedTidakUlangCount = $mergedTidakUlang->groupBy('prodi')->map(function ($item) {
            return $item->sum('Jumlah');
                });

        // Menghitung jumlah berdasarkan prodi untuk data sebelumnya
        $mergedTidakUlangSebelumnyaCount = $mergedTidakUlangSebelumnya->groupBy('prodi')->map(function ($item) {
            return $item->sum('Jumlah');
        });
    
        // Menggabungkan data dan menghitung total jumlah ulang untuk setiap prodi
        $totalTidak = $mergedTidakUlangCount->mergeRecursive($mergedTidakUlangSebelumnyaCount)->map(function ($item) {
            // Jika $item bukan array (berarti ini adalah total jumlah langsung)
            if (!is_array($item)) {
                return $item;
            }

            // Jika $item adalah array (berarti ini adalah koleksi jumlah per prodi)
            return collect($item)->sum();
        });

        //$tidak = $totalUlang -$totalCounts;
        //dd($tidak);
        $data = [
            'dafarhariini' => $mergedCount,
            'daftarsebelumnya' => $mergedSebelumnyaCount,
            'fakultasData' => $resultFakultas,
            'totalCounts'=>$totalCounts,
            'dafarUlanghariini' => $mergedUlangCount,
            'daftarUlangsebelumnya' => $mergedUlangSebelumnyaCount,
            'totalUlang' => $totalUlang,
            'tidakdaftar' => $mergedTidakUlangCount,
            'tidakUlang' => $mergedTidakUlangSebelumnyaCount,
            'totalTidak' => $totalTidak,
            'endDate'=>$endDate,
        ];
        
        //dd($endDateSql,$totalUlang,$totalTidak);
        return view('pmb.cetakmonitoring', compact('universitas', 'ta', 'endDate', 'previousDay','data' ));
    }


//Grafik PMB

public function showGrafikPMB (Request $request)
{
    return view('pmb.grafikmonitoring');
}

public function viewGrafikPMB(Request $request)
{
    $request->validate([
        'ta_awal' => 'required|integer|min:2020',
        'ta_akhir' => 'required|integer|min:2021',
    ], [
        'ta_awal' => 'TA Awal minimal dimulai dari tahun 2020.',
        'ta_akhir' => 'TA Akhir minimal dimulai dari tahun 2021.',
    ]);
    $validated = $request->validate([
        'universitas' => 'required|string',
        'ta_awal' => 'required|integer',
        'ta_akhir' => 'required|integer',
    ]);

    $universitas = $validated['universitas'];
    $ta_awal = $validated['ta_awal'];
    $ta_akhir = $validated['ta_akhir'];

        $query2020 = "WITH WeeksAndMonths AS (
                SELECT DISTINCT
                    DATEPART(ISO_WEEK, date_seq) AS WeekNumber,
                    DATEPART(MONTH, date_seq) AS MonthNumber
                FROM (
                    SELECT DATEADD(WEEK, number, '2020-01-01') AS date_seq
                    FROM master..spt_values
                    WHERE type = 'P' 
                    AND number <= DATEDIFF(WEEK, '2020-01-01', '2020-12-31')
                ) AS dates
            ),
            WeeklyCounts AS (
                SELECT 
                    COALESCE(COUNT(cm.nopeserta), 0) AS daftar,
                    wm.WeekNumber,
                    wm.MonthNumber
                FROM 
                    WeeksAndMonths wm
                LEFT JOIN 
                    calonmahasiswa cm ON DATEPART(ISO_WEEK, cm.tgldaftar) = wm.WeekNumber
                                    AND DATEPART(MONTH, cm.tgldaftar) = wm.MonthNumber
                                    AND YEAR(cm.tgldaftar) = 2020
                                    AND cm.ta = '2020'
                                    AND cm.universitas = :universitas
                GROUP BY
                    wm.WeekNumber,
                    wm.MonthNumber
            )
            SELECT 
                MonthNumber,
                SUM(daftar) AS daftar
            FROM 
                WeeklyCounts
            GROUP BY 
                MonthNumber
            ORDER BY 
                MonthNumber ";
    $results2020 = DB::select(DB::raw($query2020), ['universitas' => $universitas]);
    //dd($results2020);
    $query2020s = "WITH Months AS (
        SELECT 1 AS MonthNumber
        UNION ALL
        SELECT MonthNumber + 1
        FROM Months
        WHERE MonthNumber < 12
    )
    SELECT Months.MonthNumber, COUNT(mahasiswa.npm) AS daftarulang
    FROM mahasiswa
    RIGHT JOIN Months ON DATEPART(month, mahasiswa.tglmasuk) = Months.MonthNumber
    AND YEAR(mahasiswa.tglmasuk) = '2020'
    AND mahasiswa.ta = '2020'
    AND mahasiswa.universitas = :universitas
    GROUP BY  YEAR(mahasiswa.tglmasuk),Months.MonthNumber
    ORDER BY Months.MonthNumber";
    $results2020s = DB::select(DB::raw($query2020s), ['universitas' => $universitas]);
    //dd($results2020s);
    //dd($results2020s);
    $query2021 = "WITH Months AS (
            SELECT 1 AS MonthNumber
            UNION ALL
            SELECT MonthNumber + 1
            FROM Months
            WHERE MonthNumber < 12
        )
        SELECT Months.MonthNumber, COUNT(calonmahasiswa.nopeserta) AS daftar
        FROM calonmahasiswa
        RIGHT JOIN Months ON DATEPART(month, calonmahasiswa.tgldaftar) = Months.MonthNumber
        AND YEAR(calonmahasiswa.tgldaftar) = '2021'
        AND calonmahasiswa.ta = '2021'
        AND calonmahasiswa.universitas = :universitas
        GROUP BY  YEAR(calonmahasiswa.tgldaftar),Months.MonthNumber
        ORDER BY Months.MonthNumber";
    $results2021 = DB::select(DB::raw($query2021), ['universitas' => $universitas]);
    $query2021s = "WITH WeeksAndMonths AS (
                SELECT DISTINCT
                    DATEPART(ISO_WEEK, date_seq) AS WeekNumber,
                    DATEPART(MONTH, date_seq) AS MonthNumber
                FROM (
                    SELECT DATEADD(WEEK, number, '2021-01-01') AS date_seq
                    FROM master..spt_values
                    WHERE type = 'P' 
                    AND number <= DATEDIFF(WEEK, '2021-01-01', '2021-12-31')
                ) AS dates
            ),
            WeeklyCounts AS (
                SELECT 
                    COALESCE(COUNT(cm.nopeserta), 0) AS daftarulang,
                    wm.WeekNumber,
                    wm.MonthNumber
                FROM 
                    WeeksAndMonths wm
                LEFT JOIN 
                    mahasiswa cm ON DATEPART(ISO_WEEK, cm.tglmasuk) = wm.WeekNumber
                                    AND DATEPART(MONTH, cm.tglmasuk) = wm.MonthNumber
                                    AND YEAR(cm.tglmasuk) = 2021
                                    AND cm.ta = '2021'
                                    AND cm.universitas = :universitas
                GROUP BY
                    wm.WeekNumber,
                    wm.MonthNumber
            )
            SELECT 
                MonthNumber,
                SUM(daftarulang) AS daftarulang
            FROM 
                WeeklyCounts
            GROUP BY 
                MonthNumber
            ORDER BY 
                MonthNumber";
    $results2021s = DB::select(DB::raw($query2021s), ['universitas' => $universitas]);
    $query2022="WITH Months AS (
            SELECT 1 AS MonthNumber
            UNION ALL
            SELECT MonthNumber + 1
            FROM Months
            WHERE MonthNumber < 12
        )
        SELECT 
            Months.MonthNumber, 
            COALESCE(pmb.daftarpmb, 0) + COALESCE(cm.daftarcm, 0) AS daftar
        FROM Months
        LEFT JOIN (
            SELECT 
                DATEPART(MONTH, pmb.tgldaftar) AS MonthNumber, 
                COUNT(pmb.nopeserta) AS daftarpmb
            FROM pmbregistrasi pmb
            WHERE YEAR(pmb.tgldaftar) = '2022'
                AND pmb.ta = '2022'
                AND pmb.universitas = ?
            GROUP BY DATEPART(MONTH, pmb.tgldaftar)
        ) AS pmb ON Months.MonthNumber = pmb.MonthNumber
        LEFT JOIN (
            SELECT 
                DATEPART(MONTH, cm.tgldaftar) AS MonthNumber, 
                COUNT(cm.tgldaftar) AS daftarcm
            FROM calonmahasiswa cm
            WHERE YEAR(cm.tgldaftar) = '2022'
                AND cm.ta = '2022'
                AND cm.universitas = ?
            GROUP BY DATEPART(MONTH, cm.tgldaftar)
        ) AS cm ON Months.MonthNumber = cm.MonthNumber
        ORDER BY Months.MonthNumber";
    $results2022 = DB::select(DB::raw($query2022), [$universitas, $universitas]);
    //dd($results2022);
    $query2022s="WITH Months AS (
        SELECT 1 AS MonthNumber
        UNION ALL
        SELECT MonthNumber + 1
        FROM Months
        WHERE MonthNumber < 12
    )
    SELECT Months.MonthNumber, COUNT(mahasiswa.npm) AS daftarulang
    FROM mahasiswa
    RIGHT JOIN Months ON DATEPART(month, mahasiswa.tglmasuk) = Months.MonthNumber
    AND YEAR(mahasiswa.tglmasuk) = '2022'
    AND mahasiswa.ta = '2022'
    AND mahasiswa.universitas = :universitas
    GROUP BY  YEAR(mahasiswa.tglmasuk),Months.MonthNumber
    ORDER BY Months.MonthNumber";
    $results2022s = DB::select(DB::raw($query2022s), ['universitas'=>  $universitas]);
    $query2023 = "WITH Months AS (
        SELECT 1 AS MonthNumber
        UNION ALL
        SELECT MonthNumber + 1
        FROM Months
        WHERE MonthNumber < 12
    )
    SELECT Months.MonthNumber, COUNT(pmbregistrasi.nopeserta) AS daftar
    FROM pmbregistrasi
    RIGHT JOIN Months ON DATEPART(month, pmbregistrasi.tgldaftar) = Months.MonthNumber
    AND YEAR(pmbregistrasi.tgldaftar) = '2023'
    AND pmbregistrasi.ta = '2023'
    AND pmbregistrasi.universitas = :universitas
    GROUP BY  ta,Months.MonthNumber
    ORDER BY Months.MonthNumber";
    $results2023 = DB::select(DB::raw($query2023), ['universitas' => $universitas]);
    //dd($results2023);
    $query2023s = "WITH Months AS (
        SELECT 1 AS MonthNumber
        UNION ALL
        SELECT MonthNumber + 1
        FROM Months
        WHERE MonthNumber < 12
    )
    SELECT Months.MonthNumber, COUNT(mahasiswa.npm) AS daftarulang
    FROM mahasiswa
    RIGHT JOIN Months ON DATEPART(month, mahasiswa.tglmasuk) = Months.MonthNumber
    AND YEAR(mahasiswa.tglmasuk) = '2023'
    AND mahasiswa.ta = '2023'
    AND mahasiswa.universitas = :universitas
    GROUP BY  YEAR(mahasiswa.tglmasuk),Months.MonthNumber
    ORDER BY Months.MonthNumber";
    $results2023s = DB::select(DB::raw($query2023s), ['universitas' => $universitas]);
    //dd($results2023s);
    $query2024 = "WITH Months AS (
        SELECT 1 AS MonthNumber
        UNION ALL
        SELECT MonthNumber + 1
        FROM Months
        WHERE MonthNumber < 12
    )
    SELECT Months.MonthNumber, COUNT(pmbregistrasi.nopeserta) AS daftar
    FROM pmbregistrasi
    RIGHT JOIN Months ON DATEPART(month, pmbregistrasi.postingdate) = Months.MonthNumber
    AND YEAR(pmbregistrasi.postingdate) = '2024'
    AND pmbregistrasi.ta = '2024'
    AND pmbregistrasi.universitas = :universitas
    GROUP BY  YEAR(pmbregistrasi.postingdate),Months.MonthNumber
    ORDER BY Months.MonthNumber";
    $results2024 = DB::select(DB::raw($query2024), ['universitas' => $universitas]);
    $query2024s = "WITH Months AS (
        SELECT 1 AS MonthNumber
        UNION ALL
        SELECT MonthNumber + 1
        FROM Months
        WHERE MonthNumber < 12
    )
    SELECT Months.MonthNumber, COUNT(pmbregistrasi.npm) AS daftarulang
    FROM pmbregistrasi
    RIGHT JOIN Months ON DATEPART(month, pmbregistrasi.tgllunaspumb) = Months.MonthNumber
    AND YEAR(pmbregistrasi.tgllunaspumb) = '2024'
    AND pmbregistrasi.ta = '2024'
    AND pmbregistrasi.universitas = :universitas
    GROUP BY  YEAR(pmbregistrasi.tgllunaspumb),Months.MonthNumber
    ORDER BY Months.MonthNumber";
    $results2024s = DB::select(DB::raw($query2024s), ['universitas' => $universitas]);
        $query1 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(calonmahasiswa.nopeserta) AS daftar
        FROM calonmahasiswa
        RIGHT JOIN Weeks ON DATEPART(WEEK, calonmahasiswa.tgldaftar) = Weeks.WeekNumber
        AND YEAR(calonmahasiswa.tgldaftar) = '2020'
        AND calonmahasiswa.ta = '2020'
        AND calonmahasiswa.universitas = :universitas
        GROUP BY  YEAR(calonmahasiswa.tgldaftar),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
        ";
        // Execute the query and get the results
        $results1 = DB::select(DB::raw($query1), ['universitas' => $universitas]);
        //dd($results1);
        $query2 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(calonmahasiswa.nopeserta) AS daftar
        FROM calonmahasiswa
        RIGHT JOIN Weeks ON DATEPART(WEEK, calonmahasiswa.tgldaftar) = Weeks.WeekNumber
        AND YEAR(calonmahasiswa.tgldaftar) = '2021'
        AND calonmahasiswa.ta = '2021'
        AND calonmahasiswa.universitas = :universitas
        GROUP BY  YEAR(calonmahasiswa.tgldaftar),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
        ";

        // Execute the query and get the results
        $results2 = DB::select(DB::raw($query2), ['universitas' => $universitas]);
        //dd($results2);
        $query3 = "WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT 
            Weeks.WeekNumber, 
            COALESCE(pmb.daftarpmb, 0) + COALESCE(cm.daftarcm, 0) AS daftar
        FROM Weeks
        LEFT JOIN (
            SELECT 
                DATEPART(WEEK, pmb.tgldaftar) AS WeekNumber, 
                COUNT(pmb.nopeserta) AS daftarpmb
            FROM pmbregistrasi pmb
            WHERE YEAR(pmb.tgldaftar) = '2022'
                AND pmb.ta = '2022'
                AND pmb.universitas = ?
            GROUP BY DATEPART(WEEK, pmb.tgldaftar)
        ) AS pmb ON Weeks.WeekNumber = pmb.WeekNumber
        LEFT JOIN (
            SELECT 
                DATEPART(WEEK, cm.tgldaftar) AS WeekNumber, 
                COUNT(cm.tgldaftar) AS daftarcm
            FROM calonmahasiswa cm
            WHERE YEAR(cm.tgldaftar) = '2022'
                AND cm.ta = '2022'
                AND cm.universitas = ?
            GROUP BY DATEPART(WEEK, cm.tgldaftar)
        ) AS cm ON Weeks.WeekNumber = cm.WeekNumber
        ORDER BY Weeks.WeekNumber";

    $results3 = DB::select(DB::raw($query3), [$universitas, $universitas]);
        //dd($results3);
        $query4 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(pmbregistrasi.nopeserta) AS daftar
        FROM pmbregistrasi
        RIGHT JOIN Weeks ON DATEPART(WEEK, pmbregistrasi.tgldaftar) = Weeks.WeekNumber
        AND YEAR(pmbregistrasi.tgldaftar) = '2023'
        AND pmbregistrasi.ta = '2023'
        AND pmbregistrasi.universitas = :universitas
        GROUP BY  YEAR(pmbregistrasi.tgldaftar),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
        ";

        // Execute the query and get the results
        $results4 = DB::select(DB::raw($query4), ['universitas' => $universitas]);
        $query9 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT 
            Weeks.WeekNumber, 
            COALESCE(pmb.daftarpmb, 0) + COALESCE(cm.daftarcm, 0) AS daftar
        FROM Weeks
        LEFT JOIN (
            SELECT 
                DATEPART(WEEK, pmb.postingdate) AS WeekNumber, 
                COUNT(pmb.nopeserta) AS daftarpmb
            FROM pmbregistrasi pmb
            WHERE YEAR(pmb.postingdate) = '2024'
                AND pmb.ta = '2024'
                and pmb.posted='y'
                AND pmb.universitas = ?
            GROUP BY DATEPART(WEEK, pmb.postingdate)
        ) AS pmb ON Weeks.WeekNumber = pmb.WeekNumber
        LEFT JOIN (
            SELECT 
                DATEPART(WEEK, cm.tgllunaspmb) AS WeekNumber, 
                COUNT(cm.tgllunaspmb) AS daftarcm
            FROM pmbregistrasis2 cm
            WHERE YEAR(cm.tgllunaspmb) = '2024'
                AND cm.ta = '2024'
                AND cm.universitas = ?
            GROUP BY DATEPART(WEEK, cm.tgllunaspmb)
        ) AS cm ON Weeks.WeekNumber = cm.WeekNumber
        ORDER BY Weeks.WeekNumber";

        // Execute the query and get the results
        $results9 = DB::select(DB::raw($query9), [$universitas, $universitas]);
        //dd($results9);
        $query5 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(mahasiswa.npm) AS daftarulang
        FROM mahasiswa
        RIGHT JOIN Weeks ON DATEPART(WEEK, mahasiswa.tglmasuk) = Weeks.WeekNumber
        AND YEAR(mahasiswa.tglmasuk) = '2020'
        AND mahasiswa.ta = '2020'
        AND mahasiswa.universitas = :universitas
        GROUP BY  YEAR(mahasiswa.tglmasuk),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
    ";

                // Execute the query and get the results
                $results5 = DB::select(DB::raw($query5), ['universitas' => $universitas]);
        //dd($results5);
        $query6 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(mahasiswa.npm) AS daftarulang
        FROM mahasiswa
        RIGHT JOIN Weeks ON DATEPART(WEEK, mahasiswa.tglmasuk) = Weeks.WeekNumber
        AND YEAR(mahasiswa.tglmasuk) = '2021'
        AND mahasiswa.ta = '2021'
        AND mahasiswa.universitas = :universitas
        GROUP BY  YEAR(mahasiswa.tglmasuk),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
        ";

        // Execute the query and get the results
        $results6 = DB::select(DB::raw($query6), ['universitas' => $universitas]);
        //dd($results6);

        $query7 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(mahasiswa.npm) AS daftarulang
        FROM mahasiswa
        RIGHT JOIN Weeks ON DATEPART(WEEK, mahasiswa.tglmasuk) = Weeks.WeekNumber
        AND YEAR(mahasiswa.tglmasuk) = '2022'
        AND mahasiswa.ta = '2022'
        AND mahasiswa.universitas = :universitas
        GROUP BY  YEAR(mahasiswa.tglmasuk),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
        ";

        // Execute the query and get the results
        $results7 = DB::select(DB::raw($query7), ['universitas' => $universitas]);
        //dd($results7);
        $query8 = " WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT Weeks.WeekNumber, COUNT(mahasiswa.npm) AS daftarulang
        FROM mahasiswa
        RIGHT JOIN Weeks ON DATEPART(WEEK, mahasiswa.tglmasuk) = Weeks.WeekNumber
        AND YEAR(mahasiswa.tglmasuk) = '2023'
        AND mahasiswa.ta = '2023'
        AND mahasiswa.universitas = :universitas
        GROUP BY  YEAR(mahasiswa.tglmasuk),Weeks.WeekNumber
        ORDER BY Weeks.WeekNumber;
        ";

        // Execute the query and get the results
        $results8 = DB::select(DB::raw($query8), ['universitas' => $universitas]);
        $query10 = "WITH Weeks AS (
            SELECT 1 AS WeekNumber
            UNION ALL
            SELECT WeekNumber + 1
            FROM Weeks
            WHERE WeekNumber < 53
        )
        SELECT 
            Weeks.WeekNumber, 
            COALESCE(pmb.daftarpmb, 0) + COALESCE(cm.daftarcm, 0) AS daftarulang
        FROM Weeks
        LEFT JOIN (
            SELECT 
                DATEPART(WEEK, pmb.tgllunaspumb) AS WeekNumber, 
                COUNT(pmb.nopeserta) AS daftarpmb
            FROM pmbregistrasi pmb
            WHERE YEAR(pmb.tgllunaspumb) = '2024'
                AND pmb.ta = '2024'
                AND pmb.universitas = ?
            GROUP BY DATEPART(WEEK, pmb.tgllunaspumb)
        ) AS pmb ON Weeks.WeekNumber = pmb.WeekNumber
        LEFT JOIN (
            SELECT 
                DATEPART(WEEK, cm.tgllunaspumb) AS WeekNumber, 
                COUNT(cm.tgllunaspumb) AS daftarcm
            FROM pmbregistrasis2 cm
            WHERE YEAR(cm.tgllunaspumb) = '2024'
                AND cm.ta = '2024'
                AND cm.universitas = ?
            GROUP BY DATEPART(WEEK, cm.tgllunaspumb)
        ) AS cm ON Weeks.WeekNumber = cm.WeekNumber
        ORDER BY Weeks.WeekNumber";

      
        // Execute the query and get the results
        $results10 = DB::select(DB::raw($query10), [$universitas, $universitas]);
        //dd($results10);
    // Calculate the differences
        $tidakdaftarulang_2020 = array_map(function($r1, $r5) {
            return [
                'WeekNumber' => $r1->WeekNumber,
                'tidakdaftarulang' => $r1->daftar - $r5->daftarulang
            ];
        }, $results1, $results5);

        $tidakdaftarulang_2021 = array_map(function($r2, $r6) {
            return [
                'WeekNumber' => $r2->WeekNumber,
                'tidakdaftarulang' => $r2->daftar - $r6->daftarulang
            ];
        }, $results2, $results6);

        // Combine the multiple queries for 2022 manually, assuming $results3 is a weekly data array
        $tidakdaftarulang_2022 = array_map(function($r3, $r7) {
            return [
                'WeekNumber' => $r3->WeekNumber,
                'tidakdaftarulang' => $r3->daftar - $r7->daftarulang
            ];
        }, $results3, $results7);


        $tidakdaftarulang_2023 = array_map(function($r4, $r8) {
            return [
                'WeekNumber' => $r4->WeekNumber,
                'tidakdaftarulang' => $r4->daftar - $r8->daftarulang
            ];
        }, $results4, $results8);

        $tidakdaftarulang_2024 = array_map(function($r9, $r10) {
            return [
                'WeekNumber' => $r9->WeekNumber,
                'tidakdaftarulang' => $r9->daftar - $r10->daftarulang
            ];
        }, $results9, $results10);
    // Combine the data into a comprehensive array
    $data = [
        '2020' => [
            'daftar' => $results1,
            'daftarulang' => $results5,
            'tidakdaftarulang' => $tidakdaftarulang_2020,
            'totaldaftar' => array_sum(array_column($results1, 'daftar')),
            'totaldaftarulang' => array_sum(array_column($results5, 'daftarulang')),
            'totaltidakdaftarulang' => array_sum(array_column($tidakdaftarulang_2020, 'tidakdaftarulang')),
        ],
        '2021' => [
            'daftar' => $results2,
            'daftarulang' => $results6,
            'tidakdaftarulang' => $tidakdaftarulang_2021,
            'totaldaftar' => array_sum(array_column($results2, 'daftar')),
            'totaldaftarulang' => array_sum(array_column($results6, 'daftarulang')),
            'totaltidakdaftarulang' => array_sum(array_column($tidakdaftarulang_2021, 'tidakdaftarulang')),
        ],
        '2022' => [
            'daftar' => $results3,
            'daftarulang' => $results7,
            'tidakdaftarulang' => $tidakdaftarulang_2022,
            'totaldaftar' => array_sum(array_column($results3, 'daftar')),
            'totaldaftarulang' => array_sum(array_column($results7, 'daftarulang')),
            'totaltidakdaftarulang' => array_sum(array_column($tidakdaftarulang_2022, 'tidakdaftarulang')),
        ],
        '2023' => [
            'daftar' => $results4,
            'daftarulang' => $results8,
            'tidakdaftarulang' => $tidakdaftarulang_2023,
            'totaldaftar' => array_sum(array_column($results4, 'daftar')),
            'totaldaftarulang' => array_sum(array_column($results8, 'daftarulang')),
            'totaltidakdaftarulang' => array_sum(array_column($tidakdaftarulang_2023, 'tidakdaftarulang')),
        ],
        '2024' => [
            'daftar' => $results9,
            'daftarulang' => $results10,
            'tidakdaftarulang' => $tidakdaftarulang_2024,
            'totaldaftar' => array_sum(array_column($results9, 'daftar')),
            'totaldaftarulang' => array_sum(array_column($results10, 'daftarulang')),
            'totaltidakdaftarulang' => array_sum(array_column($tidakdaftarulang_2024, 'tidakdaftarulang')),
        ]
    ];
    $result = [
        'results2020' => $results2020,
        'results2021 ' => $results2021 ,
        'results2022 ' => $results2022 ,
        'results2023 ' => $results2023 ,
        'results2024 ' => $results2024 ,
        'results2020s ' => $results2020s ,
        'results2021s ' => $results2021s ,
        'results2022s ' => $results2022s ,
        'results2023s ' => $results2023s ,
        'results2024s ' => $results2024s ,
    ];
    $months = [
        'Januari' => range(1, 5),
        'Februari' => range(6, 9),
        'Maret' => range(10, 13),
        'April' => range(14, 18),
        'Mei' => range(19, 22),
        'Juni' => range(23, 26),
        'Juli' => range(27, 31),
        'Agustus' => range(32, 35),
        'September' => range(36, 40),
        'Oktober' => range(41, 44),
        'November' => range(45, 48),
        'Desember' => range(49, 53)
    ];

    // Initialize total arrays for each year
    $totalDaftar = [];
    $totalDaftarUlang = [];
    $totalTidakDaftarUlang = [];
    foreach (range($ta_awal, $ta_akhir) as $year) {
        $totalDaftar[$year] = [];
        $totalDaftarUlang[$year] = [];
        $totalTidakDaftarUlang[$year] = [];
        foreach ($months as $month => $weeks) {
            $totalDaftar[$year][$month] = 0;
            $totalDaftarUlang[$year][$month] = 0;
            $totalTidakDaftarUlang[$year][$month] = 0;
        }
    }

    $showTotalYear = false; // Flag to show yearly total after "Di atas Desember"
    
    foreach ($months as $month => $weeks) {
        foreach ($weeks as $week) {
            foreach (range($ta_awal, $ta_akhir) as $year) {
                $daftar = collect($data[$year]['daftar'])->firstWhere('WeekNumber', $week);
                $daftarUlang = collect($data[$year]['daftarulang'])->firstWhere('WeekNumber', $week);
                $tidakDaftarUlang = collect($data[$year]['tidakdaftarulang'])->firstWhere('WeekNumber', $week);

                // Summing up totals with null check
                $totalDaftar[$year][$month] += optional($daftar)->daftar ?? 0;
                $totalDaftarUlang[$year][$month] += optional($daftarUlang)->daftarulang ?? 0;
                $totalTidakDaftarUlang[$year][$month] += optional($tidakDaftarUlang)['tidakdaftarulang'] ?? 0;

                // Set flag to show yearly total after "Di atas Desember"
                if ($month == 'Desember' && !$showTotalYear) {
                    $showTotalYear = true;
                }
            }
        }
    }
    //dd($totalDaftar,$totalDaftarUlang);
    // Pass both weekly and monthly data to the view
    return view('pmb.grafikmonitoring', compact('universitas', 'ta_awal', 'ta_akhir','result',
'results2020','results2021','results2022','results2023','results2020s','results2021s','results2022s','results2023s',
'results2024s','results2024','months', 'data', 'totalDaftar', 'totalDaftarUlang', 'totalTidakDaftarUlang', 'showTotalYear',
'daftar','daftarUlang','tidakDaftarUlang'));
}
public function showDataCalonMahasiswa()
{
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('pmb.datacalonmahasiswa',compact('prodis'));
}
public function ViewDataCalonMahasiswa(Request $request)
{

    $request->validate([
        'ta' => 'required|integer|min:2023',
        
    ], [
        'ta' => 'TA Awal minimal dimulai dari tahun 2023.',
        
    ]);
    $validated = $request->validate([
        'universitas' => 'required|string',
        'ta' => 'required|integer',
        
    ]);

    $universitas = $validated['universitas'];
    $ta = $validated['ta'];
    
    // Execute the query
    $results = DB::select(
        "SELECT 
        postingdate,NOPESERTA, emailregis,passemail, nama, hp, alamatasal, ASALSMU,kelas,tipekelas,prodi,
        CASE
            WHEN npm IS NOT NULL THEN 'Sudah'
            ELSE 'Belum'
            END AS daftarulang
            FROM pmbregistrasi
            WHERE YEAR(postingdate) = ?
                AND ta = ?
                AND posted = 'y'
                AND universitas = ?
            ORDER BY prodi",
            [$ta, $ta, $universitas]
        );
        //dd($results);
    $prodis = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('pmb.datacalonmahasiswa',compact('results','ta','universitas'));

}
public function showDataCalonMahasiswaS2()
{
    return view('pmb.datacalonmahasiswas2');
}
public function ViewDataCalonMahasiswaS2(Request $request)
{

    $request->validate([
        'ta' => 'required|integer|min:2024',
        
    ], [
        'ta' => 'TA Awal minimal dimulai dari tahun 2024.',
        
    ]);
    $validated = $request->validate([
        'universitas' => 'required|string',
        'ta' => 'required|integer',
        
    ]);

    $universitas = $validated['universitas'];
    $ta = $validated['ta'];
    
    // Execute the query
    $results = DB::select(
        "SELECT NOPESERTA, emailregis, nama, hp, alamatasal
        FROM pmbregistrasis2
        WHERE YEAR(tgllunaspmb) = ?
        AND ta = ?
        and nopeserta is not null
        AND universitas = ?
        order by ta",
        [$ta, $ta, $universitas]
    );

    return view('pmb.datacalonmahasiswas2',compact('results','ta','universitas'));

}
}