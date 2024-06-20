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
                ->select('idkampus', 'fakultas', 'singkatan', 'ProdiMaster', 'prodi')
                ->orderBy('idPrimary', 'ASC')
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
        ->where('a.TGLLUNASPMB', '=', $endDateSql)
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
        ->where('a.postingdate', '<', $endDateSql)
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
        ->where('a.TGLLUNASPMB', '<', $endDateSql)
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
        ->whereNotNull('a.NPM')
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
        ->whereDate('a.TGLDAFTARULANG', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->whereNotNull('a.NPM')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
        $mergedUlang = $UlangS1->merge($UlangS2);

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
        ->whereNotNull('a.NPM')
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
        ->whereDate('a.TGLLUNASPMB', '<', $endDateSql)
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
        ->whereNull('a.NPM')
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
        ->where('a.TGLLUNASPMB', '=', $endDateSql)
        ->where('a.Universitas', '=', $universitas)
        ->where('a.ta', '=', $ta)
        ->whereNotNull('a.NPM')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
        $mergedTidakUlang = $TidakUlangS1->merge($TidakUlangS2);
        //dd($mergedTidakUlang);
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
        ->whereNull('a.NPM')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan','a.universitas')
        ->get();
        $TidakUlangS2Sebelumnya=DB::table('PMBRegistrasiS2 as a')
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
        ->whereNull('a.NPM')
        ->groupBy('a.idkampus', 'a.idfakultas', 'b.fakultas', 'a.prodi', 'a.pindahan')
        ->get();
        //dd($totalUlang,$mergedUlangSebelumnyaCount,$mergedUlangCount,$mergedUlangSebelumnya);
        $mergedTidakUlangSebelumnya = $TidakUlangSebelumnya->merge($TidakUlangS2Sebelumnya);
        //dd($mergedTidakUlangSebelumnya);

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
        
        //dd($data);
        return view('pmb.cetakmonitoring', compact('universitas', 'ta', 'endDate', 'previousDay','data' ));
    }


//Grafik PMB

public function showGrafikPMB (Request $request)
{
    return view('pmb.grafikmonitoring');
}

public function viewGrafikPMB(Request $request)
{
    $validated = $request->validate([
        'universitas' => 'required|string',
        'ta_awal' => 'required|string',
        'ta_akhir' => 'required|string',
    ]);

    $universitas = $validated['universitas'];
    $ta_awal = $validated['ta_awal'];
    $ta_akhir = $validated['ta_akhir'];

    $bulan = collect(range(1, 12))->map(function($month) {
        return (object)['bulan' => $month];
    });

    $registrasi = DB::table('PMBRegistrasi')
    ->selectRaw('ta, COUNT(npm) AS pendaftar, MONTH(tgldaftar) AS bulan')
    ->where('universitas', $universitas)
    ->whereBetween('ta', [$ta_awal, $ta_akhir]) // pastikan rentang tahun ta sesuai
    ->whereYear('tgldaftar', '>=', $ta_awal) // tambahkan batasan tahun untuk tanggal daftar
    ->whereYear('tgldaftar', '<=', $ta_akhir) 
    ->groupBy('ta', DB::raw('MONTH(tgldaftar)'))
    ->get();


    $totalcalon = DB::table('PMBRegistrasi')
        ->selectRaw('ta, COUNT(nopeserta) AS calon, MONTH(tgldaftar) AS bulan')
        ->whereBetween('ta', [$ta_awal, $ta_akhir])
        ->whereYear('tgldaftar', '>=', $ta_awal) // tambahkan batasan tahun untuk tanggal daftar
        ->whereYear('tgldaftar', '<=', $ta_akhir) 
        ->where('universitas', $universitas)
        ->groupBy('ta', DB::raw('MONTH(tgldaftar)'))
        ->get();
//dd($registrasi);
    $distinctTA = $registrasi->pluck('ta')->unique();
    $ta_bulan_combinations = collect($distinctTA)->crossJoin($bulan);

    $result = $ta_bulan_combinations->map(function ($item) use ($registrasi) {
        $ta = $item[0];
        $bulan = $item[1]->bulan;
        $data = $registrasi->first(function ($value) use ($ta, $bulan) {
            return $value->ta == $ta && $value->bulan == $bulan;
        });

        return (object)[
            'ta' => $ta,
            'bulan' => $bulan,
            'pendaftar' => $data ? $data->pendaftar : 0
        ];
    })->sortBy(['ta', 'bulan']);

    $calonResult = $ta_bulan_combinations->map(function ($item) use ($totalcalon) {
        $ta = $item[0];
        $bulan = $item[1]->bulan;
        $data = $totalcalon->first(function ($value) use ($ta, $bulan) {
            return $value->ta == $ta && $value->bulan == $bulan;
        });

        return (object)[
            'ta' => $ta,
            'bulan' => $bulan,
            'calon' => $data ? $data->calon : 0
        ];
    })->sortBy(['ta', 'bulan']);

    $resultArray = $result->toArray();
    $calonArray = $calonResult->toArray();

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
        //dd($results4);
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

    // Helper function to map weeks to months
    function weekToMonth($week) {
        $week_to_month = [
            1 => 1, 2 => 1, 3 => 1, 4 => 1,
            5 => 2, 6 => 2, 7 => 2, 8 => 2, 9 => 2,
            10 => 3, 11 => 3, 12 => 3, 13 => 3,
            14 => 4, 15 => 4, 16 => 4, 17 => 4,
            18 => 5, 19 => 5, 20 => 5, 21 => 5, 22 => 5,
            23 => 6, 24 => 6, 25 => 6, 26 => 6,
            27 => 7, 28 => 7, 29 => 7, 30 => 7, 31 => 7,
            32 => 8, 33 => 8, 34 => 8, 35 => 8,
            36 => 9, 37 => 9, 38 => 9, 39 => 9, 40 => 9,
            41 => 10, 42 => 10, 43 => 10, 44 => 10,
            45 => 11, 46 => 11, 47 => 11, 48 => 11,
            49 => 12, 50 => 12, 51 => 12, 52 => 12, 53 => 12
        ];
        return $week_to_month[$week] ?? 12;
    }

    // Your existing queries...

    $monthlyData = [
        '2020' => $this->aggregateMonthlyData($results1, $results5),
        '2021' => $this->aggregateMonthlyData($results2, $results6),
        '2022' => $this->aggregateMonthlyData($results3, $results7),
        '2023' => $this->aggregateMonthlyData($results4, $results8),
    ];

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
        ]
    ];

    // Pass both weekly and monthly data to the view
    return view('pmb.grafikmonitoring', compact('universitas', 'ta_awal', 'ta_akhir', 'data', 'monthlyData'));
}
private function aggregateMonthlyData($weeklyDaftar, $weeklyDaftarUlang)
{
    $monthlyDaftar = [];
    $monthlyDaftarUlang = [];
    foreach ($weeklyDaftar as $weekData) {
        $month = date('n', strtotime("2020W" . str_pad($weekData->WeekNumber, 2, '0', STR_PAD_LEFT) . "1"));
        if (!isset($monthlyDaftar[$month])) {
            $monthlyDaftar[$month] = 0;
        }
        $monthlyDaftar[$month] += $weekData->daftar;
    }
    foreach ($weeklyDaftarUlang as $weekData) {
        $month = date('n', strtotime("2020W" . str_pad($weekData->WeekNumber, 2, '0', STR_PAD_LEFT) . "1"));
        if (!isset($monthlyDaftarUlang[$month])) {
            $monthlyDaftarUlang[$month] = 0;
        }
        $monthlyDaftarUlang[$month] += $weekData->daftarulang;
    }
    return [
        'daftar' => $monthlyDaftar,
        'daftarulang' => $monthlyDaftarUlang,
        'tidakdaftarulang' => array_map(function($month) use ($monthlyDaftar, $monthlyDaftarUlang) {
            return $monthlyDaftar[$month] - $monthlyDaftarUlang[$month];
        }, array_keys($monthlyDaftar))
    ];
}
}


