@extends('admin.dashboard')

@section('admin')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Add Profile Dosen</h4>
                    <div class="mb-3"></div>
                   
                        <form method="POST" action="{{ route('addProfile') }}" class="forms-sample">
                            @csrf
                            <table width ="100%">
                                <tr>
                                    <td><label class="form-label">ID Dosen</label></td>
                                    <td><input type="text" class="form-control" id="iddosen" name="iddosen" value="{{ $newIdDosen }}" readonly required><td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">ID Finger</label></td>
                                    <td><input type="text" class="form-control" id="idfp" name="idfp" value="{{ $newIdDosen }}" readonly required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">ID Dosen2</label></td>
                                    <td><input type="text" class="form-control" id="iddosen2" name="iddosen2" value="{{ $newIdDosen }}" readonly required ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama</label></td>
                                    <td> <input type="text" class="form-control" name="nama"required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama Output</label></td>
                                    <td> <input type="text" class="form-control" name="namaoutput" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama Gelar</label></td>
                                    <td> <input type="text" class="form-control" name="namagelar" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Gelar Depan</label></td>
                                    <td> <input type="text" class="form-control" name="gd" required></td>
                                    
                                </tr>
                                <tr>
                                    <td><label class="form-label">Gelar Belakang</label></td>
                                    <td> <input type="text" class="form-control" name="gb" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Tanggal Lahir</label></td>
                                    <td>
                                        <input type="text" class="form-control" id="tanggallahir" name="tanggallahir" 
                                        placeholder="Tanggal" data-date-format="Y-m-d"  required>
                                        
                                    </td>
                                </tr>
                                
                                <tr>                                
                                    <td><label class="form-label">Tempat Lahir</label></td>
                                    <td> <input type="text" class="form-control" name="tempatlahir" required></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Jenis Kelamin</label></td>
                                    <td>
                                        <select class="form-control" name="jeniskelamin" required>
                                            <option value="" disabled selected>Choose Gender......</option>
                                            <option value="M">Laki-Laki</option>
                                            <option value="F">Perempuan</option>
                                            <option value="O">Janda/Duda</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Golongan Darah</label></td>
                                    <td>
                                        <select class="form-select" id="goldarah" name="goldarah" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose Golongan Darah...</option>
                                            @foreach($allGoldarah as $golongandarah)
                                                <option value="{{ $golongandarah->golongandarah }}" >
                                                    {{ $golongandarah->golongandarah }}
                                                </option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Agama</label></td>
                                    <td>
                                        <select class="form-select" id="agama" name="agama" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose Agama......</option>
                                            @foreach($allAgama as $agama)
                                                <option value="{{ $agama->agama }}">
                                                    {{ $agama->agama }}
                                                </option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Alamat</label></td>
                                    <td><input type="text" class="form-control" name="alamat" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Telepon</label></td>
                                    <td><input type="text" class="form-control" name="telepon" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nomor HP</label></td>
                                    <td><input type="text" class="form-control"  name="handphone" required></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Email Universitas</label></td>
                                    <td><input type="text" class="form-control" name="Email" required></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Email Pribadi</label></td>
                                    <td><input type="text" class="form-control" name="emailpribadi" required></td>
                                </tr>
                                <tr>
                                    <td>  <label class="form-label">NO REK</label></td>
                                    <td><input type="text" class="form-control" name="nomor_rek" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NIK</label> </td>
                                    <td><input type="text" class="form-control"  name="nik" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NPWP</label> </td>
                                    <td><input type="text" class="form-control"  name="npwp" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">BPJS Ketenagakerjaan</label> </td>
                                    <td><input type="text" class="form-control" name="ketenagakerjaan" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">BPJS Kesehatan</label></td>
                                    <td><input type="text" class="form-control" name="kesehatan" required></td>
                                </tr>

                                <tr>
                                    <td> <label class="form-label">Status Pernikahan</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="relationship_status" required>
                                            <option value="" disabled selected>Choose Status......</option>
                                            <option value="M">Menikah</option>
                                            <option value="B">Belum Menikah</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jumlah Tanggungan</label> </td>
                                    <td><input type="text" class="form-control"  name="jlhtanggungan" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Nama Ibu</label> </td>
                                    <td><input type="text" class="form-control"  name="namaibu" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Username</label> </td>
                                    <td><input type="text" class="form-control"  name="username" value="{{ $newIdDosen }}" readonly required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Password</label> </td>
                                    <td><input type="text" class="form-control"  name="password" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NIDN</label> </td>
                                    <td><input type="text" class="form-control"  name="nidn" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Status Jabatan</label></td>
                                    <td>
                                        <select class="form-select" id="jabatan" name="jabatan" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose Jabatan...</option>
                                            @foreach($allJabatan as $jabatan)
                                                <option value="{{ $jabatan->jabatan }}">
                                                    {{ $jabatan->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                
                               
                                <tr>
                                    <td><label class="form-label">Tanggal Register</label> </td>
                                    <td>
                                        <input type="text" class="form-control" id="tanggalgabung" name="tanggalgabung" data-date-format="Y-m-d" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">TMT Dosen</label> </td>
                                    <td>
                                        <input type="text" class="form-control" id="tmt" name="tmt" placeholder="TMT" data-date-format="Y-m-d" required>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Home Base</label> </td>
                                    <td><select class="form-select" id="homebase" name="homebase" aria-label="Default select example" required>
                                        <option value="" disabled selected>Choose Home Base...</option>
                                        
                                        @foreach($allHome as $homebase)
                                        <option value="{{ $homebase->homebase }}">
                                            {{ $homebase->homebase }}
                                        </option>
                                        
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Lokasi Kampus</label> </td>
                                    <td><select class="form-select" id="lokasi" name="lokasi" aria-label="Default select example" required>
                                        <option value="" disabled selected>Choose Lokasi Kampus...</option>
                                        
                                        @foreach($allKampus as $lokasi)
                                        <option value="{{ $lokasi->lokasi }}">
                                            {{ $lokasi->lokasi }}
                                        </option>
                                         
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jenis Dosen</label> </td>
                                    <td><select class="form-select" id="jenis" name="jenis" aria-label="Default select example" required>
                                        <option value="" disabled selected>Choose...</option>
                                        
                                        @foreach($allJenis as $nama)
                                        <option value="{{ $nama->nama }}">
                                            {{ $nama->nama }}
                                        </option>
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jenjang Pendidikan</label> </td>
                                    <td><select class="form-select" id="jenjang" name="jenjang" aria-label="Default select example" required>
                                        <option value="" disabled selected>Choose Pendidikan...</option>
                                        
                                        @foreach($allJenjang as $jenjangakademik)
                                        <option value="{{ $jenjangakademik->jenjangakademik }}">
                                            {{ $jenjangakademik->jenjangakademik }}
                                        </option>
                                        @endforeach
                                    </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Jabatan</label></td>
                                    <td> 
                                        
                                        <select class="form-select" id="jabat" name="jabat" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose Jabatan...</option>
                                            @foreach($allJabat as $jabatanakademik)
                                          <option value="{{ $jabatanakademik->jabatanakademik }}">
                                              {{ $jabatanakademik->jabatanakademik }}
                                          </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Golongan Dosen</label></td>
                                    <td>
                                        <select class="form-select" id="golongan" name="golongan" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose Golongan...</option>
                                            @foreach($allGolongan as $golongan)
                                                <option value="{{ $golongan->golongan }}" data-kepangkatan="{{ $golongan->kepangkatan }}">
                                                    {{ $golongan->golongan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td><label for="kepangkatan" class="form-label">Kepangkatan</label></td>
                                    <td><input type="text" class="form-control" id="kepangkatan" name="kepangkatan" readonly required>
                                    </tr>
                                    <tr>
                                        <td><label class="form-label">Honor SKS Dosen</label></td>
                                        <td>
                                            <input type="text" class="form-control" name="honor" value="{{ $formattedHonorsks }}" readonly required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-label">Honor SKS S2 Dosen</label></td>
                                        <td>
                                            <input type="text" class="form-control" name="honors2" value="{{ $formattedHonorskss2 }}" readonly required>
                                        </td>
                                    </tr>
                                <tr>
                                    <td><label class="form-label">Tunjangan Pendidikan</label> </td>
                                    <td><input type="text" class="form-control"  name="tunjpendidikan" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Tunjangan Akademik</label> </td>
                                    <td><input type="text" class="form-control"  name="tunjakademik" ></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">Status Ke Aktifan</label> </td>
                                    <td>
                                        <select class="form-select" id="aktifan" name="aktifan" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose...</option>
                                            @foreach($allAktifan as $statusdosen)
                                                <option value="{{ $statusdosen->statusdosen }}">
                                                    {{ $statusdosen->statusdosen }}
                                                </option>
                                            @endforeach
                                        </select></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">NIP</label> </td>
                                    <td><input type="text" class="form-control"  name="nip" required></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label">No SK </label> </td>
                                    <td><input type="text" class="form-control"  name="skdosen" required></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">SK Kepangkatan</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="sk_kepangkatan" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah Diserahkan</option>
                                            <option value="T">Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">SK Pengangkatan</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="sk_pengangkatan" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah</option>
                                            <option value="T">Belum </option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Ijazah</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="ijazah" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah Diserahkan</option>
                                            <option value="T">Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">CV</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="cv" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah Diserahkan</option>
                                            <option value="T">Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">KTP</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="ktp" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah Diserahkan</option>
                                            <option value="T">Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Pass Foto</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="passfoto" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah Diserahkan</option>
                                            <option value="T">Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                                <tr>
                                    <td> <label class="form-label">Sertifikat Dosen</label></td>
                                    <td><div class="input-group">
                                        <select class="form-control" name="serdos" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Y">Sudah Diserahkan</option>
                                            <option value="T">Belum Diserahkan</option>
                                        </select>
                                      
                                    </div></td>
                                </tr>
                            </table>
                            <br><br>
                            <button type="submit" class="btn btn-primary">Save Profil</button>    
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
            defaultDate: '{{ date('d-m-Y') }}',
            minDate: '01-01-1900', // Atur batas minimal jika diperlukan
            maxDate: 'today', // Atur batas maksimal ke hari ini
            yearRange: '1900:' + new Date().getFullYear(), // Rentang tahun mulai dari 1900 hingga tahun saat ini
        });
    });
    jQuery(document).ready(function ($) {
    // Initialize flatpickr untuk kolom tanggal
    flatpickr('#tmt', {
        dateFormat: 'd-m-Y',
        defaultDate: '{{ date('d-m-Y') }}',
        minDate: '01-01-1900', // Atur batas minimal jika diperlukan
        maxDate: 'today', // Atur batas maksimal ke hari ini
        yearRange: '1900:' + new Date().getFullYear(), // Rentang tahun mulai dari 1900 hingga tahun saat ini
    });
});
jQuery(document).ready(function ($) {
    // Initialize flatpickr untuk kolom tanggal
    flatpickr('#tanggalgabung', {
        dateFormat: 'd-m-Y',
        defaultDate: '{{ date('d-m-Y') }}',
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