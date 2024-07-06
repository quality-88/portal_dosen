<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AkreditasiController;
use App\Http\Controllers\Backend\PropertyTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KrsMahasiswaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\FungsionarisController;
use App\Http\Controllers\JabatanFungsiController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\SettingHonorController;
use App\Http\Controllers\PMBController;

use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('admin_dashboard'); // Ubah 'admin_dashboard' menjadi 'login'
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    // Tambahkan rute untuk divisi lain jika diperlukan
    // Route::get('/default_dashboard', [DashboardController::class, 'defaultDashboard'])->name('default_dashboard');

    // Terapkan middleware check.divisi pada rute formHonorDosen
    Route::get('/formHonorDosen', [PropertyTypeController::class, 'showFormData'])
        ->name('showFormData')
        ->middleware('check.divisi:Administrator,Biro Keuangan');
        //honor
    Route::post('/all/type', [PropertyTypeController::class, 'processForm'])->name('processForm');
    Route::get('/formHonorDosen', [PropertyTypeController::class, 'showFormData'])->name('showFormData');
    Route::get('/all/type', [PropertyTypeController::class, 'processForm'])->name('formHonorDosen');
    Route::get('/fetchFakultas', [PropertyTypeController::class, 'fetchFakultas'])->name('fetchFakultas');
    Route::get('/all-type', [PropertyTypeController::class, 'allType'])->name('allType');
    Route::get('/searchDosen', [PropertyTypeController::class, 'searchDosen'])->name('searchDosen');
    Route::get('/rekaphonor', [PembayaranController::class, 'showRekapHonorDosen'])->name('showRekapHonorDosen');
    Route::post('/rekaphonor', [PembayaranController::class, 'rekapHonorDosen'])->name('rekapHonorDosen');
    Route::get('/profiledosen', [PropertyTypeController::class, 'showPPD'])->name('showPPD');
    Route::post('/profiledosen', [PropertyTypeController::class, 'detailProfileDosen'])->name('detail.keuangan');
    //nilai mahasiswa
    Route::get('/nilai/type', [NilaiController::class, 'showNilai'])->name('showNilai');
    Route::post ('/nilai/submit', [NilaiController::class, 'submitForm'])->name('submit.form');
    Route::get('/cetaknilai', [NilaiController::class, 'showCetakNilai'])->name('cetak.nilai');
    Route::get('/nilaikelas', [NilaiController::class, 'showNilaKelas'])->name('nilaikelas');
    //honor
    Route::post('all/formHonorDosen/endpoint-pembayaran', [PembayaranController::class,'bayar']);
    Route::post('all/formHonorDosen/bayar-semua', [PembayaranController::class,'bayarSemua']);
    //profil dosen
    Route::get('/show-profile', [ProfilController::class, 'profilDashboard'])->name('profilDashboard');
    Route::get('/profil/fetchData', [ProfilController::class, 'fetchData']);
    Route::get('/findDosen', [ProfilController::class, 'findDosen'])->name('findDosen');
    Route::post('/profil/approve', [ProfilController::class,'approveData']);
    Route::post('/profil/reject', [ProfilController::class,'rejectData']);
    Route::get('/input', [ProfilController::class, 'profilInput'])->name('profilInput');
    Route::get('/input/fetchProfile', [ProfilController::class, 'fetchProfile']);
    //pendidikan dosen
    Route::post('/hapusDataPendidikan', [ProfilController::class,'deletePendidikan']);
    Route::get('/ubahpendidikan', [ProfilController::class,'ubahPendidikan']);
    Route::post('/ubah', [ProfilController::class,'editPendidikan'])->name('editPendidikan');
    Route::get('/tambahpendidikan', [ProfilController::class,'showPendidikan'])->name('showPendidikan');
    Route::post('/tambahpendidikan', [ProfilController::class,'tambahPendidikan'])->name('tambahPendidikan');
    //mata kuliah dosen
    Route::post('/hapusMataKuliah', [ProfilController::class,'deleteMatkul']);
    Route::get('/tambahmatakuliah', [ProfilController::class,'showMatkul'])->name('showMatkul');
    Route::post('/tambahmatkul', [ProfilController::class,'tambahMatkul'])->name('tambahMatkul');
    Route::get('/searchIdmk', [ProfilController::class, 'searchIdmk'])->name('searchIdmk');
    Route::post('/ubahmatkul', [ProfilController::class,'editMataKuliah'])->name('editMataKuliah');
    Route::get('/ubahmatakuliah', [ProfilController::class,'ubahMataKuliah']);
    Route::get('/searchMatkul', [ProfilController::class, 'searchMatkul'])->name('searchMatkul');
    //edit profil dosen
    Route::get('/editprofil', [ProfilController::class,'showEdit'])->name('showEdit');
    Route::post('/edit-profile',[ProfilController::class,'editProfile'])->name('editProfile');
    // fungsionoris dosen
    Route::get('/tambahfungsi', [ProfilController::class,'showFungsi']);
    Route::post('/tambah', [ProfilController::class,'tambahFungsi'])->name('tambahFungsi');
    Route::post('/hapusFungsi', [ProfilController::class,'deleteFungsi']);
    Route::get('/ubahfungsi', [ProfilController::class,'ubahFungsi']);
    Route::post('/editfungsi',[ProfilController::class,'editFungsi'])->name('editFungsi');
    //aktifitas dosen
    Route::get('/tambahaktifitas', [ProfilController::class,'showAktifitas'])->name('showAktifitas');
    Route::post('/tambahaktifitas', [ProfilController::class,'tambahAktifitas'])->name('tambahAktifitas');
    Route::post('/hapusAktifitas', [ProfilController::class,'deleteAktifitas'])->name('deleteAktifitas');
    Route::get('/ubahaktifitas', [ProfilController::class,'ubahAktifitas'])->name('ubahAktifitas');
    Route::post('/editaktifitas', [ProfilController::class,'editAktifitas'])->name('editAktifitas');

    //SERTIFIKASI dosen
    Route::get('/tambahsertifikasi', [ProfilController::class,'showSertifikasi'])->name('showSertifikasi');
    Route::post('/tambahsertifikasi', [ProfilController::class,'tambahSertifikasi'])->name('tambahSertifikasi');
    Route::post('/hapussertifikasi', [ProfilController::class,'deleteSertifikasi'])->name('deleteSertifikasi');
    Route::get('/ubahsertifikasi', [ProfilController::class,'ubahSertifikasi'])->name('ubahSertifikasi');
    Route::post('/editsertifikasi', [ProfilController::class,'editSertifikasi'])->name('editSertifikasi');

    //Penelitian dosen
    Route::get('/tambahpenelitian', [ProfilController::class,'showPenelitian'])->name('showPenelitian');
    Route::post('/tambahpenelitian', [ProfilController::class,'tambahPenelitian'])->name('tambahPenelitian');
    Route::post('/hapuspenelitian', [ProfilController::class,'deletePenelitian'])->name('deletePenelitian');
    Route::get('/ubahpenelitian', [ProfilController::class,'ubahPenelitian'])->name('ubahPenelitian');
    Route::post('/editpenelitian', [ProfilController::class,'editPenelitian'])->name('editPenelitian');

    //penunjang dosen
    Route::get('/tambahpenunjang', [ProfilController::class,'showPenunjang'])->name('showPenunjang');
    Route::post('/tambahpenunjang', [ProfilController::class,'tambahPenunjang'])->name('tambahPenunjang');
    Route::post('/hapuspenunjang', [ProfilController::class,'deletePenunjang'])->name('deletePenunjang');
    Route::get('/ubahpenunjang', [ProfilController::class,'ubahPenunjang'])->name('ubahPenunjang');
    Route::post('/editpenunjang', [ProfilController::class,'editPenunjang'])->name('editPenunjang');

    //Inpassing DOSEN
    Route::get('/tambahinpassing', [ProfilController::class,'showInpassing'])->name('showInpassing');
    Route::post('/tambahinpassing', [ProfilController::class,'tambahInpassing'])->name('tambahInpassing');
    Route::post('/hapusinpassing', [ProfilController::class,'deleteInpassing'])->name('deleteInpassing');
    Route::get('/ubahinpassing', [ProfilController::class,'ubahInpassing'])->name('ubahInpassing');
    Route::post('/editinpassing', [ProfilController::class,'editInpassing'])->name('editInpassing');

    //JABATAN DOSEN
    Route::get('/tambahjabatan', [ProfilController::class,'showJabatan'])->name('showJabatan');
    Route::post('/tambahjabatan', [ProfilController::class,'tambahJabatan'])->name('tambahJabatan');
    Route::post('/hapusjabatan', [ProfilController::class,'deleteJabatan'])->name('deleteJabatan');
    Route::get('/ubahjabatan', [ProfilController::class,'ubahJabatan'])->name('ubahJabatan');
    Route::post('/ubahjabatan', [ProfilController::class,'editJabatan'])->name('editJabatan');

    //Ijin Belajar Dosen
    Route::get('/tambahijinbelajar', [ProfilController::class,'showIjinBelajar'])->name('showIjinBelajar');
    Route::post('/tambahijinbelajar', [ProfilController::class,'tambahIjinBelajar'])->name('tambahIjinBelajar');
    Route::post('/hapusijinbelajar', [ProfilController::class,'deleteIjinBelajar'])->name('deleteIjinBelajar');
    Route::get('/ubahijinbelajar', [ProfilController::class,'ubahIjinBelajar'])->name('ubahIjinBelajar');
    Route::post('/editijinbelajar', [ProfilController::class,'editIjinBelajar'])->name('editIjinBelajar');

    //Tugas Belajar Dosen
    Route::get('/tambahtugasbelajar', [ProfilController::class,'showTugasBelajar'])->name('showTugasBelajar');
    Route::post('/tambahtugasbelajar', [ProfilController::class,'tambahTugasBelajar'])->name('tambahTugasBelajar');
    Route::post('/hapustugasbelajar', [ProfilController::class,'deleteTugasBelajar'])->name('deleteTugasBelajar');
    Route::get('/ubahtugasbelajar', [ProfilController::class,'ubahTugasBelajar'])->name('ubahTugasBelajar');
    Route::post('/edittugasbelajar', [ProfilController::class,'editTugasBelajar'])->name('editTugasBelajar');

    //Dosen Kelompok Mata Kuliah
    Route::get('/tambahdosenkelompok', [ProfilController::class,'showDosenKelompok'])->name('showDosenKelompok');
    Route::post('/tambahdosenkelompok', [ProfilController::class,'tambahDosenKelompok'])->name('tambahDosenKelompok');
    Route::post('/hapusdosenkelompok', [ProfilController::class,'deleteDosenKelompok'])->name('deleteDosenKelompok');
    Route::get('/ubahdosenkelompok', [ProfilController::class,'ubahDosenKelompok'])->name('ubahDosenKelompok');
    Route::post('/editdosenkelompok', [ProfilController::class,'editDosenKelompok'])->name('editDosenKelompok');

    //Dosen Pembimbing
    Route::get('/tambahdosenpembimbing', [ProfilController::class,'showDosenPembimbing'])->name('showDosenPembimbing');
    Route::post('/tambahdosenpembimbing', [ProfilController::class,'tambahDosenPembimbing'])->name('tambahDosenPembimbing');
    Route::post('/hapusdosenpembimbing', [ProfilController::class,'deleteDosenPembimbing'])->name('deleteDosenPembimbing');
    Route::get('/ubahdosenpembimbing', [ProfilController::class,'ubahDosenPembimbing'])->name('ubahDosenPembimbing');
    Route::post('/editdosenpembimbing', [ProfilController::class,'editDosenPembimbing'])->name('editDosenPembimbing');
     //Dosen Pengabdian
    Route::get('/tambahpengabdian', [ProfilController::class,'showPengabdian'])->name('showPengabdian');
    Route::post('/tambahpengabdian', [ProfilController::class,'tambahPengabdian'])->name('tambahPengabdian');
    Route::post('/hapuspengabdian', [ProfilController::class,'deletePengabdian'])->name('deletePengabdian');
    Route::get('/ubahpengabdian', [ProfilController::class,'ubahPengabdian'])->name('ubahPengabdian');
    Route::post('/editpengabdian', [ProfilController::class,'editPengabdian'])->name('editPengabdian');
    //Hibah Dosen
    Route::get('/tambahhibah', [ProfilController::class,'showHibah'])->name('showHibah');
    Route::post('/tambahhibah', [ProfilController::class,'tambahHibah'])->name('tambahHibah');
    Route::post('/hapushibah', [ProfilController::class,'deleteHibah'])->name('deleteHibah');
    Route::get('/ubahhibah', [ProfilController::class,'ubahHibah'])->name('ubahHibah');
    Route::post('/edithibah', [ProfilController::class,'editHibah'])->name('editHibah');

    //Summary KRS
    Route::get('/summarykrs', [KrsMahasiswaController::class,'showSummary'])->name('showSummary');
    Route::post('/summarykrs', [KrsMahasiswaController::class,'SummaryKRS'])->name('SummaryKRS');
    //Rincian KRS
    Route::get('/rinciankrs', [KrsMahasiswaController::class,'showRincian'])->name('showRincian');
    Route::post('/rinciankrs', [KrsMahasiswaController::class,'rincianKRS'])->name('rincianKRS');
    //Cetak KRS
    Route::get('/cetakkrs', [KrsMahasiswaController::class,'showCetakKRS'])->name('showCetakKRS');
    Route::get('/showMahasiswa', [KrsMahasiswaController::class,'showMahasiswa']);
    Route::post('/viewcetakkrs', [KrsMahasiswaController::class,'cetakKRS'])->name('cetakKRS');
    Route::get('cetakkrs/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');
    //Konversi Nilai
    Route::get('/konversinilai', [KrsMahasiswaController::class,'showKonversi'])->name('showKonversi');
    Route::get('/konversinilai/showKonversiNilai', [KrsMahasiswaController::class,'showKonversiNilai']);
    Route::post('/viewkonversinilai', [KrsMahasiswaController::class,'cetakTranskripNilai'])->name('cetakTranskripNilai');

    //Input Konversi Nilai
    Route::get('/tambahkonversi', [KrsMahasiswaController::class,'showTambahKonversi'])->name('showTambahKonversi');
    Route::get('/tambahkonversi/showInputKonversiNilai', [KrsMahasiswaController::class,'showInputKonversiNilai']);
    Route::post('/viewtambahkonversinilai', [KrsMahasiswaController::class,'viewTambahKonversi'])->name('viewTambahKonversi');
    Route::post('/delete', [KrsMahasiswaController::class,'deleteKonversiNilai'])->name('deleteKonversiNilai');
    Route::get('/searchMatakuliah', [KrsMahasiswaController::class,'searchMatkul'])->name('searchMatkul');
    Route::post('/simpanData', [KrsMahasiswaController::class,'simpanKonversi'])->name('simpanKonversi');
    Route::get('/edit', [KrsMahasiswaController::class,'editKonversi'])->name('editKonversi');
    Route::post('/edit', [KrsMahasiswaController::class,'sendEdit'])->name('sendEdit');
    Route::get('/changed', [KrsMahasiswaController::class,'changeView'])->name('changeView');
    Route::get('/tambahkonversi/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');

    //Cetak LDIKTI
    Route::get('/LDIKTI', [KrsMahasiswaController::class, 'showLDIKTI'])->name('showLDIKTI');
    Route::post('/LDIKTI', [KrsMahasiswaController::class, 'cetakLDIKTI'])->name('cetakLDIKTI');
    Route::get('/detailLDIKTI', [KrsMahasiswaController::class, 'detailLDIKTI'])->name('detailLDIKTI');
    // Tambahkan rute untuk fungsi pencarian
    Route::post('/searchLDIKTI', [KrsMahasiswaController::class,'searchLDIKTI'])->name('searchLDIKTI');

    
    //Mahasiswa
    Route::get('/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');

    //Data Fungsionaris 
    Route::get('/findDosen1', [FungsionarisController::class, 'findDosen'])->name('findDosen');
    Route::get('/formkaprodi', [FungsionarisController::class, 'formKaprodi'])->name('formKaprodi');
    Route::post('/formkaprodi', [FungsionarisController::class, 'insertJabatan'])->name('insertJabatan');
    Route::get('/viewjabatan', [FungsionarisController::class, 'showViewJabatan'])->name('showViewJabatan');
 
    //CMS
    Route::get('/showjabatanfungsi', [JabatanFungsiController::class,'showJabatanFungsi'])->name('showJabatanFungsi');
    Route::post('/showjabatanfungsi', [JabatanFungsiController::class,'simpanJabatan'])->name('simpanJabatan');
    //sethonor
    Route::get('/showsethonor', [SettingHonorController::class,'showSettingHonor'])->name('showSettingHonor');
    Route::post('/showsethonor', [SettingHonorController::class,'simpanSettingHonor'])->name('simpanSettingHonor');
    Route::post('/activate-honor-sks', [SettingHonorController::class,'activateHonorSks'])->name('activateHonorSks');

    //Kurikulum
    Route::get('/kurikulum', [KurikulumController::class,'showKurikulum'])->name('showKurikulum');
    Route::post('/kurikulum', [KurikulumController::class,'viewKurikulum'])->name('viewKurikulum');
    Route::get('/tambahkurikulum', [KurikulumController::class,'showTambahKurikulum'])->name('showTambahKurikulum');
    Route::get('/searchIdmk', [KurikulumController::class, 'searchIdmk'])->name('searchIdmk');
    Route::get('/fetchFakultash', [KurikulumController::class, 'fetchFakultash'])->name('fetchFakultash');
    Route::post('/tambahkurikulum', [KurikulumController::class, 'tambahKurikulum'])->name('tambahKurikulum');
    
    //Jadwal
    Route::get('/jadwal', [JadwalController::class,'showJadwal'])->name('showJadwal');
    Route::get('/fetchJadwal', [JadwalController::class,'fetchJadwal'])->name('fetchJadwal');
    Route::get('/fetchFakultas', [JadwalController::class, 'fetchFakultas'])->name('fetchFakultas');
    Route::get('/getKelas', [JadwalController::class, 'getKelas'])->name('getKelas');
    Route::get('/getIdmkList', [JadwalController::class, 'getIdmkList'])->name('getIdmkList');


    //UPPS
    Route::get('/upps', [AkreditasiController::class,'showUPPS'])->name('showUPPS');
    Route::post('/viewupps', [AkreditasiController::class,'viewUPPS'])->name('viewUPPS');
    //UPPS
    Route::get('/uppsfakultas', [AkreditasiController::class,'showUPPSFakultas'])->name('showUPPSFakultas');
    Route::post('/viewuppsfakultas', [AkreditasiController::class,'uppsFakultas'])->name('uppsFakultas');
    //SKProdi
    Route::get('/skprodi', [AkreditasiController::class,'SKprodi'])->name('SKprodi');
    Route::post('/tambahskprodi', [AkreditasiController::class,'showSKprodi'])->name('showSKprodi');
    //Jumlah Mahasiswa
    Route::get('/jlhmahasiswa', [AkreditasiController::class,'showjlhMahasiswa'])->name('showjlhMahasiswa');
    Route::post('/jlhmahasiswa', [AkreditasiController::class,'viewjlhMahasiswa'])->name('viewjlhMahasiswa');
    Route::get('/jlhmahasiswaprodi', [AkreditasiController::class,'showjlhMahasiswaProdi'])->name('showjlhMahasiswaProdi');
    Route::post('/jlhmahasiswaprodi', [AkreditasiController::class,'viewjlhMahasiswaProdi'])->name('viewjlhMahasiswaProdi');
    //Jumlah Mahasiswa Aktif / Tidak Aktif
    
    Route::get('/rekapmahasiswa', [AkreditasiController::class,'showRekap'])->name('showRekap');
    Route::post('/rekapmahasiswa', [AkreditasiController::class,'viewRekap'])->name('viewRekap');
    Route::get('/rekapmahasiswaprodi', [AkreditasiController::class,'showRekapProdi'])->name('showRekapProdi');
    Route::post('/rekapmahasiswaprodi', [AkreditasiController::class,'viewRekapProdi'])->name('viewRekapProdi');
    Route::get('/rekapmahasiswakaryawan', [AkreditasiController::class,'showRekapKaryawanProdi'])->name('form.karyawan');
    Route::post('/rekapmahasiswakaryawan', [AkreditasiController::class,'viewRekapKaryawanProdi'])->name('view.karyawan');
    Route::get('/detailmahasiswakaryawan', [AkreditasiController::class,'detailKaryawanAktif'])->name('detail.karyawan');
    //ipk Lulusan
    Route::get('/ipklulusan', [AkreditasiController::class,'showIPKLulusan'])->name('showIPKLulusan');
    Route::post('/ipklulusan', [AkreditasiController::class,'IPKLulusan'])->name('IPKLulusan');
    Route::get('/ipkprodi', [AkreditasiController::class,'showIPKProdi'])->name('showIPKProdi');
    Route::post('/ipkprodi', [AkreditasiController::class,'HitungIPK'])->name('HitungIPK');
    Route::get('/ipklulusanprodi', [AkreditasiController::class,'showIPKPPRODI'])->name('showIPKPPRODI');
    Route::post('/ipklulusanprodi', [AkreditasiController::class,'IPKLulusanPPRODI'])->name('IPKLulusanPPRODI');
    Route::get('/ipklulusankaryawanprodi', [AkreditasiController::class,'showIPKPPRODIRegular'])->name('showIPKPPRODIRegular');
    Route::post('/ipklulusankaryawanprodi', [AkreditasiController::class,'IPKLulusanPPRODIRegular'])->name('IPKLulusanPPRODIRegular');
    //PMB
    Route::get('/monitoringpmb', [PMBController::class,'showMonitoringPMB'])->name('showMonitoringPMB');
    Route::post('/monitoringpmb', [PMBController::class,'viewMonitoringPMB'])->name('viewMonitoringPMB');
    Route::get('/grafikpmb', [PMBController::class,'showGrafikPMB'])->name('showGrafikPMB');
    Route::post('/grafikpmb', [PMBController::class,'viewGrafikPMB'])->name('viewGrafikPMB');
    Route::get('/datacalon', [PMBController::class,'showDataCalonMahasiswa'])->name('showDataCalonMahasiswa');
    Route::post('/datacalon', [PMBController::class,'viewDataCalonMahasiswa'])->name('viewDataCalonMahasiswa');
    Route::get('/datacalons2', [PMBController::class,'showDataCalonMahasiswas2'])->name('showDataCalonMahasiswas2');
    Route::post('/datacalons2', [PMBController::class,'viewDataCalonMahasiswas2'])->name('viewDataCalonMahasiswas2');
});


