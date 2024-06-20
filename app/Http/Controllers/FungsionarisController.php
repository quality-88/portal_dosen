<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FungsionarisController extends Controller
{
    public function findDosen(Request $request)
    {
        $term = $request->input('term');
    
        $dosenResults = DB::table('allsmusersall')
            ->select('allsmusersall.id', 'allsmusersall.nama', 'allsmusersall.divisi', 'dosen.nidnntbdos','dosen.nip')
            ->join('dosen', 'allsmusersall.id', '=', 'dosen.iddosen')
            ->where('allsmusersall.nama', 'like', '%' . $term . '%')
            ->get();
    
        return response()->json($dosenResults);
    }
    
    public function formKaprodi(Request $request)
    {
        $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
        $allProdi = DB::table('prodifakultas')->select('idfakultas','idprodi', 'prodi')->distinct()->get();
        $allJabatan = DB::table('jabatanfungsi')->select('idprimary','jabatanfungsi')->distinct()->get();
        // Pass the data to the view
        return view('fungsionaris.fungsionaris', compact('allIdKampus','allProdi','allJabatan'));
    }
    public function insertJabatan(Request $request)
    {
        $data = $request->all();
        $lastItemNo = DB::table('Hak_Akses_Global')
            ->where('id_dosen_pejabat', $data['id'])
            ->max('id_pejabat');
        $id_pejabat = $lastItemNo ? $lastItemNo + 1 : 1;
        
        // Simpan data ke dalam tabel Hak_Akses_Global
        DB::table('Hak_Akses_Global')->insertGetId([
            'id_pejabat' => $id_pejabat,
            'id_kampus' => $data['idkampus'],
            'alamat_kampus' => $data['lokasi'],
            'prodi' => $data['prodi'],
            'id_prodi' => $data['idprodi'],
            'id_fakultas' => $data['id_fakultas'],
            'fakultas' => $data['fakultas'],
            'id_dosen_pejabat' => $data['id'],
            'nama_gelar' => $data['nama'],
            'nidn' => $data['nidn'],
            'nip' => $data['nip'],
            'jabatan' => $data['jabatanfungsi'],
            'priode_awal' => $data['startDate'],
            'priode_akhir' => $data['endDate'],
            'status' => $data['status'],
        ]);
        
        // Check jika jabatan adalah "Dekan" atau dimulai dengan "Dekan"
        if (strpos($data['jabatanfungsi'], 'Dekan') === 0) {
            // Lakukan update pada tabel fakultas
            DB::table('fakultas')
                ->where('idfakultas', $data['id_fakultas'])
                ->where('fakultas', $data['fakultas'])
                ->update([
                    'iddekan' => $data['id'],
                    'Kajur' => $data['nama'], // id_dekan diupdate dengan id dari dekan yang baru
                    'tglmulai' => $data['startDate'], // tgl_mulai diupdate dengan tgl mulai yang baru
                    'tglselesai' => $data['endDate'], // tgl_selesai diupdate dengan tgl selesai yang baru
                ]);
        }
        if (strpos($data['jabatanfungsi'], 'Kaprodi') === 0) {
            // Lakukan update pada tabel fakultas
            DB::table('prodifakultas')
                ->where('idprodi', $data['id_prodi'])
                ->where('prodi', $data['prodi'])
                ->update([
                    'idkajur' => $data['id'],
                    'idDosen' => $data['id'],
                    'Kajur' => $data['nama'], // id_dekan diupdate dengan id dari dekan yang baru
                    'tglmulai' => $data['startDate'], // tgl_mulai diupdate dengan tgl mulai yang baru
                    'tglselesai' => $data['endDate'], // tgl_selesai diupdate dengan tgl selesai yang baru
                ]);
        }
        // Redirect ke viewfungsionaris.blade.php dengan mengirimkan data yang diperlukan
        return response()->json(['success' => 'Data berhasil ditambahkan!']);
    }
    public function showViewJabatan()
    {
        $hakAkses = DB::select('SELECT * FROM Hak_Akses_Global');
        return view('fungsionaris.viewfungsionaris', ['hakAkses' => $hakAkses]);
    }
    }

