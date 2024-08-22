<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
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
use App\Http\Controllers\SettingHonorController;
use App\Http\Controllers\AkreditasiController;
use App\Http\Controllers\PMBController;
use App\Http\Controllers\JadwalController;  
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\MatakuliahController; 
use App\Http\Controllers\DosenController; 
use App\Http\Controllers\AlumniController;
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
Route::group(['middleware' => ['auth', 'CheckLoginTime']], function () {

    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    // Tambahkan rute untuk divisi lain jika diperlukan
    // Route::get('/default_dashboard', [DashboardController::class, 'defaultDashboard'])->name('default_dashboard');

    // Terapkan middleware check.divisi pada rute formHonorDosen
    Route::get('/formHonorDosen', [PropertyTypeController::class, 'showFormData'])
        ->name('showFormData')
        ->middleware('check.divisi:Administrator,Biro Keuangan');
    Route::post('/all/type', [PropertyTypeController::class, 'processForm'])->name('processForm');
    Route::get('/formHonorDosen', [PropertyTypeController::class, 'showFormData'])->name('showFormData');
    Route::get('/all/type', [PropertyTypeController::class, 'processForm'])->name('formHonorDosen');
    Route::get('formHonorDosen/fetchFakultas', [PropertyTypeController::class, 'fetchFakultas'])->name('fetchFakultas');
    Route::get('/all-type', [PropertyTypeController::class, 'allType'])->name('allType');
    Route::get('formHonorDosen/searchDosen', [PropertyTypeController::class, 'searchDosen'])->name('searchDosen');
    Route::get('/nilai/type', [NilaiController::class, 'showNilai'])->name('showNilai');
    Route::post ('/nilai/submit', [NilaiController::class, 'submitForm'])->name('submit.form');
    Route::get('/cetaknilai', [NilaiController::class, 'showCetakNilai'])->name('cetak.nilai');
    Route::get('/nilaikelas', [NilaiController::class, 'showNilaKelas'])->name('nilaikelas');
    Route::post('all/formHonorDosen/endpoint-pembayaran', [PembayaranController::class,'bayar']);
    Route::post('all/formHonorDosen/bayar-semua', [PembayaranController::class,'bayarSemua']);
    Route::get('/rekaphonor', [PembayaranController::class, 'showRekapHonorDosen'])->name('showRekapHonorDosen');
    Route::post('/rekaphonor', [PembayaranController::class, 'rekapHonorDosen'])->name('rekapHonorDosen');
    Route::get('/tunjakademik', [SettingHonorController::class,'showTunjAkademik'])->name('showTunjAkademik');
    Route::post('/tunjakademik', [SettingHonorController::class,'simpanTunjAkademik'])->name('simpanTunjAkademik');
    Route::post('/activatetunjakademik', [SettingHonorController::class,'activateTunjAkademik'])->name('activateTunjAkademik');
    Route::get('/leveldosen', [SettingHonorController::class,'showLevelDosen'])->name('showLevelDosen');
    Route::post('/leveldosen', [SettingHonorController::class,'simpanLevelDosen'])->name('simpanLevelDosen');
    Route::get('/honorpokok', [SettingHonorController::class,'showHonorPokok'])->name('showHonorPokok');
    Route::post('/honorpokok', [SettingHonorController::class,'simpanHonorPokok'])->name('simpanHonorPokok');
    Route::post('/activatepokok', [SettingHonorController::class,'activateHonorPokok'])->name('activateHonorPokok');
    
     //Add Profil Dosen
     Route::get('/adddosen', [ProfilController::class, 'showAddProfile'])->name('showAddProfile');
     Route::post('/adddosen', [ProfilController::class, 'addProfile'])->name('addProfile'); 
    //profil dosen
    Route::get('/show-profile', [ProfilController::class, 'profilDashboard'])->name('profilDashboard');
    Route::get('/profil/fetchData', [ProfilController::class, 'fetchData']);
    Route::get('/findDosen', [ProfilController::class, 'findDosen1'])->name('findDosen1');
    Route::post('/profil/approve', [ProfilController::class,'approveData']);
    Route::post('/profil/reject', [ProfilController::class,'rejectData']);
    Route::get('/input', [ProfilController::class, 'profilInput'])->name('profilInput');
    Route::get('/fetchProfile', [ProfilController::class, 'fetchProfile'])->name('fetchProfile');
    //pendidikan dosen
    Route::post('input/hapusDataPendidikan', [ProfilController::class,'deletePendidikan']);
    Route::get('input/ubahpendidikan', [ProfilController::class,'ubahPendidikan']);
    Route::post('/ubah', [ProfilController::class,'editPendidikan'])->name('editPendidikan');
    Route::get('input/tambahpendidikan', [ProfilController::class,'showPendidikan'])->name('showPendidikan');
    Route::post('/tambahpendidikan', [ProfilController::class,'tambahPendidikan'])->name('tambahPendidikan');
    //mata kuliah dosen
    Route::post('input/hapusMataKuliah', [ProfilController::class,'deleteMatkul']);
    Route::get('input/tambahmatakuliah', [ProfilController::class,'showMatkul'])->name('showMatkul');
    Route::post('input/tambahmatkul', [ProfilController::class,'tambahMatkul'])->name('tambahMatkul');
    Route::get('/tambahmatakuliah/searchIdmk', [ProfilController::class, 'searchIdmk'])->name('searchIdmk');
    Route::post('input/ubahmatkul', [ProfilController::class,'editMataKuliah'])->name('editMataKuliah');
    Route::get('input/ubahmatakuliah', [ProfilController::class,'ubahMataKuliah']);
    Route::get('/input/ubahmatakuliah/searchMatkul', [ProfilController::class, 'searchMatkul'])->name('searchMatkul');
    //edit profil dosen
    Route::get('input/editprofil', [ProfilController::class,'showEdit'])->name('showEdit');
    Route::post('input/edit-profile',[ProfilController::class,'editProfile'])->name('editProfile');
    // fungsionoris dosen
    Route::get('input/tambahfungsi', [ProfilController::class,'showFungsi']);
    Route::post('/tambah', [ProfilController::class,'tambahFungsi'])->name('tambahFungsi');
    Route::post('input/hapusFungsi', [ProfilController::class,'deleteFungsi']);
    Route::get('input/ubahfungsi', [ProfilController::class,'ubahFungsi']);
    Route::post('/editfungsi',[ProfilController::class,'editFungsi'])->name('editFungsi');
    //aktifitas dosen
    Route::get('input/tambahaktifitas', [ProfilController::class,'showAktifitas'])->name('showAktifitas');
    Route::post('/tambahaktifitas', [ProfilController::class,'tambahAktifitas'])->name('tambahAktifitas');
    Route::post('input/hapusAktifitas', [ProfilController::class,'deleteAktifitas'])->name('deleteAktifitas');
    Route::get('input/ubahaktifitas', [ProfilController::class,'ubahAktifitas'])->name('ubahAktifitas');
    Route::post('/editaktifitas', [ProfilController::class,'editAktifitas'])->name('editAktifitas');
    //SERTIFIKASI dosen
    Route::get('input/tambahsertifikasi', [ProfilController::class,'showSertifikasi'])->name('showSertifikasi');
    Route::post('/tambahsertifikasi', [ProfilController::class,'tambahSertifikasi'])->name('tambahSertifikasi');
    Route::post('input/hapussertifikasi', [ProfilController::class,'deleteSertifikasi'])->name('deleteSertifikasi');
    Route::get('input/ubahsertifikasi', [ProfilController::class,'ubahSertifikasi'])->name('ubahSertifikasi');
    Route::post('/editsertifikasi', [ProfilController::class,'editSertifikasi'])->name('editSertifikasi');
    //Penelitian dosen
    Route::get('input/tambahpenelitian', [ProfilController::class,'showPenelitian'])->name('showPenelitian');
    Route::post('/tambahpenelitian', [ProfilController::class,'tambahPenelitian'])->name('tambahPenelitian');
    Route::post('input/hapuspenelitian', [ProfilController::class,'deletePenelitian'])->name('deletePenelitian');
    Route::get('input/ubahpenelitian', [ProfilController::class,'ubahPenelitian'])->name('ubahPenelitian');
    Route::post('/editpenelitian', [ProfilController::class,'editPenelitian'])->name('editPenelitian');
    //penunjang dosen
    Route::get('input/tambahpenunjang', [ProfilController::class,'showPenunjang'])->name('showPenunjang');
    Route::post('/tambahpenunjang', [ProfilController::class,'tambahPenunjang'])->name('tambahPenunjang');
    Route::post('input/hapuspenunjang', [ProfilController::class,'deletePenunjang'])->name('deletePenunjang');
    Route::get('input/ubahpenunjang', [ProfilController::class,'ubahPenunjang'])->name('ubahPenunjang');
    Route::post('/editpenunjang', [ProfilController::class,'editPenunjang'])->name('editPenunjang');
    //Inpassing DOSEN
    Route::get('input/tambahinpassing', [ProfilController::class,'showInpassing'])->name('showInpassing');
    Route::post('/tambahinpassing', [ProfilController::class,'tambahInpassing'])->name('tambahInpassing');
    Route::post('input/hapusinpassing', [ProfilController::class,'deleteInpassing'])->name('deleteInpassing');
    Route::get('input/ubahinpassing', [ProfilController::class,'ubahInpassing'])->name('ubahInpassing');
    Route::post('/editinpassing', [ProfilController::class,'editInpassing'])->name('editInpassing');
    //JABATAN DOSEN
    Route::get('input/tambahjabatan', [ProfilController::class,'showJabatan'])->name('showJabatan');
    Route::post('/tambahjabatan', [ProfilController::class,'tambahJabatan'])->name('tambahJabatan');
    Route::post('input/hapusjabatan', [ProfilController::class,'deleteJabatan'])->name('deleteJabatan');
    Route::get('input/ubahjabatan', [ProfilController::class,'ubahJabatan'])->name('ubahJabatan');
    Route::post('/ubahjabatan', [ProfilController::class,'editJabatan'])->name('editJabatan');
    //Ijin Belajar Dosen
    Route::get('input/tambahijinbelajar', [ProfilController::class,'showIjinBelajar'])->name('showIjinBelajar');
    Route::post('/tambahijinbelajar', [ProfilController::class,'tambahIjinBelajar'])->name('tambahIjinBelajar');
    Route::post('input/hapusijinbelajar', [ProfilController::class,'deleteIjinBelajar'])->name('deleteIjinBelajar');
    Route::get('input/ubahijinbelajar', [ProfilController::class,'ubahIjinBelajar'])->name('ubahIjinBelajar');
    Route::post('/editijinbelajar', [ProfilController::class,'editIjinBelajar'])->name('editIjinBelajar');
    //Tugas Belajar Dosen
    Route::get('input/tambahtugasbelajar', [ProfilController::class,'showTugasBelajar'])->name('showTugasBelajar');
    Route::post('/tambahtugasbelajar', [ProfilController::class,'tambahTugasBelajar'])->name('tambahTugasBelajar');
    Route::post('input/hapustugasbelajar', [ProfilController::class,'deleteTugasBelajar'])->name('deleteTugasBelajar');
    Route::get('input/ubahtugasbelajar', [ProfilController::class,'ubahTugasBelajar'])->name('ubahTugasBelajar');
    Route::post('/edittugasbelajar', [ProfilController::class,'editTugasBelajar'])->name('editTugasBelajar');
    //Dosen Kelompok Mata Kuliah
    Route::get('input/tambahdosenkelompok', [ProfilController::class,'showDosenKelompok'])->name('showDosenKelompok');
    Route::post('/tambahdosenkelompok', [ProfilController::class,'tambahDosenKelompok'])->name('tambahDosenKelompok');
    Route::post('input/hapusdosenkelompok', [ProfilController::class,'deleteDosenKelompok'])->name('deleteDosenKelompok');
    Route::get('input/ubahdosenkelompok', [ProfilController::class,'ubahDosenKelompok'])->name('ubahDosenKelompok');
    Route::post('/editdosenkelompok', [ProfilController::class,'editDosenKelompok'])->name('editDosenKelompok');
    //Dosen Pembimbing
    Route::get('input/tambahdosenpembimbing', [ProfilController::class,'showDosenPembimbing'])->name('showDosenPembimbing');
    Route::post('/tambahdosenpembimbing', [ProfilController::class,'tambahDosenPembimbing'])->name('tambahDosenPembimbing');
    Route::post('input/hapusdosenpembimbing', [ProfilController::class,'deleteDosenPembimbing'])->name('deleteDosenPembimbing');
    Route::get('input/ubahdosenpembimbing', [ProfilController::class,'ubahDosenPembimbing'])->name('ubahDosenPembimbing');
    Route::post('/editdosenpembimbing', [ProfilController::class,'editDosenPembimbing'])->name('editDosenPembimbing');
    //Dosen Pengabdian
    Route::get('input/tambahpengabdian', [ProfilController::class,'showPengabdian'])->name('showPengabdian');
    Route::post('/tambahpengabdian', [ProfilController::class,'tambahPengabdian'])->name('tambahPengabdian');
    Route::post('input/hapuspengabdian', [ProfilController::class,'deletePengabdian'])->name('deletePengabdian');
    Route::get('input/ubahpengabdian', [ProfilController::class,'ubahPengabdian'])->name('ubahPengabdian');
    Route::post('/editpengabdian', [ProfilController::class,'editPengabdian'])->name('editPengabdian');
    //Hibah Dosen
    Route::get('input/tambahhibah', [ProfilController::class,'showHibah'])->name('showHibah');
    Route::post('/tambahhibah', [ProfilController::class,'tambahHibah'])->name('tambahHibah');
    Route::post('input/hapushibah', [ProfilController::class,'deleteHibah'])->name('deleteHibah');
    Route::get('input/ubahhibah', [ProfilController::class,'ubahHibah'])->name('ubahHibah');
    Route::post('/edithibah', [ProfilController::class,'editHibah'])->name('editHibah');
    //Rekap Dosen Aktif
    Route::get('/profiledosen', [PropertyTypeController::class, 'showPPD'])->name('showPPD');
    Route::post('/profiledosen', [PropertyTypeController::class, 'detailProfileDosen'])->name('detail.keuangan');
    //Summary KRS
    Route::get('/summarykrs', [KrsMahasiswaController::class,'showSummary'])->name('showSummary');
    Route::post('/summarykrs', [KrsMahasiswaController::class,'SummaryKRS'])->name('SummaryKRS');
    Route::get('summarykrs/fetchFakultas', [PropertyTypeController::class, 'fetchFakultas'])->name('fetchFakultas');
    //Rincian KRS
    Route::get('/rinciankrs', [KrsMahasiswaController::class,'showRincian'])->name('showRincian');
    Route::post('/rinciankrs', [KrsMahasiswaController::class,'rincianKRS'])->name('rincianKRS');
    Route::get('rinciankrs/fetchFakultas', [PropertyTypeController::class, 'fetchFakultas'])->name('fetchFakultas');

    //Cetak KRS
    Route::get('/cetakkrs', [KrsMahasiswaController::class,'showCetakKRS'])->name('showCetakKRS');
    Route::get('cetakkrs/showMahasiswa', [KrsMahasiswaController::class,'showMahasiswa']);
    Route::post('/viewcetakkrs', [KrsMahasiswaController::class,'cetakKRS'])->name('cetakKRS');
    //Mahasiswa
    Route::get('cetakkrs/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');
    //Data Fungsionaris 
    Route::get('formkaprodi/findDosen', [FungsionarisController::class, 'findDosen'])->name('findDosen');
    Route::get('/formkaprodi', [FungsionarisController::class, 'formKaprodi'])->name('formKaprodi');
    Route::post('/formkaprodi', [FungsionarisController::class, 'insertJabatan'])->name('insertJabatan');
    Route::get('/viewjabatan', [FungsionarisController::class, 'showViewJabatan'])->name('showViewJabatan');
    Route::get('formkaprodi/fetchFakultas', [PropertyTypeController::class, 'fetchFakultas'])->name('fetchFakultas');
    //CMS
    Route::get('/showjabatanfungsi', [JabatanFungsiController::class,'showJabatanFungsi'])->name('showJabatanFungsi');
    Route::post('/showjabatanfungsi', [JabatanFungsiController::class,'simpanJabatan'])->name('simpanJabatan');
    //Kurikulum
    Route::get('/kurikulum', [KurikulumController::class,'showKurikulum'])->name('showKurikulum');
    Route::post('/kurikulum', [KurikulumController::class,'viewKurikulum'])->name('viewKurikulum');
    Route::get('/tambahkurikulum', [KurikulumController::class,'showTambahKurikulum'])->name('showTambahKurikulum');
    Route::get('/searchIdmk', [KurikulumController::class, 'searchIdmk'])->name('searchIdmk');
    Route::get('/fetchFakultash', [KurikulumController::class, 'fetchFakultash'])->name('fetchFakultash');
    Route::post('/tambahkurikulum', [KurikulumController::class, 'tambahKurikulum'])->name('tambahKurikulum');
    Route::get('/edit-kurikulum/{id}', [KurikulumController::class, 'editKurikulum'])->name('editKurikulum');
    Route::post('/delete-kurikulum', [KurikulumController::class, 'deleteKurikulum'])->name('deleteKurikulum');
    Route::post('/updateKurikulum/{id}', [KurikulumController::class, 'updateKurikulum'])->name('updateKurikulum');

    //Konversi Nilai
    Route::get('/konversinilai', [KrsMahasiswaController::class,'showKonversi'])->name('showKonversi');
    Route::get('konversinilai/showKonversiNilai', [KrsMahasiswaController::class,'showKonversiNilai']);
    Route::post('/viewkonversinilai', [KrsMahasiswaController::class,'cetakTranskripNilai'])->name('cetakTranskripNilai');
    Route::get('konversinilai/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');
    //Cetak LDIKTI
    Route::get('/LDIKTI', [KrsMahasiswaController::class, 'showLDIKTI'])->name('showLDIKTI');
    Route::post('/LDIKTI', [KrsMahasiswaController::class, 'cetakLDIKTI'])->name('cetakLDIKTI');
    Route::get('/detailLDIKTI', [KrsMahasiswaController::class, 'detailLDIKTI'])->name('detailLDIKTI');
    Route::post('/searchLDIKTI', [KrsMahasiswaController::class,'searchLDIKTI'])->name('searchLDIKTI');
    //Input Konversi Nilai
    Route::get('/tambahkonversi', [KrsMahasiswaController::class,'showTambahKonversi'])->name('showTambahKonversi');
    Route::get('tambahkonversi/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');
    Route::get('tambahkonversi/showInputKonversiNilai', [KrsMahasiswaController::class,'showInputKonversiNilai']);
    Route::post('/viewtambahkonversinilai', [KrsMahasiswaController::class,'viewTambahKonversi'])->name('viewTambahKonversi');
    Route::post('viewtambahkonversinilai/delete', [KrsMahasiswaController::class,'deleteKonversiNilai'])->name('deleteKonversiNilai');
    Route::get('viewtambahkonversinilai/searchMatakuliah', [KrsMahasiswaController::class,'searchMatkul'])->name('searchMatkul');
    Route::post('viewtambahkonversinilai/simpanData', [KrsMahasiswaController::class,'simpanKonversi'])->name('simpanKonversi');
    Route::get('viewtambahkonversinilai/edit', [KrsMahasiswaController::class,'editKonversi'])->name('editKonversi');
    Route::post('/edit', [KrsMahasiswaController::class,'sendEdit'])->name('sendEdit');
    Route::get('/changed', [KrsMahasiswaController::class,'changeView'])->name('changeView');
    //sethonor
    Route::get('/showsethonor', [SettingHonorController::class,'showSettingHonor'])->name('showSettingHonor');
    Route::post('/showsethonor', [SettingHonorController::class,'simpanSettingHonor'])->name('simpanSettingHonor');
    Route::post('/activate-honor-sks', [SettingHonorController::class,'activateHonorSks'])->name('activateHonorSks');
    //sethonorS1
    Route::get('/showsethonors1', [SettingHonorController::class,'showSettingHonorS1'])->name('showSettingHonorS1');
    Route::post('/showsethonors1', [SettingHonorController::class,'simpanSettingHonorS1'])->name('simpanSettingHonorS1');
    Route::post('/activate-honor-s1', [SettingHonorController::class,'activateHonorS1'])->name('activateHonorS1');
    //tunjangan doktor
    Route::get('/tunjdoktor', [SettingHonorController::class,'showTunjanganDoktor'])->name('showTunjanganDoktor');
    Route::post('/tunjdoktor', [SettingHonorController::class,'simpanTunganDoktor'])->name('simpanTunganDoktor');
    Route::post('/activatedoktor', [SettingHonorController::class,'activateTunganDoktor'])->name('activateTunganDoktor'); 
    //Jadwal
    Route::get('/jadwal', [JadwalController::class,'showJadwal'])->name('showJadwal');
    Route::get('/jadwaladmin', [JadwalController::class,'showJadwalAdmin'])->name('showJadwalAdmin'); 
    Route::get('/fetchJadwal', [JadwalController::class,'fetchJadwal'])->name('fetchJadwal');
    Route::get('/fetchFakultas', [JadwalController::class, 'fetchFakultas'])->name('fetchFakultas');
    Route::post('/getKelas', [JadwalController::class, 'getKelas'])->name('getKelas');
    Route::post('/getIDMK', [JadwalController::class, 'getIDMK'])->name('getIDMK');
    Route::post('/getRuangJam', [JadwalController::class, 'getRuang'])->name('getRuang');
    Route::post('/getHonor', [JadwalController::class, 'getHonor'])->name('getHonor'); 
    Route::post('/getDosen2', [JadwalController::class, 'getDosen2'])->name('getDosen2');
    Route::post('/getDosen3', [JadwalController::class, 'getDosen3'])->name('getDosen3');
    Route::post('/getGabungan', [JadwalController::class, 'getGabungan'])->name('getGabungan');
    Route::post('/getProdiGabungan', [JadwalController::class, 'getProdiGabungan'])->name('getProdiGabungan');
    Route::post('/getKurikulum', [JadwalController::class, 'getKurikulum'])->name('getKurikulum'); 
    Route::post('/simpan-data', [JadwalController::class, 'simpan'])->name('simpan.data'); 
    Route::post('/validateJadwal', [JadwalController::class, 'validateJadwal'])->name('validateJadwal');
    Route::post('/validateAllByDay', [JadwalController::class, 'validateAllByDay'])->name('validateAllByDay');
    Route::post('/deleteJadwal', [JadwalController::class, 'deleteJadwal'])->name('deleteJadwal');
    Route::get('/showreport', [JadwalController::class, 'showReportJadwal'])->name('showReportJadwal');
    Route::post('/showreport', [JadwalController::class, 'OrderbyReport'])->name('OrderbyReport'); 
  //UPPS
    Route::get('/uppsfakultas', [AkreditasiController::class,'showUPPSFakultas'])->name('showUPPSFakultas');
    Route::post('/viewuppsfakultas', [AkreditasiController::class,'uppsFakultas'])->name('uppsFakultas');
    Route::get('/upps', [AkreditasiController::class,'showUPPS'])->name('showUPPS');
    Route::post('/viewupps', [AkreditasiController::class,'viewUPPS'])->name('viewUPPS');
    //ipk Lulusan
    Route::get('/ipklulusan', [AkreditasiController::class,'showIPKLulusan'])->name('showIPKLulusan');
    Route::post('/ipklulusan', [AkreditasiController::class,'IPKLulusan'])->name('IPKLulusan');
  //ipklulusan prodi
    Route::get('/ipkprodi', [AkreditasiController::class,'showIPKProdi'])->name('showIPKProdi');
    Route::post('/ipkprodi', [AkreditasiController::class,'HitungIPK'])->name('HitungIPK');
    Route::get('/ipklulusanprodi', [AkreditasiController::class,'showIPKPPRODI'])->name('showIPKPPRODI');
    Route::post('/ipklulusanprodi', [AkreditasiController::class,'IPKLulusanPPRODI'])->name('IPKLulusanPPRODI');
    Route::get('/ipklulusanregulerprodi', [AkreditasiController::class,'showIPKPPRODIRegular'])->name('showIPKPPRODIRegular');
    Route::post('/ipklulusanregulerprodi', [AkreditasiController::class,'IPKLulusanPPRODIRegular'])->name('IPKLulusanPPRODIRegular');
    Route::get('/rekapmahasiswakaryawan', [AkreditasiController::class,'showRekapKaryawanProdi'])->name('form.karyawan');
    Route::post('/rekapmahasiswakaryawan', [AkreditasiController::class,'viewRekapKaryawanProdi'])->name('view.karyawan');
    Route::get('/detailmahasiswakaryawan', [AkreditasiController::class,'detailKaryawanAktif'])->name('detail.karyawan');
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
    //PMB
    Route::get('/monitoringpmb', [PMBController::class,'showMonitoringPMB'])->name('showMonitoringPMB');
    Route::post('/monitoringpmb', [PMBController::class,'viewMonitoringPMB'])->name('viewMonitoringPMB');
    Route::get('/grafikpmb', [PMBController::class,'showGrafikPMB'])->name('showGrafikPMB');
    Route::post('/grafikpmb', [PMBController::class,'viewGrafikPMB'])->name('viewGrafikPMB');
    Route::get('/datacalon', [PMBController::class,'showDataCalonMahasiswa'])->name('showDataCalonMahasiswa');
    Route::post('/datacalon', [PMBController::class,'viewDataCalonMahasiswa'])->name('viewDataCalonMahasiswa');
    Route::get('/datacalons2', [PMBController::class,'showDataCalonMahasiswas2'])->name('showDataCalonMahasiswas2');
    Route::post('/datacalons2', [PMBController::class,'viewDataCalonMahasiswas2'])->name('viewDataCalonMahasiswas2');

    //Absensi Dosen
    Route::get('/absensidosen', [AbsensiController::class,'formRekapAbsensiDosen'])->name('formRekapAbsensiDosen');
    Route::post('/absensidosen', [AbsensiController::class,'rekapAbsensiDosen'])->name('rekapAbsensiDosen');
    //Mahasiswa
    Route::get('/findMahasiswa', [MahasiswaController::class,'findMahasiswa'])->name('findMahasiswa');
    Route::get('/profilemahasiswa', [MahasiswaController::class,'showProfileMahasiswa'])->name('showProfileMahasiswa'); 
    Route::post('/profilemahasiswa', [MahasiswaController::class,'viewProfileMahasiswa'])->name('viewProfileMahasiswa');
    Route::post('/updateprofilemahasiswa', [MahasiswaController::class,'updateProfileMahasiswa'])->name('updateProfileMahasiswa');

    //Matakuliah
    Route::get('/showmatkul', [MatakuliahController::class,'showMatakuliah'])->name('showMatakuliah'); 
    Route::post('/showmatkul', [MatakuliahController::class, 'viewMatakuliah'])->name('viewMatakuliah');
    Route::post('/search-matakuliah', [MatakuliahController::class, 'searchMatakuliah'])->name('searchMatakuliah');
    Route::get('/detailmatkul', [MatakuliahController::class, 'detailMataKuliah'])->name('detailMataKuliah');
    Route::post('/matakuliah/update', [MatakuliahController::class, 'updateMatakuliah'])->name('matakuliah.update'); 
    Route::post('/editpengampu', [MatakuliahController::class, 'editPengampu'])->name('matakuliah.editPengampu');
    Route::get('/matakuliah/add-pengampu', [MatakuliahController::class, 'tambahPengampu'])->name('matakuliah.addPengampu');
    Route::post('/matakuliah/add-pengampu', [MatakuliahController::class, 'addPengampu'])->name('matakuliah.addPengampu');
    Route::post('/updatepengampu', [MatakuliahController::class, 'updatePengampu'])->name('updatePengampu');
    Route::get('/addmatkul', [MatakuliahController::class, 'addMataKuliah'])->name('addMataKuliah'); 
    Route::post('/store-matakuliah', [MatakuliahController::class, 'tambahMatakuliah'])->name('tambahMatakuliah');

    //Kartu Mengajar
    Route::get('/showkartu', [DosenController::class, 'showKartuMengajar'])->name('showKartuMengajar'); 
    Route::post('/getHonorsks', [DosenController::class, 'getHonorSKS'])->name('getHonorSKS');
    Route::post('/showkartu', [DosenController::class, 'viewKartuMengajar'])->name('viewKartuMengajar'); 
     //Alumni
     Route::get('/showalumni', [AlumniController::class, 'showAlumni'])->name('showAlumni'); 
     Route::post('/showalumni', [AlumniController::class, 'viewAlumni'])->name('viewAlumni'); 
});


