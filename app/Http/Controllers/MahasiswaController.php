<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class MahasiswaController extends Controller
{
    public function findMahasiswa(Request $request)
{
    $term = $request->input('term');

    // Perform the search in both tables
    $mahasiswa = DB::table('mahasiswa')
    ->select('npm', 'nama')
    ->where('nama', 'like', '%' . $term . '%')
    ->get();
    // Combine the results from both tables
    return response()->json($mahasiswa);
}
public function showProfileMahasiswa (Request $request)
{
    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
    return view('mahasiswa.profile.profilemahasiswa',compact('allIdKampus','allProdi'));
}
public function viewProfileMahasiswa (Request $request)
{
    $npm = $request->input('npm');
    $profile = DB::table('mahasiswa as a')
    ->select(
        DB::raw("ISNULL(a.nama, '') as namalengkap"),
        DB::raw("ISNULL(a.NAMAOUTPUT, '') as nama"),
        'a.TGLLAHIR as tanggallahir',
        DB::raw("ISNULL(a.TEMPATLAHIR, '') as tempatlahir"),
        DB::raw("ISNULL(a.TELEPON, '') as notelp"),
        DB::raw("ISNULL(a.HP, '') as hp"),
        DB::raw("ISNULL(a.EMAILMAHASISWA, '') as email"),
        DB::raw("ISNULL(a.HOBI, '') as hobi"),
        'a.JENISKELAMIN as jeniskelamin',
        'a.BEKERJA as bekerja',
        DB::raw("ISNULL(a.AGAMA, '') as agama"),
        DB::raw("ISNULL(a.STATUS, '') as status"),
        DB::raw("ISNULL(a.warga, '') as warga"),
        DB::raw("ISNULL(a.SUMBERBIAYA, '') as sumber"),
        DB::raw("ISNULL(a.TIPEKELAS, '') as tipekelas"),
        'a.npm as npm',
        'a.IDKAMPUS as idkampus',
        'b.lokasi as lokasi',
        'a.IDFAKULTAS as idfakultas',
        'c.FAKULTAS as fakultas',
        'a.UNIVERSITAS as universitas',
        'a.PRODI as prodi',
        'a.TA as ta',
        'a.SEMESTER as semester',
        'a.LOGINUSERNAME as username',
        'a.LOGINPASSWORD as passwrd',
        'a.JENISSEKOLAH as sekolah',
        DB::raw("ISNULL(a.IDLembaga, '') as idsekolah"),
        DB::raw("ISNULL(a.NAMASEKOLAH, '') as namasekolah"),
        DB::raw("ISNULL(a.ALAMATSEKOLAH, '') as alamatsekolah"),
        'a.KECAMATANSEKOLAH as kecamatan',
        'a.KABUPATENSEKOLAH as kabupaten',
        'a.PROPINSISEKOLAH as provinsi',
        'a.JURUSANSEKOLAH as jurusan',
        'a.NOIJAZAH as ijazah',
        'a.TGLIJAZAH as tglijazah',
        'a.NAMAAYAH as ayah',
        'a.NAMAIBU as ibu',
        'a.HP as hportu',
        'a.ALAMATORTU as alamatortu',
        'a.KECAMATANORTU as kecamatanortu',
        'a.KABUPATENORTU as kabupatenortu',
        DB::raw("ISNULL(a.StatusMahasiswa, '') as statusmhs"),
        'a.PEKERJAANORTU as pekerjaan',
        'a.PENGHASILANORTU as penghasilan',
        'a.ALAMATKERJA as alamatkerja',
        'a.KECAMATANKERJA as kecamatankerja',
        'a.KABUPATENKERJA as kabupatenkerja',
        'a.ALAMATASAL as alamatasal',
        'a.KECAMATANASAL as kecamatanasal',
        'a.KABUPATENASAL as kabupatenasal',
        'a.IDDOSEN as dosenpembimbing',
        DB::raw("ISNULL(d.NAMA, '') as namadosen"),
        'a.TGLMASUK as tglmasuk',
        DB::raw("ISNULL(e.NIK, '') as nik")
    )
    ->join('kampus as b', 'b.idkampus', '=', 'a.idkampus')
    ->join('Fakultas as c', 'c.IDFAKULTAS', '=', 'a.IDFAKULTAS')
    ->leftJoin('dosen as d', 'd.IDDOSEN', '=', 'a.IDDOSEN')
    ->leftJoin('pmbregistrasi as e', 'e.npm', '=', 'a.npm')
    ->where('a.npm', $npm)
    ->orderBy('a.npm', 'asc')
    ->first();
    if ($profile) {
        $profile->tanggallahir = \Carbon\Carbon::parse($profile->tanggallahir)->format('d/m/Y');
        $profile->tglmasuk = \Carbon\Carbon::parse($profile->tglmasuk)->format('d/m/Y');
    }

    $allIdKampus = DB::table('kampus')->select('idkampus', 'lokasi')->distinct()->get();
    $allProdi = DB::table('prodifakultas')->select('idfakultas', 'prodi')->distinct()->get(); 
    $allAgama = DB::table('agama')->select('agama', 'keterangan')->distinct()->get();
    $allPenghasilan = DB::table('TblGajiOrtu')->select('idprimary', 'nama')->distinct()->get();
    $allPekerjaan = DB::table('Tblkerjaortu')->select('idprimary', 'nama')->distinct()->get();
    return view('mahasiswa.profile.profilemahasiswa',compact('allIdKampus','allProdi','profile','npm','allAgama',
    'npm','allPenghasilan','allPekerjaan'));
}
public function updateProfileMahasiswa(Request $request)
{
    $data = $request->all();

    // Validasi dan konversi tanggal
    // Update data mahasiswa
    DB::table('mahasiswa')
        ->where('npm', $data['npm'])
        ->update([
            'nama' => $data['namalengkap'],
            'NAMAOUTPUT' => $data['nama'],
            'TEMPATLAHIR' => $data['tempatlahir'],
            'TELEPON' => $data['notelp'],
            'HP' => $data['hp'],
            'EMAILMAHASISWA' => $data['email'],
            'HOBI' => $data['hobi'],
            'JENISKELAMIN' => $data['jeniskelamin'],
            'BEKERJA' => $data['bekerja'],
            'AGAMA' => $data['agama'],
            'STATUS' => $data['status'],
            'warga' => $data['warga'],
            'SUMBERBIAYA' => $data['sumber'],
            'TIPEKELAS' => $data['tipekelas'],
            'IDKAMPUS' => $data['idkampus'],
            'IDFAKULTAS' => $data['idfakultas'],
            'UNIVERSITAS' => $data['universitas'],
            'PRODI' => $data['prodi'],
            'TA' => $data['ta'],
            'SEMESTER' => $data['semester'],
            'LOGINUSERNAME' => $data['username'],
            'LOGINPASSWORD' => $data['passwrd'],
            'JENISSEKOLAH' => $data['sekolah'],
            'IDLembaga' => $data['idsekolah'],
            'NAMASEKOLAH' => $data['namasekolah'],
            'ALAMATSEKOLAH' => $data['alamatsekolah'],
            'KECAMATANSEKOLAH' => $data['kecamatan'],
            'KABUPATENSEKOLAH' => $data['kabupaten'],
            'PROPINSISEKOLAH' => $data['provinsi'],
            'JURUSANSEKOLAH' => $data['jurusan'],
            'NOIJAZAH' => $data['ijazah'],
            'NAMAAYAH' => $data['ayah'],
            'NAMAIBU' => $data['ibu'],
            'ALAMATORTU' => $data['alamatortu'],
            'KECAMATANORTU' => $data['kecamatanortu'],
            'KABUPATENORTU' => $data['kabupatenortu'],
            'StatusMahasiswa' => $data['statusmhs'],
            'PEKERJAANORTU' => $data['pekerjaan'],
            'PENGHASILANORTU' => $data['penghasilan'],
            'ALAMATKERJA' => $data['alamatkerja'],
            'KECAMATANKERJA' => $data['kecamatankerja'],
            'KABUPATENKERJA' => $data['kabupatenkerja'],
            'ALAMATASAL' => $data['alamatasal'],
            'KECAMATANASAL' => $data['kecamatanasal'],
            'KABUPATENASAL' => $data['kabupatenasal'],
            'IDDOSEN' => $data['dosenpembimbing']
        ]);

        return response()->json(['success' => 'Data berhasil disimpan']);
}
}
