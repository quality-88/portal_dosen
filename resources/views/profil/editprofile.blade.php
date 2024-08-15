@extends('admin.dashboard')

@section('admin')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h4 class="mb-4">Edit Profile Dosen</h4>
    
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3"></div>
                   
                        <form method="POST" action="{{ route('editProfile') }}" class="forms-sample">
                            @csrf
                            <table width ="100%">
                                <tr>
                                    <td><label class="form-label">ID Dosen</label></td>
                                    <td> <input type="text" class="form-control" name="iddosen" value="{{ $dosen->IDDOSEN ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">ID Finger</label></td>
                                    <td> <input type="text" class="form-control" name="idfp" value="{{ $dosen->IDFP ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">ID Dosen2</label></td>
                                    <td> <input type="text" class="form-control" name="idfp" value="{{ $dosen->iddosen2 ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama</label></td>
                                    <td> <input type="text" class="form-control" name="nama" value="{{ $dosen->NAMA ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama Output</label></td>
                                    <td> <input type="text" class="form-control" name="namaoutput" value="{{ $dosen->NAMAOUTPUT ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama Gelar</label></td>
                                    <td> <input type="text" class="form-control" name="namagelar" value="{{ $dosen->NAMAGELAR ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Gelar Depan</label></td>
                                    <td> <input type="text" class="form-control" name="gd" value="{{ $dosen->GD ?? '' }}"></td>
                                    
                                </tr>
                                <tr>
                                    <td><label class="form-label">Gelar Belakang</label></td>
                                    <td> <input type="text" class="form-control" name="gb" value="{{ $dosen->GB ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Tanggal Lahir</label></td>
                                    <td>
                                        <input type="text" class="form-control" id="tanggallahir" name="tanggallahir" placeholder="Tanggal" data-date-format="Y-m-d" value="{{ $dosen->TGLLAHIR }}" required>
                                        
                                    </td>
                                </tr>
                                
                                <tr>                                
                                    <td><label class="form-label">Tempat Lahir</label></td>
                                    <td> <input type="text" class="form-control" name="tempatlahir" value="{{ $dosen->TEMPATLAHIR ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Jenis Kelamin</label></td>
                                    <td>
                                        <select class="form-control" name="jeniskelamin">
                                            <option value="M" {{ $dosen->JENISKELAMIN == 'M' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="F" {{ $dosen->JENISKELAMIN == 'F' ? 'selected' : '' }}>Perempuan</option>
                                            <option value="O" {{ $dosen->JENISKELAMIN == 'N' ? 'selected' : '' }}>Janda/Duda</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Golongan Darah</label></td>
                                    <td>
                                        <select class="form-select" id="goldarah" name="goldarah" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Golongan Darah...</option>
                                            @foreach($allGoldarah as $golongandarah)
                                                <option value="{{ $golongandarah->golongandarah }}" 
                                                    {{ $golongandarah->golongandarah == $dosen->GOLDARAH ? 'selected' : '' }}>
                                                    {{ $golongandarah->golongandarah }}
                                                </option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Agama</label></td>
                                    <td>
                                        <select class="form-select" id="agama" name="agama" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Agama......</option>
                                            @foreach($allAgama as $agama)
                                                <option value="{{ $agama->agama }}" 
                                                    {{ $agama->agama == $dosen->AGAMA ? 'selected' : '' }}>
                                                    {{ $agama->agama }}
                                                </option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Alamat</label></td>
                                    <td><input type="text" class="form-control" name="alamat"  value="{{ $dosen->ALAMAT ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Telepon</label></td>
                                    <td><input type="text" class="form-control" name="telepon" value="{{ $dosen->TELEPON ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nomor HP</label></td>
                                    <td><input type="text" class="form-control"  name="handphone" value="{{ $dosen->HP ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Email Universitas</label></td>
                                    <td><input type="text" class="form-control" name="Email"  value="{{ $dosen->EMAILDOSEN ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Email Pribadi</label></td>
                                    <td><input type="text" class="form-control" name="emailpribadi"  value="{{ $dosen->EMAILPRIBADI ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td>  <label class="form-label">NO REK</label></td>
                                    <td><input type="text" class="form-control" name="nomor_rek"  value="{{ $dosen->NOACBANK ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NIK</label> </td>
                                    <td><input type="text" class="form-control"  name="nik" value="{{ $dosen->NOKTP ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NPWP</label> </td>
                                    <td><input type="text" class="form-control"  name="npwp" value="{{ $dosen->NPWP ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">BPJS Ketenagakerjaan</label> </td>
                                    <td><input type="text" class="form-control" name="ketenagakerjaan"  value="{{ $dosen->Ketenagakerjaan ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">BPJS Kesehatan</label></td>
                                    <td><input type="text" class="form-control" name="kesehatan"  value="{{ $dosen->Kesehatan ?? '' }}" ></td>
                                </tr>

                                <tr>
                                    <td> <label class="form-label">Status Pernikahan</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="relationship_status">
                                            <option value="M" {{ $dosen->RELATIONSHIPSTATUS == 'M' ? 'selected' : '' }}>Menikah</option>
                                            <option value="B" {{ $dosen->RELATIONSHIPSTATUS == 'B' ? 'selected' : '' }}>Belum Menikah</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jumlah Tanggungan</label> </td>
                                    <td><input type="text" class="form-control"  name="jlhtanggungan" value="{{ $dosen->jlhtanggungan ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama Ibu</label> </td>
                                    <td><input type="text" class="form-control"  name="namaibu" value="{{ $dosen->NamaIbu ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Username</label> </td>
                                    <td><input type="text" class="form-control"  name="username" value="{{ $dosen->LOGINUSERNAME ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Password</label> </td>
                                    <td><input type="text" class="form-control"  name="password" value="{{ $dosen->LOGINPASSWORD ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NIDN</label> </td>
                                    <td><input type="text" class="form-control"  name="nidn" value="{{ $dosen->NIDNNTBDOS ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Status Jabatan</label></td>
                                    <td>
                                        <select class="form-select" id="jabatan" name="jabatan" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Jabatan...</option>
                                            @foreach($allJabatan as $jabatan)
                                                <option value="{{ $jabatan->jabatan }}"
                                                     {{ $jabatan->jabatan == $dosen->STATUSJABATAN ? 'selected' : '' }}>
                                                    {{ $jabatan->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                
                               
                                <tr>
                                    <td><label class="form-label">Tanggal Register</label> </td>
                                    <td>
                                        <input type="text" class="form-control" id="tanggalgabung" name="tanggalgabung" data-date-format="Y-m-d" value="{{ $dosen->TGLGABUNG }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">TMT Dosen</label> </td>
                                    <td>
                                        <input type="text" class="form-control" id="tmt" name="tmt" placeholder="TMT" data-date-format="Y-m-d" value="{{ $dosen->TMTDosen }}" required>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Home Base</label> </td>
                                    <td><select class="form-select" id="homebase" name="homebase" aria-label="Default select example">
                                        <option value="" disabled selected>Choose Home Base...</option>
                                        
                                        @foreach($allHome as $homebase)
                                        <option value="{{ $homebase->homebase }}" {{ $homebase->homebase == $dosen->HomeBase ? 'selected' : '' }}>
                                            {{ $homebase->homebase }}
                                        </option>
                                        
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Lokasi Kampus</label> </td>
                                    <td><select class="form-select" id="lokasi" name="lokasi" aria-label="Default select example">
                                        <option value="" disabled selected>Choose Lokasi Kampus...</option>
                                        
                                        @foreach($allKampus as $lokasi)
                                        <option value="{{ $lokasi->lokasi }}" {{ $lokasi->lokasi == $dosen->ASALKOTA ? 'selected' : '' }}>
                                            {{ $lokasi->lokasi }}
                                        </option>
                                         
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jenis Dosen</label> </td>
                                    <td><select class="form-select" id="jenis" name="jenis" aria-label="Default select example">
                                        <option value="" disabled selected>Choose...</option>
                                        
                                        @foreach($allJenis as $nama)
                                        <option value="{{ $nama->nama }}" 
                                            {{ $nama->nama == $dosen->STATUSDOSEN ? 'selected' : '' }}>
                                            {{ $nama->nama }}
                                        </option>
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jenjang Pendidikan</label> </td>
                                    <td><select class="form-select" id="jenjang" name="jenjang" aria-label="Default select example">
                                        <option value="" disabled selected>Choose Pendidikan...</option>
                                        
                                        @foreach($allJenjang as $jenjangakademik)
                                        <option value="{{ $jenjangakademik->jenjangakademik }}" 
                                            {{ $jenjangakademik->jenjangakademik == $dosen->JENJANGAKADEMIK ? 'selected' : '' }}>
                                            {{ $jenjangakademik->jenjangakademik }}
                                        </option>
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jabatan</label></td>
                                    <td> 
                                        
                                        <select class="form-select" id="jabat" name="jabat" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Jabatan...</option>
                                            @foreach($allJabat as $jabatanakademik)
                                          <option value="{{ $jabatanakademik->jabatanakademik }}"
                                                  {{ $jabatanakademik->jabatanakademik == $dosen->JABATANAKADEMIK ? 'selected' : '' }}>
                                              {{ $jabatanakademik->jabatanakademik }}
                                          </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Golongan Dosen</label></td>
                                    <td>
                                        <select class="form-select" id="golongan" name="golongan" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Golongan...</option>
                                            @foreach($allGolongan as $golongan)
                                                <option value="{{ $golongan->golongan }}" data-kepangkatan="{{ $golongan->kepangkatan }}" 
                                                    {{ $golongan->golongan == $dosen->GOLONGAN ? 'selected' : '' }}>
                                                    {{ $golongan->golongan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td><label for="kepangkatan" class="form-label">Kepangkatan</label></td>
                                    <td><input type="text" class="form-control" id="kepangkatan" name="kepangkatan" 
                                        value="{{ $dosen->Kepangkatan ?? '' }}"readonly>
                                    </tr>
                                    <tr>
                                        <td><label class="form-label">Honor SKS S1</label></td>
                                        <td>
                                            <input type="text" class="form-control" name="honor"
                                             value="{{ isset($dosen->HONORSKS) ? number_format($dosen->HONORSKS, 0, ',', '.') : '' }}" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-label">Honor SKS S2</label></td>
                                        <td>
                                            <input type="text" class="form-control" name="honor"
                                             value="{{ isset($dosen->HONORSKSS2) ? number_format($dosen->HONORSKSS2, 0, ',', '.') : '' }}" readonly>
                                        </td>
                                    </tr> 
                                <tr>
                                    <td><label class="form-label">Tunjangan Pendidikan</label> </td>
                                    <td><input type="text" class="form-control"  name="tunjpendidikan" value="{{ $dosen->TUNJPENDIDIKAN ? number_format($dosen->TUNJPENDIDIKAN, 0, ',', '.') : '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Tunjangan Akademik</label> </td>
                                    <td><input type="text" class="form-control"  name="tunjakademik" value="{{ $dosen->TUNJAKADEMIK ? number_format($dosen->TUNJAKADEMIK, 0, ',', '.') : '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Cabang</label> </td>
                                    <td><input type="text" class="form-control"  name="cabang" value="{{ $dosen->CABANG ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Status Ke Aktifan</label> </td>
                                    <td>
                                        <select class="form-select" id="aktifan" name="aktifan" aria-label="Default select example">
                                            <option value="" disabled selected>Choose...</option>
                                            @foreach($allAktifan as $statusdosen)
                                                <option value="{{ $statusdosen->statusdosen }}" 
                                                    {{ $statusdosen->statusdosen == $dosen->StatusDosenAktif ? 'selected' : '' }}>
                                                    {{ $statusdosen->statusdosen }}
                                                </option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NIP</label> </td>
                                    <td><input type="text" class="form-control"  name="nip" value="{{ $dosen->NIP ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">No SK </label> </td>
                                    <td><input type="text" class="form-control"  name="skdosen" value="{{ $dosen->SK_Dosen ?? '' }}" ></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">SK Kepangkatan</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="sk_kepangkatan">
                                            <option value="Y" {{ $dosen->SKKepangkatan == 'Y' ? 'selected' : '' }}>Sudah Diserahkan</option>
                                            <option value="T" {{ $dosen->SKKepangkatan == 'T' ? 'selected' : '' }}>Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">SK Pengangkatan</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="sk_pengangkatan">
                                            <option value="Y" {{ $dosen->SKPengangkatan == 'Y' ? 'selected' : '' }}>Sudah</option>
                                            <option value="T" {{ $dosen->SKPengangkatan == 'T' ? 'selected' : '' }}>Belum </option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Ijazah</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="ijazah">
                                            <option value="Y" {{ $dosen->Ijazah == 'Y' ? 'selected' : '' }}>Sudah Diserahkan</option>
                                            <option value="T" {{ $dosen->Ijazah == 'T' ? 'selected' : '' }}>Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">CV</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="cv">
                                            <option value="Y" {{ $dosen->CV == 'Y' ? 'selected' : '' }}>Sudah Diserahkan</option>
                                            <option value="T" {{ $dosen->CV == 'T' ? 'selected' : '' }}>Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">KTP</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="ktp">
                                            <option value="Y" {{ $dosen->KTP == 'Y' ? 'selected' : '' }}>Sudah Diserahkan</option>
                                            <option value="T" {{ $dosen->KTP == 'T' ? 'selected' : '' }}>Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Pass Foto</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="passfoto">
                                            <option value="Y" {{ $dosen->PassFoto == 'Y' ? 'selected' : '' }}>Sudah Diserahkan</option>
                                            <option value="T" {{ $dosen->PassFoto == 'T' ? 'selected' : '' }}>Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Sertifikat Dosen</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="serdos">
                                            <option value="Y" {{ $dosen->SERDOS == 'Y' ? 'selected' : '' }}>Sudah Diserahkan</option>
                                            <option value="T" {{ $dosen->SERDOS == 'T' ? 'selected' : '' }}>Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                            </table>
                            <br><br>
                            <button type="submit" class="btn btn-primary">Edit Profil</button>    
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    
    
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        jQuery(document).ready(function ($) {
        // Initialize flatpickr untuk kolom tanggal
        flatpickr('#tanggallahir', {
            dateFormat: 'd-m-Y',
            defaultDate: '{{ date('d-m-Y', strtotime($dosen->TGLLAHIR)) }}',
            minDate: '01-01-1900', // Atur batas minimal jika diperlukan
            maxDate: 'today', // Atur batas maksimal ke hari ini
            yearRange: '1900:' + new Date().getFullYear(), // Rentang tahun mulai dari 1900 hingga tahun saat ini
        });
    });
    jQuery(document).ready(function ($) {
    // Initialize flatpickr untuk kolom tanggal
    flatpickr('#tmt', {
        dateFormat: 'd-m-Y',
        defaultDate: '{{ date('d-m-Y', strtotime($dosen->TMTDosen)) }}',
        minDate: '01-01-1900', // Atur batas minimal jika diperlukan
        maxDate: 'today', // Atur batas maksimal ke hari ini
        yearRange: '1900:' + new Date().getFullYear(), // Rentang tahun mulai dari 1900 hingga tahun saat ini
    });
});
jQuery(document).ready(function ($) {
    // Initialize flatpickr untuk kolom tanggal
    flatpickr('#tanggalgabung', {
        dateFormat: 'd-m-Y',
        defaultDate: '{{ date('d-m-Y', strtotime($dosen->TGLGABUNG)) }}',
        minDate: '01-01-1900', // Atur batas minimal jika diperlukan
        maxDate: 'today', // Atur batas maksimal ke hari ini
        yearRange: '1900:' + new Date().getFullYear(), // Rentang tahun mulai dari 1900 hingga tahun saat ini
    });
});
$('#jabatan').change(function () {
    var jabatan = $(this).find(':selected').val();
   
});
$('#jabat').change(function () {
    var jabat = $(this).find(':selected').val();
   
});
$('#jenis').change(function () {
    var jenis = $(this).find(':selected').val();
   
});
$('#home').change(function () {
    var home = $(this).find(':selected').val();
    
});
$('#lokasi').change(function () {
    var lokasi = $(this).find(':selected').val();
    
});
$('#jenjang').change(function () {
    var jenjang = $(this).find(':selected').val();
   
});
$('#aktifan').change(function () {
    var aktifan = $(this).find(':selected').val();
    
});
$('#agama').change(function () {
    var agama = $(this).find(':selected').val();
    
});
$('#goldarah').change(function () {
    var goldarah = $(this).find(':selected').val();
    
});
$('#golongan').change(function () {
    var golongan = $(this).find(':selected').val();
    var kepangkatan = $(this).find(':selected').data('kepangkatan');
   // console.log('Golongan:', golongan);
    //console.log('Keterangan:', kepangkatan);
    $("#kepangkatan").val(kepangkatan);
});
$(document).ready(function() {
    $('form').submit(function(event) {
        event.preventDefault(); // Mencegah form untuk disubmit secara normal
        
        var formData = $(this).serialize(); // Meng-serialize data form
        //console.log('Data form:', formData); // Mencetak data form
        
        // Melakukan request AJAX
        $.ajax({
            url: $(this).attr('action'), // URL dari atribut action form
            method: $(this).attr('method'), // Metode dari atribut method form
            data: formData, // Data form
            success: function(response) {
                console.log('Respons sukses:', response); // Mencetak respons sukses
                
                // Memeriksa apakah respons mengandung pesan sukses
                if (response && response.success) {
                    // Menampilkan notifikasi SweetAlert
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.success,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Mengarahkan pengguna ke halaman profilInput
                        window.location.href = "{{ route('profilInput') }}";
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error); // Mencetak error AJAX
            }
        });
    });
});

</script>
@endsection