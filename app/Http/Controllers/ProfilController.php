<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
class ProfilController extends Controller
{   
    public function profilDashboard()
{
    return view('profil.profil');   
}
public function findDosen(Request $request)
{
    $term = $request->input('term');

    // Perform the search in both tables
    $dosen = DB::table('dosen')
    ->select('iddosen', 'nama')
    ->where('nama', 'like', '%' . $term . '%')
    ->get();
    // Combine the results from both tables
    return response()->json($dosen);
}
public function fetchData(Request $request)
{
    $iddosen = $request->input('iddosen');

    // Ambil data Dosen
    $dosenData = DB::table('dosen')
        ->select(
            'emaildosen as Email',
            'telepon as Telepon',
            'hp as HP',
            'noktp as NIK',
            'npwp as NPWP',
            'ketenagakerjaan as Ketenagakerjaan',
            'kesehatan as BPJS',
            'noacbank as No Rek',
            'alamat as Alamat',
            'agama as Agama',
            'relationshipstatus as Status'
        )
        ->where('iddosen', $iddosen)
        ->first();

    // Ambil data Dosensementara
    $dosensementaraData = DB::table('dosensementara')
        ->select(
            'emaildosen as Email',
            'telepon as Telepon',
            'hp as HP',
            'noktp as NIK',
            'npwp as NPWP',
            'ketenagakerjaan as Ketenagakerjaan',
            'kesehatan as BPJS',
            'noacbank as No Rek',
            'alamat as Alamat',
            'agama as Agama',
            'relationshipstatus as Status'
        )
        ->where('iddosen', $iddosen)
        ->first();

    return response()->json([
        'dosen' => $dosenData,
        'dosensementara' => $dosensementaraData
    ]);
}
public function approveData(Request $request)
{
    $iddosen = $request->input('iddosen');

    // Ambil data dosen sementara berdasarkan iddosen
    $dosenSementara = DB::table('dosensementara')
        ->where('iddosen', $iddosen)
        ->first();

    // Cek apakah data ditemukan
    if ($dosenSementara) {
        // Perbarui data di tabel dosen
        DB::table('dosen')
            ->where('iddosen', $iddosen)
            ->update([
                'TELEPON' => $dosenSementara->TELEPON,
                'HP' => $dosenSementara->HP,
                'NOKTP' => $dosenSementara->NOKTP,
                'NPWP' => $dosenSementara->NPWP,
                'Ketenagakerjaan' => $dosenSementara->Ketenagakerjaan,
                'Kesehatan' => $dosenSementara->Kesehatan,
                'NOACBANK' => $dosenSementara->NOACBANK,
                'ALAMAT' => $dosenSementara->ALAMAT,
                'AGAMA' => $dosenSementara->AGAMA,
                'EMAILDOSEN' => $dosenSementara->EMAILDOSEN,
                'RELATIONSHIPSTATUS' => $dosenSementara->RELATIONSHIPSTATUS,
            ]);

        // Hapus data dari tabel dosensementara yang telah disetujui
        DB::table('dosensementara')
            ->where('iddosen', $iddosen)
            ->delete();

        return response()->json(['message' => 'Data has been approved and updated in dosen table.']);
    } else {
        return response()->json(['error' => 'Data not found in dosensementara.'], 404);
    }
}
public function rejectData(Request $request)
{
    $iddosen = $request->input('iddosen');

    // Hapus data dosen sementara berdasarkan iddosen
    DB::table('dosensementara')
        ->where('iddosen', $iddosen)
        ->delete();

    return response()->json(['message' => 'Data rejection has been processed.']);
}
public function profilInput(Request $request)
{
   
   
    return view('profil.inputprofile');
    
}
public function fetchProfile(Request $request)
{
    $iddosen = $request->input('iddosen');
    $dosenprofile = DB::table('dosen')
    ->select(
        'NIDNNTBDOS as NIDN',
        'nama as Nama',
        'proditerdaftar as Prodi',
        'asalkota as Asal',
        'noktp as NIK',
        'npwp as NPWP',
        'ketenagakerjaan as Ketenagakerjaan',
        'kesehatan as BPJS',
        'noacbank as No Rek',
        'alamat as Alamat',
        'agama as Agama',
        'statusjabatan as Status',
        'jabatanakademik as jabatan',
        'golongan as golongan',
        'serdos as SERDOS',
        'SK_Dosen as SK',
        'homebase as Home Base'
    )
    
    ->where('iddosen', $iddosen)
    ->first();
    $results = DB::table('pendidikandosen')
    ->select('idprimary','iddosen', 'Itemno as ITEMNO', 'Pendidikan as pendidikan', 'keterangan')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();

    $matkul =DB::table('mkdosen2')
    ->select('idprimary','idmk','Itemno as ITEMNO', 'matakuliah', 'SKS')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();

    $fungsi =DB::table('HistJabatanDosenTetap')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'jabatandosen', 'Nosk')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $aktifitas =DB::table('HistStatusDosen')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'statusdosen', 'nosk','tmt')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $sertifikasi =DB::table('SertifikasiDosen')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'JenisSertifikasi', 'BidangSertifikasi','TahunSertifikasi','NoSK')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $penelitian =DB::table('HistPenelitian')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'judul', 'Bidang as bidang','Tahun as tahun','Lokasi as lokasi')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $penunjang =DB::table('HistLainnya')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'SERTIFIKATPANITIA as sertifikat', 
    'NAMAKEGIATAN as kegiatan','tahun','penyelenggara','tempat')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $inpassing =DB::table('hisInpassing')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 
    'NamaInpassing as pangkat','NOSK as nosk','Tanggal as tanggal','tmt')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $jabatan =DB::table('hisjabatanfungsional')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'jabatan', 
    'kum','NOSK as nosk','tanggal','tmt')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $ijinbelajar =DB::table('HistIzinBelajar')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'berizin', 
    'tahunmulai','NOSK as nosk','kota','keterangan')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $tugasbelajar =DB::table('HistTugasBelajar')
    ->select('idprimary','Iddosen as iddosen','Itemno as ITEMNO', 'tugas', 
    'tahunmulai','NOSK as nosk','kota','keterangan')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $dosenkelompok =DB::table('DosenKelompokMK')
    ->select('idprimary','Iddosen as iddosen','ilmuipa', 'ilmuips', 
    'ilmubahasa','ilmumatematika','Itemno as ITEMNO')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $dosenpembimbing =DB::table('TugasDosen')
    ->select('idprimary','Iddosen as iddosen','dosenwali', 'pembimbing1', 
    'pembimbing2','evaluasiolehdosen','evaluasiolehteman','evaluasiolehatasan','Itemno as ITEMNO')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $pengabdian =DB::table('HistPengabdian')
    ->select('idprimary','Iddosen as iddosen','namakegiatan', 'jeniskegiatan', 
    'lokasi','tahun','publikasi','Itemno as ITEMNO')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    $hibah =DB::table('Tblhibahdosen')
    ->select('idprimary','Iddosen as iddosen','Sumber', 'Jumlah','Itemno as ITEMNO', 'TA')
    ->where('iddosen', $iddosen)
    ->orderBy('ITEMNO', 'asc')
    ->get();
    return response()->json([
        'dosen' => $dosenprofile,
        'pendidikandosen'=>$results,
        'matakuliah'=>$matkul,
        'fungsi'=>$fungsi,
        'aktifitas'=>$aktifitas,
        'sertifikasi'=>$sertifikasi,
        'penelitian'=>$penelitian,
        'penunjang'=>$penunjang,
        'inpassing'=>$inpassing,
        'jabatan'=>$jabatan,
        'ijinbelajar'=>$ijinbelajar,
        'tugasbelajar'=>$tugasbelajar,
        'dosenkelompok'=>$dosenkelompok,
        'dosenpembimbing'=>$dosenpembimbing,
        'pengabdian'=>$pengabdian,
        'hibah'=>$hibah
    ]);
}
public function showEdit(Request $request)
{
    $iddosen = $request->input('iddosen');
    $dosen = DB::table('dosen')
                ->where('iddosen', $iddosen)
                ->first();
    $allJabatan = DB::table('jabatan')->select('itemno', 'jabatan')->distinct()->get(); 
    $allHome = DB::table('TblHomeBase')->select('homebase', 'nama')->distinct()->get(); 
    $allKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allJenjang = DB::table('JENJANGAKADEMIK')->select('itemno', 'jenjangakademik')->distinct()->get();
    $allGolongan = DB::table('golongandosen')->select('golongan', 'keterangan as kepangkatan')->distinct()->get();
    $allAktifan = DB::table('StatusDosenKampus')->select('statusdosen', 'keterangan')->distinct()->get();
    $allGoldarah = DB::table('GolonganDarah')->select('golongandarah', 'keterangan')->distinct()->get();
    $allAgama = DB::table('agama')->select('agama', 'keterangan')->distinct()->get();
    $allJabat = DB::table('TblAkademik')->select('idprimary', 'jabatanakademik')->distinct()->get();
    $allJenis = DB::table('JenisDosen')->select('idprimary', 'nama')->distinct()->get();
  // Perubahan di sini untuk mengambil JABATANAKADEMIK
  
    //dd($dosen);
    return view('profil.editprofile', compact('dosen','allJabatan','allHome','allKampus','allJenjang',
     'allGolongan','allAktifan','allGoldarah','allAgama','allJabat','allJenis'));
}

public function editProfile(Request $request)
{
    $data = $request->all();


    // Pastikan bidang-bidang yang mungkin kosong ada dalam array $data
    $fieldsToEnsure = ['nama', 'idfp', 'namaoutput', 'namagelar', 'gd', 'gb', 'tgllahir',
    'jeniskelamin', 'goldarah', 'agama', 'alamat', 'telepon', 'handphone', 'Email',
    'emailpribadi', 'nomor_rek', 'nik', 'npwp', 'ketenagakerjaan', 'kesehatan',
    'relationship_status', 'jlhtanggungan', 'namaibu', 'username', 'password', 'nidn',
    'jabatan', 'tanggalgabung', 'homebase', 'lokasi', 'jenjang', 'jabat', 'golongan',
    'kepangkatan', 'tunjpendidikan', 'tunjakademik', 'cabang', 'aktifan',
    'nip', 'skdosen'];
    foreach ($fieldsToEnsure as $field) {
        if (!isset($data[$field])) {
            $data[$field] = null; // Atau nilai default lainnya jika perlu
        }
    }
    $honor = str_replace(',', '.', str_replace('.', '', $data['honor']));
    // Simpan data ke dalam tabel dosen
    DB::table('dosen')
    ->where('iddosen', $data['iddosen'])
    ->update([
        'nama' => $data['nama'],
        'idfp' => $data['idfp'],
        'namaoutput' => $data['namaoutput'],
        'namagelar' => $data['namagelar'],
        'gd' => $data['gd'],
        'gb' => $data['gb'],
        'tgllahir' => date('Y-m-d', strtotime($data['tanggallahir'])),
        'tempatlahir' => $data['tempatlahir'],
        'jeniskelamin' => $data['jeniskelamin'],
        'goldarah' => $data['goldarah'],
        'AGAMA' => $data['agama'],
        'alamat' => $data['alamat'],
        'telepon' => $data['telepon'],
        'hp' => $data['handphone'],
        'emaildosen' => $data['Email'],
        'emailpribadi' => $data['emailpribadi'],
        'noacbank' => $data['nomor_rek'],
        'noktp' => $data['nik'],
        'npwp' => $data['npwp'],
        'ketenagakerjaan' => $data['ketenagakerjaan'],
        'kesehatan' => $data['kesehatan'],
        'relationshipstatus' => $data['relationship_status'],
        'jlhtanggungan' => $data['jlhtanggungan'],
        'namaibu' => $data['namaibu'],
        'loginusername' => $data['username'],
        'loginpassword' => $data['password'],
        'nidnntbdos' => $data['nidn'],
        'statusjabatan' => $data['jabatan'],
        'tglgabung' => date('Y-m-d', strtotime($data['tanggalgabung'])),
        'homebase' => $data['homebase'],
        'asalkota' => $data['lokasi'],
        'jenjangakademik' => $data['jenjang'],
        'jabatanakademik' => $data['jabat'],
        'golongan' => $data['golongan'],
        'kepangkatan' => $data['kepangkatan'],
        'TMTdosen' => date('Y-m-d', strtotime($data['tmt'])),
        'tunjpendidikan' => $data['tunjpendidikan'],
        'tunjakademik' => $data['tunjakademik'],
        'cabang' => $data['cabang'],
        'statusdosenaktif' => $data['aktifan'],
        'nip' => $data['nip'],
        'sk_dosen' => $data['skdosen'],
        'SKKepangkatan' => $data['sk_kepangkatan'],
        'SKPengangkatan'=>$data['sk_pengangkatan'],
        'Ijazah' => $data['ijazah'],
        'cv' => $data['cv'],
        'ktp' => $data['ktp'],
        'passfoto' => $data['passfoto'],
        'serdos' => $data['serdos'],
    ]);
    // Redirect kembali ke halaman dashboard atau halaman yang sesuai
    return response()->json(['success' => 'Data berhasil disimpan!']);
  
}


//PENDIDIKAN
public function deletePendidikan(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('pendidikandosen')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahPendidikan(Request $request)
{
    // Menangkap ID primary dari permintaan POST
    $idPrimary = $request->input('idPrimary');

    // Mengambil data pendidikan berdasarkan ID primary
    $pendidikan = DB::table('pendidikandosen')->where('idPrimary', $idPrimary)->first();

    // Memastikan data pendidikan ditemukan
    if (!$pendidikan) {
        // Jika tidak ditemukan, mungkin menampilkan pesan error atau mengembalikan ke halaman sebelumnya
        return redirect()->back()->with('error', 'Data pendidikan tidak ditemukan.');
    }

    // Mengirim data pendidikan ke halaman ubahpendidikan.blade.php
    return view('profil.ubahpendidikan', compact('pendidikan'));
}

public function editPendidikan(Request $request)
{
    $idPrimary = $request->input('idPrimary');
    $pendidikan = $request->input('pendidikan');
    $keterangan = $request->input('keterangan');
    $status = $request->input('status');
    $tanggal = $request->input('tanggal');
    $idDosen = $request->input('idDosen'); 
    $itemNo = $request->input('itemNo');// Mengambil nilai idDosen dari parameter URL
  // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
  // Lakukan penyisipan data ke dalam tabel pendidikandosen
  DB::table('pendidikandosen')
    ->where('idPrimary', $idPrimary)
    ->update([
        'IDDOSEN' => $idDosen,
        'ITEMNO' => $itemNo,
        'pendidikan' => $pendidikan,
        'keterangan' => $keterangan,
        'status' => $status,
        'tanggal' => $tanggal,
    ]);
    return response()->json(['success' => 'Data berhasil diperbarui.']);
}
public function showPendidikan(Request $request)
{      
    return view('profil.tambahpendidikan');   
}
public function tambahPendidikan(Request $request)
{  
    // Ambil data yang dikirim melalui formulir
    $pendidikan = $request->input('pendidikan');
    $keterangan = $request->input('keterangan');
    $status = $request->input('status');
    $tanggal = $request->input('tanggal');
    $idDosen = $request->input('idDosen'); // Mengambil nilai idDosen dari parameter URL

    // Mengambil itemno terakhir untuk idDosen tertentu
    $lastItemNo = DB::table('pendidikandosen')
                    ->where('IDDOSEN', $idDosen)
                    ->max('ITEMNO');

    // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
    $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;

    // Lakukan penyisipan data ke dalam tabel pendidikandosen
    DB::table('pendidikandosen')->insert([
        'IDDOSEN' => $idDosen,
        'ITEMNO' => $itemNo,
        'pendidikan' => $pendidikan,
        'keterangan' => $keterangan,
        'status' => $status,
        'tanggal' => $tanggal,
    ]);    
    return response()->json(['success' => 'Data berhasil ditambahkan!']);  
}
public function deleteMatkul(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('mkdosen')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}


//MATA KULIAH
public function showMatkul(Request $request)
{      
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get(); 
    $allMatkul = DB::table('matakuliah')->select('idmk', 'matakuliah')->distinct()->get();
    $allKurikulum  = DB::table('kurikulum')->select('kurikulum', 'tahunajaran1')->distinct()->get();// Replace 'your_prodi_table' with your actual table name
    // Pass the data to the view
    
    return view('profil.tambahmatakuliah',compact('allIdKampus','allProdi','allKurikulum','allMatkul'));  
}

public function searchIdmk(Request $request)
{
    try {
        $searchTerm = $request->input('term');
        $prodi = $request->input('prodi');
        $kurikulum = $request->input('kurikulum');

        // Modifikasi query untuk mengambil data matakuliah sesuai prodi dan kurikulum yang dipilih
        $results = DB::table('matakuliah')
            ->select('IDMK', 'MATAKULIAH', 'SKS')
            ->whereIn('IDMK', function($query) use ($prodi, $kurikulum) {
                $query->select('idmk')
                      ->from('prodimk')
                      ->where('prodi', $prodi)
                      ->where('kurikulum', $kurikulum);
            })
            ->where('MATAKULIAH', 'like', '%' . $searchTerm . '%')
            ->get();

        // Tambahkan pernyataan log sebelum mengembalikan respons
        \Log::info('searchidmk request with term: ' . $searchTerm);

        return response()->json($results);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error($e->getMessage());

        // Return a response indicating an error
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function tambahMatkul(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idmk = $request->input('idmk');
 $prodi = $request->input('prodi');
 $idDosen = $request->input('idDosen'); // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('mkdosen')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('mkdosen')->insert([
     'IDDOSEN' => $idDosen,
     'ITEMNO' => $itemNo,
     'IDMK' => $idmk,
     'prodi'=>$prodi
 ]);  

 return response()->json(['success' => 'Data berhasil ditambahkan!']);  
}
public function ubahMataKuliah(Request $request)
{
    // Menangkap ID primary dari permintaan POST
    $idPrimary = $request->input('idPrimary');

    // Mengambil data pendidikan berdasarkan ID primary
    
    $mkdosen = DB::table('mkdosen2')->where('idPrimary', $idPrimary)->first();
    $allMatkul = DB::table('matakuliah')->select('idmk', 'matakuliah')->distinct()->get();
    $allProdi = DB::table('prodi')->select('idfakultas', 'prodi')->distinct()->get();
    // Memastikan data pendidikan ditemukan
    if (!$mkdosen) {
        // Jika tidak ditemukan, mungkin menampilkan pesan error atau mengembalikan ke halaman sebelumnya
        return redirect()->back()->with('error', 'Data pendidikan tidak ditemukan.');
    }

    // Mengirim data pendidikan ke halaman ubahpendidikan.blade.php
    return view('profil.ubahmatakuliah', compact('mkdosen','allMatkul','allProdi'));
}
public function editMataKuliah(Request $request)
{
    $idmk = $request->input('idmk');
    //$prodi = $request->input('prodi');
    $idPrimary = $request->input('idPrimary');
    //$idDosen = $request->input('idDosen'); 
    //$itemNo = $request->input('itemNo');// Mengambil nilai idDosen dari parameter URL
  // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
  // Lakukan penyisipan data ke dalam tabel pendidikandosen
  DB::table('mkdosen')
    ->where('idPrimary', $idPrimary)
    ->update([
        'idmk'=>$idmk,
    ]);

    return response()->json(['success' => 'Data berhasil diperbarui.']);
}

public function searchMatkul(Request $request)
{
    try {
        $searchTerm = $request->input('term');

        // Logika pencarian berdasarkan $searchTerm, misalnya di tabel Dosen
        $results = DB::table('matakuliah')
            ->select('idmk', 'matakuliah')
            ->where('matakuliah', 'like', '%' . $searchTerm . '%')
            ->get();

        // Tambahkan pernyataan log sebelum mengembalikan respons
        \Log::info('SearchDosen request with term: ' . $searchTerm);

        return response()->json($results); // Mengembalikan seluruh hasil pencarian dalam bentuk JSON
    } catch (\Exception $e) {
        // Log the exception
        \Log::error($e->getMessage());

        // Return a response indicating an error
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

//JABATAN FUNGSI

public function showFungsi(Request $request)
{      
    $allJabatan = DB::table('jabatan')->select('itemno', 'jabatan')->distinct()->get();
    return view('profil.tambahfungsi',compact('allJabatan'));   
}
public function tambahFungsi(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $jabatandosen = $request->input('jabatan');
 $nomorsk = $request->input('Nosk');
 $tmt = $request->input('tmt');
 $tanggal = $request->input('tanggal');  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistJabatanDosenTetap')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistJabatanDosenTetap')->insert([
     'IDDOSEN' => $idDosen,
     'ITEMNO' => $itemNo,
     'jabatandosen' => $jabatandosen,
     'nosk'=>$nomorsk,
     'tanggal'=>$tanggal,
     'tmt'=>$tmt
 ]);  

 return response()->json(['success' => 'Data berhasil ditambahkan!']);  
}
public function deleteFungsi(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistJabatanDosenTetap')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahFungsi(Request $request)
{
    // Menangkap ID primary dari permintaan POST
    $idPrimary = $request->input('idPrimary');
    $allJabatan = DB::table('jabatan')->select('itemno', 'jabatan')->distinct()->get();
    $fungsi = DB::table('HistJabatanDosenTetap')->where('idPrimary', $idPrimary)->first();
    // Memastikan data pendidikan ditemukan
    //dd($fungsi);
    // Mengirim data pendidikan ke halaman ubahpendidikan.blade.php
    return view('profil.ubahfungsi', compact('allJabatan','fungsi'));
}
public function editFungsi(Request $request)
{
   $nomorsk = $request->input('nomorsk');
   //$prodi = $request->input('prodi');
   $idPrimary = $request->input('idPrimary');
   $jabatan = $request->input('jabatan'); 
   $tmt = $request->input('tmt');// Mengambil nilai idDosen dari parameter URL
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistJabatanDosenTetap')
   ->where('idPrimary', $idPrimary)
   ->update([
       'nosk'=>$nomorsk,
       'tmt'=>$tmt,
       'jabatandosen'=>$jabatan
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}


//AKTIFITAS DOSEN
public function showAktifitas(Request $request)
{      
    $allStatus = DB::table('TblStatusDosen')->select('StatusDosen as status', 'Keterangan')->distinct()->get();
    return view('profil.tambahaktifitas',compact('allStatus'));   
}
public function tambahAktifitas(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $statusdosen = $request->input('status');
 $nomorsk = $request->input('nomorsk');
 $tmt = $request->input('tmt');
  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistStatusDosen')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistStatusDosen')->insert([
     'IDDOSEN' => $idDosen,
     'ITEMNO' => $itemNo,
     'statusdosen' => $statusdosen,
     'NOSK'=>$nomorsk,
     'tmt'=>$tmt
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteAktifitas(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistStatusDosen')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahAktifitas(Request $request)
{
    // Menangkap ID primary dari permintaan POST
    $idPrimary = $request->input('idPrimary');
    $allStatus = DB::table('TblStatusDosen')->select('StatusDosen as status', 'Keterangan')->distinct()->get();
    $aktif = DB::table('HistStatusDosen')->where('idPrimary', $idPrimary)->first();
    // Memastikan data pendidikan ditemukan

    // Mengirim data pendidikan ke halaman ubahpendidikan.blade.php
    return view('profil.ubahaktifitas', compact('allStatus','aktif'));
}
public function editAktifitas(Request $request)
{
   $idPrimary = $request->input('idPrimary');
    $statusdosen = $request->input('status');
    $nomorsk = $request->input('nomorsk');
    $tmt = $request->input('tmt');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistStatusDosen')
   ->where('idPrimary', $idPrimary)
   ->update([
        'NOSK'=>$nomorsk,
       'tmt'=>$tmt,
       'statusdosen'=>$statusdosen
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//SERTIFIKASI DOSEN
public function showSertifikasi(Request $request)
{      
    
    return view('profil.tambahsertifikasi');   
}
public function tambahSertifikasi(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $JenisSertifikasi = $request->input('jenis');
 $BidangSertifikasi = $request->input('bidang');
 $tahun =$request->input('tahun');
 $nosk = $request->input('nomorsk');
  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('SertifikasiDosen')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('SertifikasiDosen')->insert([
     'IDDOSEN' => $idDosen,
     'ITEMNO' => $itemNo,
     'JenisSertifikasi' => $JenisSertifikasi,
     'NoSK'=>$nosk,
     'BidangSertifikasi'=>$BidangSertifikasi,
     'TahunSertifikasi'=>$tahun
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteSertifikasi(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('SertifikasiDosen')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahSertifikasi(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $sertifikat = DB::table('SertifikasiDosen')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahsertifikasi',compact('sertifikat'));   
}
public function editSertifikasi(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $JenisSertifikasi = $request->input('jenis');
   $BidangSertifikasi = $request->input('bidang');
   $TahunSertifikasi = $request->input('tahun');
   $nomorsk = $request->input('nomorsk');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('SertifikasiDosen')
   ->where('idPrimary', $idPrimary)
   ->update([
        'NoSK'=>$nomorsk,
       'JenisSertifikasi'=>$JenisSertifikasi,
       'BidangSertifikasi'=>$BidangSertifikasi,
       'TahunSertifikasi'=>$TahunSertifikasi
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}


//PENELITIAN
public function showPenelitian(Request $request)
{      
    
    return view('profil.tambahpenelitian');   
}
public function tambahPenelitian(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $judul = $request->input('judul');
 $bidang = $request->input('bidang');
 $tahun =$request->input('tahun');
 $lokasi =$request->input('lokasi');
 $publikasi = $request->input('publikasi');
  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistPenelitian')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistPenelitian')->insert([
     'IDDOSEN' => $idDosen,
     'ITEMNO' => $itemNo,
     'judul' => $judul,
     'lokasi'=>$lokasi,
     'Publikasi'=>$publikasi,
     'Bidang'=>$bidang,
     'Tahun'=>$tahun
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deletePenelitian(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistPenelitian')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahPenelitian(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $penelitian = DB::table('HistPenelitian')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahpenelitian',compact('penelitian'));   
}
public function editPenelitian(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $idDosen = $request->input('idDosen');
   $judul = $request->input('judul');
   $bidang = $request->input('bidang');
   $tahun =$request->input('tahun');
   $lokasi =$request->input('lokasi');
   $publikasi = $request->input('publikasi');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistPenelitian')
   ->where('idPrimary', $idPrimary)
   ->update([
    'judul' => $judul,
    'lokasi'=>$lokasi,
    'Publikasi'=>$publikasi,
    'Bidang'=>$bidang,
    'Tahun'=>$tahun
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}


//PENGABDIAN DOSEN
public function showPenunjang(Request $request)
{      
    
    return view('profil.tambahpenunjang');   
}
public function tambahPenunjang(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $sertifikat = $request->input('sertifikat');
 $kegiatan = $request->input('kegiatan');
 $tahun =$request->input('tahun');
 $tempat =$request->input('tempat');
 $penyelenggara = $request->input('penyelenggara');
  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistLainnya')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistLainnya')->insert([
     'IDDOSEN' => $idDosen,
     'ITEMNO' => $itemNo,
     'sertifikatpanitia' => $sertifikat,
     'tempat'=>$tempat,
     'penyelenggara'=>$penyelenggara,
     'namakegiatan'=>$kegiatan,
     'Tahun'=>$tahun
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deletePenunjang(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistLainnya')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahPenunjang(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $penunjang = DB::table('HistLainnya')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahpenunjang',compact('penunjang'));   
}
public function editPenunjang(Request $request)
{
   $idPrimary = $request->input('idPrimary');

   $sertifikat = $request->input('sertifikat');
   $kegiatan = $request->input('kegiatan');
   $tahun =$request->input('tahun');
   $tempat =$request->input('tempat');
   $penyelenggara = $request->input('penyelenggara');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistLainnya')
   ->where('idPrimary', $idPrimary)
   ->update([
    'sertifikatpanitia' => $sertifikat,
    'tempat'=>$tempat,
    'penyelenggara'=>$penyelenggara,
    'namakegiatan'=>$kegiatan,
    'Tahun'=>$tahun
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}


//Inpassing
public function showInpassing(Request $request)
{      
    $allPangkat = DB::table('GolonganPegawai')->select('golongan', 'keterangan')->distinct()->get();
    return view('profil.tambahinpassing',compact('allPangkat'));   
}
public function tambahInpassing(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $pangkat = $request->input('pangkat');
 $golongan = $request->input('golongan');
 $nomorsk =$request->input('nomorsk');
 $tanggal =$request->input('tanggal');
 $tmt = $request->input('tmt');
  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('hisinpassing')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('hisinpassing')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'nosk' => $nomorsk,
     'namainpassing'=>$pangkat,
     'tanggal'=>$tanggal,
     'tmt'=>$tmt
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteInpassing(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('hisinpassing')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahInpassing(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $inpassing = DB::table('hisinpassing')->where('idPrimary', $idPrimary)->first();
    $allPangkat = DB::table('GolonganPegawai')->select('golongan', 'keterangan')->distinct()->get();
    return view('profil.ubahinpassing',compact('allPangkat','inpassing'));   
}
public function editInpassing(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $pangkat = $request->input('pangkat');
   $golongan = $request->input('golongan');
   $nomorsk =$request->input('nomorsk');
   $tanggal =$request->input('tanggal');
   $tmt = $request->input('tmt');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('hisinpassing')
   ->where('idPrimary', $idPrimary)
   ->update([
    'nosk' => $nomorsk,
    'namainpassing'=>$pangkat,
    'tanggal'=>$tanggal,
    'tmt'=>$tmt
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}


//JABATAN DOSEN
public function showJabatan(Request $request)
{      
    $allJabatan = DB::table('TblAkademik')->select('idprimary', 'jabatanakademik')->distinct()->get();
    $allKum = DB::table('kum')->select('idprimary', 'kum')->distinct()->get();
    return view('profil.tambahjabatan',compact('allJabatan','allKum'));   
}
public function tambahJabatan(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $jabatan = $request->input('jabatan');
 $kum = $request->input('kum');
 $nomorsk =$request->input('nomorsk');
 $tanggal =$request->input('tanggal');
 $tmt = $request->input('tmt');
  // Mengambil nilai idDosen dari parameter URL
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('hisjabatanfungsional')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('hisjabatanfungsional')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'nosk' => $nomorsk,
     'jabatan'=>$jabatan,
     'kum'=>$kum,
     'tanggal'=>$tanggal,
     'tmt'=>$tmt
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteJabatan(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('hisjabatanfungsional')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahJabatan(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $jabat = DB::table('hisjabatanfungsional')->where('idPrimary', $idPrimary)->first();
    $allJabatan = DB::table('TblAkademik')->select('idprimary', 'jabatanakademik')->distinct()->get();
    $allKum = DB::table('kum')->select('idprimary', 'kum')->distinct()->get();
    //dd($jabat,$allJabatan);
    return view('profil.ubahjabatan',compact('allJabatan','allKum','jabat'));   
}
public function editJabatan(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $jabatan = $request->input('jabatan');
    $kum = $request->input('kum');
    $nomorsk =$request->input('nomorsk');
    $tanggal =$request->input('tanggal');
    $tmt = $request->input('tmt');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('hisjabatanfungsional')
   ->where('idPrimary', $idPrimary)
   ->update([
    'NOSK' => $nomorsk,
    'Jabatan'=>$jabatan,
    'KUM'=>$kum,
    'Tanggal'=>$tanggal,
    'TMT'=>$tmt
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//Ijin Belajar Dosen
public function showIjinBelajar(Request $request)
{      
    return view('profil.tambahijinbelajar');   
}
public function tambahIjinBelajar(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $nidn = $request->input('nidn');
 $berizin = $request->input('berizin');
 $nomorsk =$request->input('nomorsk');
 $tahunmulai =$request->input('tahunmulai');
 $namapt = $request->input('namapt');
 $kota = $request->input('kota');
 $sumberdanai = $request->input('sumberdana');
 $keterangan = $request->input('keterangan');
  // Mengambil nilai idDosen dari parameter URL
  $nama = DB::table('dosen')
  ->where('IDDOSEN', $idDosen)
  ->value('nama');
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistIzinBelajar')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistIzinBelajar')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'nosk' => $nomorsk,
     'nidn'=>$nidn,
     'berizin'=>$berizin,
     'tahunmulai'=>$tahunmulai,
     'namapt'=>$namapt,
     'kota'=>$kota,
     'sumberdanai'=>$sumberdanai,
     'keterangan'=>$keterangan,
     'nama'=>$nama
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteIjinBelajar(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistIzinBelajar')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahIjinBelajar(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $ijin = DB::table('HistIzinBelajar')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahijinbelajar',compact('ijin'));   
}
public function editIjinBelajar(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $nidn = $request->input('nidn');
   $berizin = $request->input('berizin');
   $nomorsk =$request->input('nomorsk');
   $tahunmulai =$request->input('tahunmulai');
   $namapt = $request->input('namapt');
   $kota = $request->input('kota');
   $sumberdanai = $request->input('sumberdanai');
   $keterangan = $request->input('keterangan');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistIzinBelajar')
   ->where('idPrimary', $idPrimary)
   ->update([
    'nosk' => $nomorsk,
    'nidn'=>$nidn,
    'berizin'=>$berizin,
    'tahunmulai'=>$tahunmulai,
    'namapt'=>$namapt,
    'kota'=>$kota,
    'sumberdanai'=>$sumberdanai,
    'keterangan'=>$keterangan,
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//tugas belajar dosen
public function showTugasBelajar(Request $request)
{      
    return view('profil.tambahtugasbelajar');
}
public function tambahTugasBelajar(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $nidn = $request->input('nidn');
 $tugas = $request->input('tugas');
 $nomorsk =$request->input('nomorsk');
 $tahunmulai =$request->input('tahunmulai');
 $namapt = $request->input('namapt');
 $kota = $request->input('kota');
 $sumberdanai = $request->input('sumberdana');
 $keterangan = $request->input('keterangan');
  // Mengambil nilai idDosen dari parameter URL
  $nama = DB::table('dosen')
  ->where('IDDOSEN', $idDosen)
  ->value('nama');
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistTugasBelajar')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistTugasBelajar')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'nosk' => $nomorsk,
     'nidn'=>$nidn,
     'tugas'=>$tugas,
     'tahunmulai'=>$tahunmulai,
     'namapt'=>$namapt,
     'kota'=>$kota,
     'sumberdanai'=>$sumberdanai,
     'keterangan'=>$keterangan,
     'nama'=>$nama
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteTugasBelajar(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistTugasBelajar')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahTugasBelajar(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $tugas = DB::table('HistTugasBelajar')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahtugasbelajar',compact('tugas'));   
}
public function editTugasBelajar(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $nidn = $request->input('nidn');
   $tugas = $request->input('tugas');
   $nomorsk =$request->input('nomorsk');
   $tahunmulai =$request->input('tahunmulai');
   $namapt = $request->input('namapt');
   $kota = $request->input('kota');
   $sumberdanai = $request->input('sumberdana');
   $keterangan = $request->input('keterangan');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistTugasBelajar')
   ->where('idPrimary', $idPrimary)
   ->update([
    'nosk' => $nomorsk,
    'nidn'=>$nidn,
    'tugas'=>$tugas,
    'tahunmulai'=>$tahunmulai,
    'namapt'=>$namapt,
    'kota'=>$kota,
    'sumberdanai'=>$sumberdanai,
    'keterangan'=>$keterangan,
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//Dosen Kelompok Mata Kuliah
public function showDosenKelompok(Request $request)
{      
    return view('profil.tambahdosenkelompok');   
}
public function tambahDosenKelompok(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $ipa = $request->input('ipa');
 $ips = $request->input('ips');
 $bahasa =$request->input('bahasa');
 $matematika =$request->input('matematika');
  // Mengambil nilai idDosen dari parameter URL
  $nama = DB::table('dosen')
->where('IDDOSEN', $idDosen)
->value('nama');
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('DosenKelompokMK')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('DosenKelompokMK')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'ilmumatematika' => $matematika,
     'ilmuipa'=>$ipa,
     'ilmuips'=>$ips,
     'ilmubahasa'=>$bahasa,
     'namadosen'=>$nama
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteDosenKelompok(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('DosenKelompokMK')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahDosenKelompok(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $dosenkelompok = DB::table('DosenKelompokMK')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahdosenkelompok',compact('dosenkelompok'));   
}
public function editDosenKelompok(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $ipa = $request->input('ipa');
   $ips = $request->input('ips');
   $bahasa =$request->input('bahasa');
   $matematika =$request->input('matematika');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('DosenKelompokMK')
   ->where('idPrimary', $idPrimary)
   ->update([
    'ilmumatematika' => $matematika,
    'ilmuipa'=>$ipa,
    'ilmuips'=>$ips,
    'ilmubahasa'=>$bahasa,
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//Dosen Pembimbing
public function showDosenPembimbing(Request $request)
{      
    return view('profil.tambahdosenpembimbing');   
}
public function tambahDosenPembimbing(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $dosenwali = $request->input('dosenwali');
 $pembimbing1 = $request->input('pembimbing1');
 $pembimbing2 =$request->input('pembimbing2');
 $evaluasiolehdosen =$request->input('evaluasiolehdosen');
 $evaluasiolehteman =$request->input('evaluasiolehteman');
 $evaluasiolehatasan =$request->input('evaluasiolehatasan');
  // Mengambil nilai idDosen dari parameter URL
  $nama = DB::table('dosen')
->where('IDDOSEN', $idDosen)
->value('nama');
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('TugasDosen')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('TugasDosen')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'dosenwali' => $dosenwali,
     'pembimbing1'=>$pembimbing1,
     'pembimbing2'=>$pembimbing2,
     'evaluasiolehdosen'=>$evaluasiolehdosen,
     'evaluasiolehteman'=>$evaluasiolehteman,
     'evaluasiolehatasan'=>$evaluasiolehatasan,
     'namadosen'=>$nama
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteDosenPembimbing(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('TugasDosen')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahDosenPembimbing(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $dosenpembimbing = DB::table('TugasDosen')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahdosenpembimbing',compact('dosenpembimbing'));   
}
public function editDosenPembimbing(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $dosenwali = $request->input('dosenwali');
    $pembimbing1 = $request->input('pembimbing1');
    $pembimbing2 =$request->input('pembimbing2');
    $evaluasiolehdosen =$request->input('evaluasiolehdosen');
    $evaluasiolehteman =$request->input('evaluasiolehteman');
    $evaluasiolehatasan =$request->input('evaluasiolehatasan');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('TugasDosen')
   ->where('idPrimary', $idPrimary)
   ->update([
    'dosenwali' => $dosenwali,
    'pembimbing1'=>$pembimbing1,
    'pembimbing2'=>$pembimbing2,
    'evaluasiolehdosen'=>$evaluasiolehdosen,
    'evaluasiolehteman'=>$evaluasiolehteman,
    'evaluasiolehatasan'=>$evaluasiolehatasan
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}
//Pengabdian Dosen
public function showPengabdian(Request $request)
{      
    return view('profil.tambahpengabdian');   
}
public function tambahPengabdian(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $kegiatan = $request->input('kegiatan');
 $jenis = $request->input('jenis');
 $lokasi =$request->input('lokasi');
 $tahun =$request->input('tahun');
 $publikasi =$request->input('publikasi');
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('HistPengabdian')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistPengabdian')->insert([
     'IDDOSEN' => $idDosen,
     'itemno' => $itemNo,
     'namakegiatan' => $kegiatan,
     'jeniskegiatan'=>$jenis,
     'lokasi'=>$lokasi,
     'tahun'=>$tahun,
     'publikasi'=>$publikasi
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deletePengabdian(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('HistPengabdian')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahPengabdian(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $pengabdian = DB::table('HistPengabdian')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahpengabdian',compact('pengabdian'));   
}
public function editPengabdian(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $kegiatan = $request->input('kegiatan');
    $jenis = $request->input('jenis');
    $lokasi =$request->input('lokasi');
    $tahun =$request->input('tahun');
    $publikasi =$request->input('publikasi');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('HistPengabdian')
   ->where('idPrimary', $idPrimary)
   ->update([
    'namakegiatan' => $kegiatan,
    'jeniskegiatan'=>$jenis,
    'lokasi'=>$lokasi,
    'tahun'=>$tahun,
    'publikasi'=>$publikasi
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//Hibah
public function showHibah(Request $request)
{      
    return view('profil.tambahhibah');   
}
public function tambahHibah(Request $request)
{
     // Ambil data yang dikirim melalui formulir
 $idDosen = $request->input('idDosen');
 $sumber = $request->input('sumber');
 $jumlah = $request->input('jumlah');
 $ta =$request->input('ta');
 // Mengambil itemno terakhir untuk idDosen tertentu
 $lastItemNo = DB::table('Tblhibahdosen')
                 ->where('IDDOSEN', $idDosen)
                 ->max('ITEMNO');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 $itemNo = $lastItemNo ? $lastItemNo + 1 : 1;
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('Tblhibahdosen')->insert([
     'IDDOSEN' => $idDosen,
     'Itemno' => $itemNo,
     'Sumber' => $sumber,
     'Jumlah'=>$jumlah,
     'TA'=>$ta
 ]);  
 return response()->json(['success' => 'Data berhasil disimpan']);
}
public function deleteHibah(Request $request)
{
    $idPrimary = $request->input('idPrimary');

    // Hapus data pendidikan dosen berdasarkan idPrimary
    DB::table('Tblhibahdosen')
        ->where('idPrimary', $idPrimary)
        ->delete();

    return response()->json(['message' => 'Data pendidikan dosen berhasil dihapus.']);
}
public function ubahHibah(Request $request)
{      
    $idPrimary = $request->input('idPrimary');
    $hibah = DB::table('Tblhibahdosen')->where('idPrimary', $idPrimary)->first();
    return view('profil.ubahhibah',compact('hibah'));   
}
public function editHibah(Request $request)
{
   $idPrimary = $request->input('idPrimary');
   $sumber = $request->input('sumber');
    $jumlah = $request->input('jumlah');
    $ta =$request->input('ta');
 // Jika tidak ada entri sebelumnya, atur itemno menjadi 1
 // Lakukan penyisipan data ke dalam tabel pendidikandosen
 DB::table('Tblhibahdosen')
   ->where('idPrimary', $idPrimary)
   ->update([
    'Sumber' => $sumber,
    'Jumlah'=>$jumlah,
    'TA'=>$ta
   ]);
   
   return response()->json(['success' => 'Data berhasil diperbarui.']);
}

//tambah dosen
public function showAddProfile()
{
    // Fetch all necessary data for the form
    $allJabatan = DB::table('jabatan')->select('itemno', 'jabatan')->distinct()->get(); 
    $allHome = DB::table('TblHomeBase')->select('homebase', 'nama')->distinct()->get(); 
    $allKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allJenjang = DB::table('JENJANGAKADEMIK')->select('itemno', 'jenjangakademik')->distinct()->get();
    $allGolongan = DB::table('golongandosen')->select('golongan', 'keterangan as kepangkatan')->distinct()->get();
    $allAktifan = DB::table('StatusDosenKampus')->select('statusdosen', 'keterangan')->distinct()->get();
    $allGoldarah = DB::table('GolonganDarah')->select('golongandarah', 'keterangan')->distinct()->get();
    $allAgama = DB::table('agama')->select('agama', 'keterangan')->distinct()->get();
    $allJabat = DB::table('TblAkademik')->select('idprimary', 'jabatanakademik')->distinct()->get();
    $allJenis = DB::table('JenisDosen')->select('idprimary', 'nama')->distinct()->get();

    // Get the highest existing iddosen with 7 digits and increment it by 1
    $highestIdDosenRecord = DB::table('dosen')
        ->select('iddosen')
        ->whereRaw('LEN(iddosen) = 7')
        ->orderBy('iddosen', 'desc')
        ->first();

    // Check if there's an existing record and set the newIdDosen accordingly
    if ($highestIdDosenRecord) {
        $newIdDosen = (int)$highestIdDosenRecord->iddosen + 1;
    } else {
        $newIdDosen = 1000000; // Starting value if no such iddosen exists
    }
    $highestHonors = DB::table('dosen')
        ->select(DB::raw('MAX(honorsks) as highestHonorsks'), DB::raw('MAX(honorskss2) as highestHonorskss2'))
        ->first();
       // Format honorsks and honorskss2 without decimal places
       $formattedHonorsks = number_format($highestHonors->highestHonorsks, 0, ',', '.');
       $formattedHonorskss2 = number_format($highestHonors->highestHonorskss2, 0, ',', '.');

    // Prepare the default data for the new form
    $dosen = [
        'iddosen' => $newIdDosen,
        'idfp' => $newIdDosen,
        'iddosen2' => $newIdDosen,
        'loginusername' => $newIdDosen,
    ];
    //dd($dosen);
    // Pass all data to the view
    return view('profil.tambahdosen', compact(
        'newIdDosen', 'formattedHonorsks', 'formattedHonorskss2', 'allJabatan', 'allHome', 'allKampus', 'allJenjang', 
        'allGolongan', 'allAktifan', 'allGoldarah', 'allAgama', 'allJabat', 'allJenis'
    ));
   
}

public function addProfile(Request $request)
{
    $data = $request->all();
    //dd($data);
    $honors2 = str_replace(',', '.', str_replace('.', '', $data['honors2']));
    $honor = str_replace(',', '.', str_replace('.', '', $data['honor']));
    // Simpan data ke dalam tabel dosen
    DB::table('dosen')
    ->insert([
        'iddosen'=>$data['iddosen'],
        'nama' => $data['nama'],
        'idfp' => $data['idfp'],
        'namaoutput' => $data['namaoutput'],
        'namagelar' => $data['namagelar'],
        'gd' => $data['gd'],
        'gb' => $data['gb'],
        'tgllahir' => date('Y-m-d', strtotime($data['tanggallahir'])),
        'tempatlahir' => $data['tempatlahir'],
        'jeniskelamin' => $data['jeniskelamin'],
        'goldarah' => $data['goldarah'],
        'AGAMA' => $data['agama'],
        'alamat' => $data['alamat'],
        'telepon' => $data['telepon'],
        'hp' => $data['handphone'],
        'emaildosen' => $data['Email'],
        'emailpribadi' => $data['emailpribadi'],
        'noacbank' => $data['nomor_rek'],
        'noktp' => $data['nik'],
        'npwp' => $data['npwp'],
        'ketenagakerjaan' => $data['ketenagakerjaan'],
        'kesehatan' => $data['kesehatan'],
        'relationshipstatus' => $data['relationship_status'],
        'jlhtanggungan' => $data['jlhtanggungan'],
        'namaibu' => $data['namaibu'],
        'loginusername' => $data['username'],
        'loginpassword' => $data['password'],
        'nidnntbdos' => $data['nidn'],
        'statusjabatan' => $data['jabatan'],
        'tglgabung' => date('Y-m-d', strtotime($data['tanggalgabung'])),
        'homebase' => $data['homebase'],
        'asalkota' => $data['lokasi'],
        'jenjangakademik' => $data['jenjang'],
        'jabatanakademik' => $data['jabat'],
        'golongan' => $data['golongan'],
        'kepangkatan' => $data['kepangkatan'],
        'honorsks' => $honor,
        'HONORSKSS2' => $honors2,
        'TMTdosen' => date('Y-m-d', strtotime($data['tmt'])),
        'tunjpendidikan' => $data['tunjpendidikan'],
        'tunjakademik' => $data['tunjakademik'],
        'statusdosenaktif' => $data['aktifan'],
        'nip' => $data['nip'],
        'sk_dosen' => $data['skdosen'],
        'SKKepangkatan' => $data['sk_kepangkatan'],
        'SKPengangkatan'=>$data['sk_pengangkatan'],
        'Ijazah' => $data['ijazah'],
        'cv' => $data['cv'],
        'ktp' => $data['ktp'],
        'passfoto' => $data['passfoto'],
        'serdos' => $data['serdos'],
    ]);
    // Redirect kembali ke halaman dashboard atau halaman yang sesuai
    return response()->json(['success' => 'Data berhasil disimpan!']);
  
}

}