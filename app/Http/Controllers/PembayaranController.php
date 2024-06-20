<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function bayar(Request $request)
    {           
        $TA = session('TA');
        $Semester = session('Semester');
                // Validasi request
                $request->validate([
                    'data' => 'required|array', // Pastikan data yang diterima adalah array
                    // Tambahkan validasi lainnya sesuai kebutuhan Anda
                ]);
        
                // Ambil data dari request
                $data = $request->input('data');
                
                foreach ($data as $row) {
                    // Cek apakah data sudah ada di database
                    $existingData = DB::table('BayarSKSHonor')
                        ->where('nama_dosen', $row['nama_dosen'])
                        ->where('id_dosen', $row['id_dosen'])
                        ->where('tanggal', $row['tanggal'])
                        ->first();
            
                    // Jika data sudah ada, berikan respons bahwa pembayaran sudah dilakukan
                    if ($existingData) {
                        return response()->
                        json(['message' => 'Pembayaran untuk dosen ini pada tanggal tersebut sudah dilakukan sebelumnya'], 422);
                    }
            

                // Lakukan penyimpanan data ke dalam database
                foreach ($data as $row) {
                    DB::table('BayarSKSHonor')->insert([
                        'nama_dosen' => $row['nama_dosen'],
                        'id_dosen' => $row['id_dosen'],
                        'tanggal' => $row['tanggal'],
                        'id_mk' => $row['id_mk'],
                        'matakuliah' => $row['matakuliah'],
                        'sks' => $row['sks'],
                        'masuk' => $row['masuk'],
                        'keluar' => $row['keluar'],
                        'kelas' => $row['kelas'],
                        'jumlah_mahasiswa' => $row['jumlah_mahasiswa'],
                        'pertemuan_ke' => $row['pertemuan_ke'],
                        'honor' => $row['honor'],
                        'keterangan' => $row['keterangan'],
                        'TA' => $TA, // Menyimpan nilai TA
                        'Semester' => $Semester, // Menyimpan nilai Semester
                    ]);
                }
        // Berikan respons yang sesuai
        return response()->json(['message' => 'Pembayaran berhasil disimpan'], 200);
    }
}
public function bayarSemua(Request $request)
{
    $TA = session('TA');
$Semester = session('Semester');
    // Validasi request
    $request->validate([
        'data' => 'required|array', // Pastikan data yang diterima adalah array
        // Tambahkan validasi lainnya sesuai kebutuhan Anda
    ]);

    // Ambil data dari request
    $data = $request->input('data');

    // Filter data untuk menghapus baris yang tidak valid (misalnya, yang memiliki 'nama_dosen' berupa 'Total Honor')
    $filteredData = array_filter($data, function ($row) {
        return !preg_match('/^Total Honor/', $row['nama_dosen']); // Menghapus baris yang memiliki 'nama_dosen' berupa 'Total Honor'
    });

    // Cek apakah semua entri sudah dibayar sebelumnya
    $allAlreadyPaid = true;
    foreach ($filteredData as $row) {
        $existingPayment = DB::table('BayarSKSHonor')
            ->where('id_dosen', $row['id_dosen'])
            ->where('tanggal', $row['tanggal'])
            ->exists();

        if (!$existingPayment) {
            $allAlreadyPaid = false;
            break;
        }
    }

    // Jika semua entri sudah dibayar sebelumnya, kembalikan notifikasi
    if ($allAlreadyPaid) {
        return response()->json(['message' => 'Semua pembayaran telah dilakukan sebelumnya'], 422);
    }

    // Lakukan penyimpanan data ke dalam database
    foreach ($filteredData as $row) {
        // Cek apakah pembayaran telah dilakukan sebelumnya
        $existingPayment = DB::table('BayarSKSHonor')
            ->where('id_dosen', $row['id_dosen'])
            ->where('tanggal', $row['tanggal'])
            ->exists();

        // Jika pembayaran belum dilakukan sebelumnya, simpan data pembayaran
        if (!$existingPayment) {
            DB::table('BayarSKSHonor')->insert([
                'nama_dosen' => $row['nama_dosen'],
                'id_dosen' => $row['id_dosen'],
                'tanggal' => $row['tanggal'],
                'id_mk' => $row['id_mk'],
                'matakuliah' => $row['matakuliah'],
                'sks' => $row['sks'],
                'masuk' => $row['masuk'],
                'keluar' => $row['keluar'],
                'kelas' => $row['kelas'],
                'jumlah_mahasiswa' => $row['jumlah_mahasiswa'],
                'pertemuan_ke' => $row['pertemuan_ke'],
                'honor' => $row['honor'],
                'keterangan' => $row['keterangan'],
                'TA' => $TA, // Menyimpan nilai TA
                'Semester' => $Semester, // Menyimpan nilai Semester
            ]);
        }
    }

    // Berikan respons yang sesuai
    return response()->json(['message' => 'Pembayaran berhasil disimpan'], 200);
}
public function showRekapHonorDosen()
{
    return view('backend.type.rekaphonordosen');
}
public function rekapHonorDosen(Request $request)
{
    // Mendapatkan input tahun dan bulan dari form
    $tahun = $request->tahun;
    $bulan = $request->bulan;
    session(['tahun' => $tahun]);
    session(['bulan' => $bulan]);
    // Mengonversi bulan menjadi format dua digit
    $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

    // Menentukan tanggal awal dan akhir berdasarkan tahun dan bulan yang dipilih
    $tanggalAwal = $tahun . '-' . $bulan . '-01';
    $tanggalAkhir = $tahun . '-' . $bulan . '-' . cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

    // Gunakan $tanggalAwal dan $tanggalAkhir dalam query untuk mencari data yang sesuai
    if ($tahun != 2024) {
        $totalHonor = DB::table('TblSementaraHonorDosen')
    ->select('idDosen as id_dosen', 'NamaDosen as nama_dosen', DB::raw('SUM(CAST(TotalHonor AS DECIMAL(10, 3))) / 1000 AS TotalHonor'))
    ->whereBetween('Fingerin', [$tanggalAwal, $tanggalAkhir])
    ->whereNotNull('TotalHonor') // Hanya ambil nilai yang tidak null
    ->groupBy('idDosen', 'NamaDosen')
    ->get();
    foreach ($totalHonor as $honor) {
        $honor->TotalHonor = number_format($honor->TotalHonor, 3, '.', '');
    }
    } else {
        $totalHonor = DB::table('bayarskshonor')
            ->select('id_dosen', 'nama_dosen', DB::raw('SUM(honor) as TotalHonor'))
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->groupBy( 'nama_dosen','id_dosen')
            ->get();
    }
//dd($totalHonor);
    $total = $totalHonor->sum('TotalHonor');
    
    $idDavid = DB::table('inventoryid')->where('keterangan', 'DAVID FREDIANSON PURBA')->value('Id');
    $idHernyke = DB::table('inventoryid')->where('keterangan', 'HERNYKE ALVIANI SEMBIRING MELIALA')->value('Id');
    $idDedi = DB::table('inventoryid')->where('keterangan', 'Dr. DEDI HOLDEN SIMBOLON, S.Si.,M.Pd')->value('Id');

    return view('backend.type.rekaphonordosen', compact('totalHonor', 'tahun', 'bulan', 'total', 'idDavid', 'idHernyke', 'idDedi'));
}

}
