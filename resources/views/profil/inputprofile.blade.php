@extends('admin.dashboard')

@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h4 class="mb-4"> Profile Dosen</h4>
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="row g-5" id="showProfile">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="iddosen" name="iddosen" placeholder="masukkan nama dosen" required>
                                <button type="button" class="btn btn-outline-primary" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                                <input type="hidden" id="NamaDosen" name="NamaDosen">
                                <ul id="resultList" style="display: none;"></ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="label" onclick="toggleContent('datadiri')"> <span class="toggle-symbol" id="datadiriToggle">+</span> Profil Kampus </h5>
                    <div class="mb-3"></div>
                    <div class="content" id="datadiriContent">
                        <form id="dosenprofile">
                            <table width ="100%">
                                <tr>
                                    <td><label for="dosenNIDN" class="form-label">NIDN:</label></td>
                                    <td><input type="text" class="form-control" id="dosenNIDN" name="dosenNIDN" readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenNama" class="form-label">Nama:</label></td>
                                    <td><input type="email" class="form-control" id="dosenNama" name="dosenNama"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenProdi" class="form-label">Prodi:</label></td>
                                    <td><input type="text" class="form-control" id="dosenProdi" name="dosenProdi"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenAsal" class="form-label">Asal Kampus:</label></td>
                                    <td><input type="text" class="form-control" id="dosenAsal" name="dosenAsal"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenSK" class="form-label">No SK Dosen:</label></td>
                                    <td><input type="text" class="form-control" id="dosenSK" name="dosenSK"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenSERDOS" class="form-label">SERDOS:</label></td>
                                    <td><input type="text" class="form-control" id="dosenSERDOS" name="dosenSERDOS"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenjabatan" class="form-label">Jabatan:</label></td>
                                    <td><input type="text" class="form-control" id="dosenjabatan" name="dosenjabatan"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosengolongan" class="form-label">Golongan:</label></td>
                                    <td><input type="text" class="form-control" id="dosengolongan" name="dosengolongan"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenStatus" class="form-label">Status:</label></td>
                                    <td><input type="text" class="form-control" id="dosenStatus" name="dosenStatus"readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="dosenAlamat" class="form-label">Alamat:</label></td>
                                    <td><input type="text" class="form-control" id="dosenAlamat" name="dosenAlamat"readonly></td>
                                </tr>
                            </table>
                            <br><br>
                            <button type="button" id="editProfileBtn" class="btn btn-primary">Edit Profil</button>       
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="label" onclick="toggleContent('pendidikan')"> 
                        <span class="toggle-symbol" id="pendidikanToggle">+</span> Histori Pendidikan </h5>
                    <div class="mb-3"></div>
                    <div class="content" id="pendidikanContent">
                        <form id="results">
                            <button type="submit" class="btn btn-primary" id="tambahBtn">Tambah</button>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('matakuliah')"> 
                    <span class="toggle-symbol" id="matakuliahToggle">+</span> Mata Kuliah </h5>
                <div class="mb-3"></div>
                <div class="content" id="matakuliahContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="matkulBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('fungsi')"> 
                    <span class="toggle-symbol" id="fungsiToggle">+</span> 
                    History Jabatan Fungsionoris </h5>
                <div class="mb-3"></div>
                <div class="content" id="fungsiContent">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('aktifitas')"> 
                    <span class="toggle-symbol" id="aktifitasToggle">+</span>Histori Aktifitas Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="aktifitasContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="aktifBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('sertifikasi')"> 
                    <span class="toggle-symbol" id="sertifikasiToggle">+</span> Sertifikasi Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="sertifikasiContent">
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('penelitian')"> 
                    <span class="toggle-symbol" id="penelitianToggle">+</span> Histori Penelitian Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="penelitianContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="penelitianBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('penunjang')"> 
                    <span class="toggle-symbol" id="penunjangToggle">+</span> History Penunjang Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="penunjangContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="penunjangBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('inpassing')"> 
                    <span class="toggle-symbol" id="inpassingToggle">+</span> Histori Inpassing Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="inpassingContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="inpassingBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('jabatan')"> 
                    <span class="toggle-symbol" id="jabatanToggle">+</span> Histori Jabatan Fungsional Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="jabatanContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="jabatanBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('ijinbelajar')"> 
                    <span class="toggle-symbol" id="ijinbelajarToggle">+</span> Histori Ijin Belajar Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="ijinbelajarContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="ijinbelajarBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('tugasbelajar')"> 
                    <span class="toggle-symbol" id="tugashbelajarContent">+</span> Histori Tugas Belajar Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="tugasbelajarContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="tugasbelajarBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('dosenkelompok')"> 
                    <span class="toggle-symbol" id="dosenkelompokToggle">+</span> Histori Dosen Kelompok (Mata Kuliah) </h5>
                <div class="mb-3"></div>
                <div class="content" id="dosenkelompokContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="dosenkelompokBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('dosenpembimbing')"> 
                    <span class="toggle-symbol" id="dosenpembimbingToggle">+</span> Histori Dosen Pembimbing </h5>
                <div class="mb-3"></div>
                <div class="content" id="dosenpembimbingContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="dosenpembimbingBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('pengabdian')"> 
                    <span class="toggle-symbol" id="pengabdianToggle">+</span> Histori Pengabdian Dosen </h5>
                <div class="mb-3"></div>
                <div class="content" id="pengabdianContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="pengabdianBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="label" onclick="toggleContent('hibah')"> 
                    <span class="toggle-symbol" id="hibahToggle">+</span> Hibah ( Pemerintah / Mandari ) </h5>
                <div class="mb-3"></div>
                <div class="content" id="hibahContent">
                    <form id="results">
                        <button type="submit" class="btn btn-primary" id="hibahBtn">Tambah</button>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    
  <style>
  #dataNilai tbody tr:hover {
  cursor: pointer;
}
#matakuliahContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#matakuliahContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#fungsiContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#fungsiContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#aktifitasContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#aktifitasContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#sertifikasiContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#sertifikasiContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#pendidikanContent {
  overflow-x: auto; /* Atau scroll */
  max-width: 100%; /* Sesuaikan dengan lebar maksimum yang diinginkan */
}
#aktifitasContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#aktifitasContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#penelitianContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#penelitianContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#penunjangContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#penunjangContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#inpassingContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#inpassingContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#jabatanContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#jabatanContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#ijinbelajarContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#ijinbelajarContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#tugasbelajarContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#tugasbelajarContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#dosenkelompokContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#dosenkelompokContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
#dosenpembimbingContent {
  overflow-x: auto !important;
  overflow-y: auto !important;
  -webkit-overflow-scrolling: touch !important;
  max-width: 100%;
}
#dosenpembimbingContent {
  max-height: 400px; /* Atur ketinggian maksimum sesuai kebutuhan */
}
</style>  
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script>
    $('#editProfileBtn').click(function () {
    var idDosen = $('#iddosen').val();
    // Arahkan pengguna ke halaman ediprofil.blade.php dengan menyertakan ID Dosen
    window.location.href = "{{ route('showEdit') }}?iddosen=" + idDosen;
});

   $('#iddosen').on('input', function () {
       var searchQuery = $(this).val();
       if (searchQuery.length >= 4) {
           // Lakukan permintaan AJAX ke server untuk mencari Dosen
           $.ajax({
               url: '{{ route("findDosen1") }}',
               method: 'GET',
               data: { term: searchQuery },
               success: function (data) {
                   var resultList = $('#resultList');
                   resultList.empty();
                   console.log('Server Response:', data);
                   resultList.show();
                   data.forEach(function (result) {
                       resultList.append('<li data-id="' + result.iddosen + '">' + result.iddosen + ' - ' +  result.nama + '</li>');
                   });
               },
               error: function (error) {
                   console.error('Error fetching data:', error);
               }
           });
       } else {
           $('#resultList').hide();
       }
   });
   $(document).on('click', '#resultList li', function () {
       var fullName = $(this).text();
       var splitResult = fullName.split(' - ');
       var idDosen = splitResult.length > 1 ? splitResult[0] : '';
       var namaDosen = splitResult.length > 1 ? splitResult[1] : '';
       console.log('id dosen:', idDosen);
       console.log('Nama:', namaDosen);
       $('#iddosen').val(idDosen);
       $('#NamaDosen').val(namaDosen);
       if (!idDosen) {
           $('#NamaDosen').val('');
       }
       $('#resultList').hide();
   });
function toggleContent(sectionId) {
        console.log('Toggle content for section:', sectionId);
        var content = document.getElementById(sectionId + 'Content');
        var toggleSymbol = document.getElementById(sectionId + 'Toggle');

        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            toggleSymbol.textContent = '+';
        } else {
            content.style.display = 'none';
            toggleSymbol.textContent = '-';
        }
    }
    $(document).ready(function () {
        $('#myTabs a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    });

    $(document).ready(function() {
    $('#searchButton').click(function() {
        var idDosen = $('#iddosen').val();
        $.ajax({
            url: '{{ route("fetchProfile") }}',
            method: 'GET',
            data: { iddosen: idDosen },
            success: function(data) {
                console.log('Data dari pendidikandosen:', data.pendidikandosen); // Tambahkan pernyataan console.log() di sini
                // Memperbarui form dengan data Dosen jika ada
                if (data.dosen) {
                    var dosen = data.dosen;
                    $('#dosenNama').val(dosen.Nama || '');
                    $('#dosenProdi').val(dosen.Prodi || '');
                    $('#dosenAsal').val(dosen.Asal || '');
                    $('#dosenNIDN').val(dosen.NIDN || '');
                    $('#dosenSK').val(dosen.SK || '');
                    if (dosen.SERDOS === 'Y') {
                        $('#dosenSERDOS').val('Sudah Sertifikasi');
                    } else if (dosen.SERDOS === 'T') {
                        $('#dosenSERDOS').val('Belum Sertifikasi');
                    } else {
                        $('#dosenSERDOS').val('');
                    }
                    $('#dosenjabatan').val(dosen.jabatan || '');
                    $('#dosengolongan').val(dosen.golongan || '');
                    $('#dosenAlamat').val(dosen.Alamat || '');
                    $('#dosenAgama').val(dosen.Agama || '');
                    $('#dosenStatus').val(dosen.Status || '');
                } else {
                    // Jika data Dosen tidak ditemukan, reset nilai input fields menjadi kosong
                    $('#emailDosen').val('');
                    $('#dosenTelepon').val('');
                    $('#dosenHP').val('');
                    $('#dosenNIK').val('');
                    $('#dosenSK').val('');
                    $('#dosenSERDOS').val('');
                    $('#dosenBPJS').val('');
                    $('#dosenNoRek').val('');
                    $('#dosenAlamat').val('');
                    $('#dosenAgama').val('');
                    $('#dosenStatus').val('');
                    console.log('Response from server:', data);
                }
                // Menampilkan data pendidikan dosen dalam bentuk tabel
                $('#pendidikanContent').empty(); // Menghapus konten sebelumnya
                if (data.pendidikandosen.length > 0) {
                    var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>Pendidikan</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>';
                    data.pendidikandosen.forEach(function (item) {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + item.ITEMNO + '</td>';
                        tableHtml += '<td>' + item.pendidikan + '</td>';
                        tableHtml += '<td>' + item.keterangan + '</td>';
                        tableHtml += '<td>';
                        tableHtml += '<button class="btn btn-danger btn-sm delete-btn" data-id="' + item.idprimary + '">Hapus</button>';
                        tableHtml += '<button class="btn btn-primary btn-sm edit-btn" data-id="' + item.idprimary + '">Edit</button>';
                        tableHtml += '</td>';
                        tableHtml += '</tr>';
                        console.log('Response from server:', item.idprimary);
                        
                    });
                    tableHtml += '</tbody></table>';
                    tableHtml += '<button id="tambahBtn" class="btn btn-success">Tambah</button>';
                    
                    $('#pendidikanContent').html(tableHtml);
                    $('#tambahBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpendidikan?idDosen=' + idDosen;
                        });
                        // Tambahkan event listener untuk tombol delete
                    $('.delete-btn').click(function () {
                        var idPrimary = $(this).data('id');
                        console.log('id primary : ', idPrimary);
                        // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                        $.ajax({
                            url: 'input/hapusDataPendidikan',
                            method: 'POST',
                            data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function (response) {
                                // Handle response jika berhasil dihapus
                                console.log('Data berhasil dihapus');
                                // Tampilkan notifikasi SweetAlert
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: response.success,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // Muat ulang hanya bagian konten pendidikan dosen
                                refreshPendidikanContent();
                            },
                            error: function (error) {
                                console.error('Error menghapus data:', error);
                            }
                        });
                    });
                    
                    function refreshPendidikanContent() {
                        var idDosen = $('#iddosen').val();
                        // Kirim permintaan AJAX untuk memperbarui konten pendidikan dosen
                        $.ajax({
                            url: 'input/fetchProfile',
                            method: 'GET',
                            data: { iddosen: idDosen },
                            success: function(data) {
                                console.log('Data dari pendidikandosen:', data.pendidikandosen);
                                // Memperbarui tabel pendidikan dosen dengan data baru
                                if (data.pendidikandosen.length > 0) {
                                    var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>Pendidikan</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>';
                                    data.pendidikandosen.forEach(function (item) {
                                        tableHtml += '<tr>';
                                        tableHtml += '<td>' + item.ITEMNO + '</td>';
                                        tableHtml += '<td>' + item.pendidikan + '</td>';
                                        tableHtml += '<td>' + item.keterangan + '</td>';
                                        tableHtml += '<td>';
                                        tableHtml += '<button class="btn btn-danger btn-sm delete-btn" data-id="' + item.idprimary + '">Hapus</button>';
                                        tableHtml += '<button class="btn btn-primary btn-sm edit-btn" data-id="' + item.idprimary + '">Edit</button>';
                                        tableHtml += '</td>';
                                        tableHtml += '</tr>';
                                    });
                                    tableHtml += '</tbody></table>';
                                    tableHtml += '<button id="tambahBtn" class="btn btn-success">Tambah</button>';
                                    
                                    $('#pendidikanContent').html(tableHtml);
                                } else {
                                    $('#pendidikanContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
                                }
                            },
                            error: function(error) {
                                console.error('Error fetching data:', error);
                            }
                        });
                    }


                    // Tambahkan event listener untuk tombol edit
                    $('.edit-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahpendidikan?idPrimary=' + idPrimary + '&idDosen=' + idDosen + '&itemNo=' + itemNo;
                });

                } else {
                    $('#pendidikanContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
                }
                
                
                $('#matakuliahContent').empty(); // Menghapus konten sebelumnya
                if (data.matakuliah) {
                    var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDMK</th><th>Matakuliah</th><th>Aksi</th></tr></thead><tbody>';
                        tableHtml += '<button id="matkulBtn" class="btn btn-success">Tambah</button>';   
                    data.matakuliah.forEach(function(item) {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + item.ITEMNO + '</td>';
                        tableHtml += '<td>' + item.idmk + '</td>';
                        tableHtml += '<td>' + item.matakuliah + '</td>';
                        tableHtml += '<td>';
                        tableHtml += '<button class="btn btn-danger btn-sm hapus-btn" data-id="' + item.idprimary + '">Hapus</button>';
                        tableHtml += '<button class="btn btn-primary btn-sm ubah-btn" data-id="' + item.idprimary + '">Edit</button>';
                        tableHtml += '</td>';
                        tableHtml += '</tr>';
                       // console.log('Response from server:', item.ITEMNO);
                    });
                    tableHtml += '</tbody></table>';
                   
                    
                    $('#matakuliahContent').html(tableHtml);
                    $('#matkulBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = '{{ route('showMatkul') }}?idDosen=' + idDosen;
                        });
                        $('.hapus-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusMataKuliah',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            Swal.fire({
                             title: 'Sukses!',
                             text: response.success,
                             icon: 'success',
                             showConfirmButton: false,
                             timer: 1500
                         });
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            refreshMatakuliahContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });

                function refreshMatakuliahContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                        if (data.matakuliah) {
                            var tableHtml = '<table class="table"><thead><tr><th>IDMK</th><th>Matakuliah</th><th>Aksi</th></tr></thead><tbody>';
                                tableHtml += '<button id="matkulBtn" class="btn btn-success">Tambah</button>'; 
                            data.matakuliah.forEach(function(item) {
                                tableHtml += '<tr>';
                                tableHtml += '<td>' + item.ITEMNO + '</td>';
                                tableHtml += '<td>' + item.idmk + '</td>';
                                tableHtml += '<td>' + item.matakuliah + '</td>';
                                tableHtml += '<td>';
                                tableHtml += '<button class="btn btn-danger btn-sm hapus-btn" data-id="' + item.idprimary + '">Hapus</button>';
                                tableHtml += '<button class="btn btn-primary btn-sm ubah-btn" data-id="' + item.idprimary + '">Edit</button>';
                                tableHtml += '</td>';
                                tableHtml += '</tr>';
                            });
                            tableHtml += '</tbody></table>';
                            tableHtml += '<button id="matkulBtn" class="btn btn-success">Tambah</button>';
                            $('#matakuliahContent').html(tableHtml);
                            $('#matkulBtn').click(function () {
                                var idDosen = $('#iddosen').val();
                                // Arahkan pengguna ke halaman tambahmatakuliah.blade.php
                                window.location.href = '{{ route('showMatkul') }}?idDosen=' + idDosen;
                            });
                        } else {
                            $('#matakuliahContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = '/ubahmatakuliah?idPrimary=' + idPrimary + '&idDosen=' + idDosen + '&itemNo=' + itemNo;
                });
                } else {
                    $('#matakuliahContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                    // Menambahkan properti overflow dengan nilai auto
                    matakuliahContent.show(); // Menampilkan matakuliahContent bahkan jika tidak ada data matakuliah
                  
                }
                $('#fungsiContent').empty(); // Menghapus konten sebelumnya
            if (data.fungsi) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jabatan Dosen</th><th>NO SK</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="fungsiBtn" class="btn btn-success">Tambah</button>';
                data.fungsi.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.jabatandosen + '</td>';
                    tableHtml += '<td>' + item.Nosk + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus1-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah1-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#fungsiContent').html(tableHtml);
                $('#fungsiBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahfungsi?idDosen=' + idDosen;
                        });
                    $('.hapus1-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusFungsi',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshFungsiContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshFungsiContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                        if (data.fungsi) {
                         var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jabatan Dosen</th><th>NO SK</th><th>Aksi</th></tr></thead><tbody>';
                         tableHtml += '<button id="fungsiBtn" class="btn btn-success">Tambah</button>';
                         data.fungsi.forEach(function(item) {
                             tableHtml += '<tr>';
                             tableHtml += '<td>' + item.ITEMNO + '</td>';
                             tableHtml += '<td>' + item.iddosen + '</td>';
                             tableHtml += '<td>' + item.jabatandosen + '</td>';
                             tableHtml += '<td>' + item.Nosk + '</td>';
                             tableHtml += '<td>';
                             tableHtml += '<button class="btn btn-danger btn-sm hapus1-btn" data-id="' + item.idprimary + '">Hapus</button>';
                             tableHtml += '<button class="btn btn-primary btn-sm ubah1-btn" data-id="' + item.idprimary + '">Edit</button>';
                             tableHtml += '</td>';
                             tableHtml += '</tr>';
                             console.log('Response from server:', item.ITEMNO);
                         });
                         tableHtml += '</tbody></table>';
                            $('#fungsiContent').html(tableHtml);
                            $('#fungsiBtn').click(function () {
                                var idDosen = $('#iddosen').val();
                                // Arahkan pengguna ke halaman tambahmatakuliah.blade.php
                                window.location.href = 'input/tambahfungsi?idDosen=' + idDosen;
                            });
                        } else {
                            $('#matakuliahContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah1-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahfungsi?idPrimary=' + idPrimary + '&idDosen=' + idDosen + '&itemNo=' + itemNo;
                });
            } else {
                $('#fungsiContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#aktifitasContent').empty(); // Menghapus konten sebelumnya
            if (data.aktifitas) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Status Dosen</th><th>NO SK</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="aktifitasBtn" class="btn btn-success">Tambah</button>';
                data.aktifitas.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.statusdosen + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus2-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah2-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#aktifitasContent').html(tableHtml);
                $('#aktifitasBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahaktifitas?idDosen=' + idDosen;
                        });
                    $('.hapus2-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusAktifitas',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshFungsiContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshFungsiContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                        if (data.aktifitas) {
                         var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jabatan Dosen</th><th>NO SK</th><th>Aksi</th></tr></thead><tbody>';
                         tableHtml += '<button id="aktifitasBtn" class="btn btn-success">Tambah</button>';
                         data.aktifitas.forEach(function(item) {
                             tableHtml += '<tr>';
                             tableHtml += '<td>' + item.ITEMNO + '</td>';
                             tableHtml += '<td>' + item.iddosen + '</td>';
                             tableHtml += '<td>' + item.statusdosen + '</td>';
                             tableHtml += '<td>' + item.nosk + '</td>';
                             tableHtml += '<td>';
                             tableHtml += '<button class="btn btn-danger btn-sm hapus2-btn" data-id="' + item.idprimary + '">Hapus</button>';
                             tableHtml += '<button class="btn btn-primary btn-sm ubah2-btn" data-id="' + item.idprimary + '">Edit</button>';
                             tableHtml += '</td>';
                             tableHtml += '</tr>';
                             console.log('Response from server:', item.ITEMNO);
                         });
                         tableHtml += '</tbody></table>';
                            $('#aktifitasContent').html(tableHtml);
                            $('#aktifitasBtn').click(function () {
                                var idDosen = $('#iddosen').val();
                                // Arahkan pengguna ke halaman tambahmatakuliah.blade.php
                                window.location.href = 'input/tambahaktifitas?idDosen=' + idDosen;
                            });
                        } else {
                            $('#aktifitasContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah2-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahaktifitas?idPrimary=' + idPrimary ;
                });
            } else {
                $('#aktifitasContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#sertifikasiContent').empty(); // Menghapus konten sebelumnya
            if (data.sertifikasi) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jenis Sertifikasi</th><th>Bidang Sertifikasi</th><th>Nomor SK</th><th>Tahun Sertifikasi</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="sertifikasiBtn" class="btn btn-success">Tambah</button>';
                data.sertifikasi.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.JenisSertifikasi + '</td>';
                    tableHtml += '<td>' + item.BidangSertifikasi + '</td>';
                    tableHtml += '<td>' + item.NoSK + '</td>';
                    tableHtml += '<td>' + item.TahunSertifikasi + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus3-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah3-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#sertifikasiContent').html(tableHtml);
                $('#sertifikasiBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahsertifikasi?idDosen=' + idDosen;
                        });
                    $('.hapus3-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapussertifikasi',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshsertifikasiContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshsertifikasiContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                        if (data.sertifikasi) {
                         var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jabatan Dosen</th><th>NO SK</th><th>Aksi</th></tr></thead><tbody>';
                            tableHtml += '<button id="sertifikasiBtn" class="btn btn-success">Tambah</button>';
                data.sertifikasi.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.JenisSertifikasi + '</td>';
                    tableHtml += '<td>' + item.BidangSertifikasi + '</td>';
                    tableHtml += '<td>' + item.NoSK + '</td>';
                    tableHtml += '<td>' + item.TahunSertifikasi + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus3-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah3-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
                $('#sertifikasiContent').html(tableHtml);
                $('#sertifikasiBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahsertifikasi?idDosen=' + idDosen;
                        });
                        } else {
                            $('#sertifikasiContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah3-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahsertifikasi?idPrimary=' + idPrimary ;
                });
            } else {
                $('#sertifikasiContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#penelitianContent').empty(); // Menghapus konten sebelumnya
            if (data.penelitian) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Judul</th><th>Bidang</th><th>Lokasi</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="penelitianBtn" class="btn btn-success">Tambah</button>';
                data.penelitian.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.judul + '</td>';
                    tableHtml += '<td>' + item.bidang + '</td>';
                    tableHtml += '<td>' + item.lokasi + '</td>';
                    tableHtml += '<td>' + item.tahun + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus4-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah4-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#penelitianContent').html(tableHtml);
                $('#penelitianBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpenelitian?idDosen=' + idDosen;
                        });
                    $('.hapus4-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapuspenelitian',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshpenelitianContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshpenelitianContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.penelitian) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Judul</th><th>Bidang</th><th>Lokasi</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="penelitianBtn" class="btn btn-success">Tambah</button>';
                data.penelitian.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.judul + '</td>';
                    tableHtml += '<td>' + item.bidang + '</td>';
                    tableHtml += '<td>' + item.lokasi + '</td>';
                    tableHtml += '<td>' + item.tahun + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus4-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah4-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#penelitianContent').html(tableHtml);
                $('#penelitianBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpenelitian?idDosen=' + idDosen;
                        });
                        } else {
                            $('#penelitianContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah4-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahpenelitian?idPrimary=' + idPrimary ;
                });
            } else {
                $('#penelitianContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#penunjangContent').empty(); // Menghapus konten sebelumnya
            if (data.penunjang) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Kegiatan</th><th>Penyelenggara</th><th>Lokasi</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="penunjangBtn" class="btn btn-success">Tambah</button>';
                data.penunjang.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.kegiatan + '</td>';
                    tableHtml += '<td>' + item.sertifikat + '</td>';
                    tableHtml += '<td>' + item.tempat + '</td>';
                    tableHtml += '<td>' + item.tahun + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus5-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah5-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#penunjangContent').html(tableHtml);
                $('#penunjangBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpenunjang?idDosen=' + idDosen;
                        });
                    $('.hapus5-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapuspenunjang',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshpenunjangContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshpenunjangContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.penunjang) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Kegiatan</th><th>Penyelenggara</th><th>Lokasi</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="penunjangBtn" class="btn btn-success">Tambah</button>';
                data.penunjang.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.kegiatan + '</td>';
                    tableHtml += '<td>' + item.sertifikat + '</td>';
                    tableHtml += '<td>' + item.tempat + '</td>';
                    tableHtml += '<td>' + item.tahun + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus5-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah5-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#penunjangContent').html(tableHtml);
                $('#penunjangBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpenunjang?idDosen=' + idDosen;
                        });
                        } else {
                            $('#penunjangContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah5-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahpenunjang?idPrimary=' + idPrimary ;
                });
            } else {
                $('#penunjangContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#inpassingContent').empty(); // Menghapus konten sebelumnya
            if (data.inpassing) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Pangkat</th><th>NO SK</th><th>Tanggal</th><th>TMT</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="inpassingBtn" class="btn btn-success">Tambah</button>';
                data.inpassing.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.pangkat + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tanggal + '</td>';
                    tableHtml += '<td>' + item.tmt + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus6-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah6-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#inpassingContent').html(tableHtml);
                $('#inpassingBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahinpassing?idDosen=' + idDosen;
                        });
                    $('.hapus5-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusinpassing',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshinpassingContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshinpassingContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.inpassing) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Pangkat</th><th>NO SK</th><th>Tanggal</th><th>TMT</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="inpassingBtn" class="btn btn-success">Tambah</button>';
                data.inpassing.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.pangkat + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tanggal + '</td>';
                    tableHtml += '<td>' + item.tmt + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus6-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah6-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#inpassingContent').html(tableHtml);
                $('#inpassingBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahinpassing?idDosen=' + idDosen;
                        });
                        } else {
                            $('#inpassingContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah6-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahinpassing?idPrimary=' + idPrimary ;
                });
            } else {
                $('#inpassingContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#jabatanContent').empty(); // Menghapus konten sebelumnya
            if (data.jabatan) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jabatan</th><th>NO SK</th><th>Tanggal</th><th>TMT</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="jabatanBtn" class="btn btn-success">Tambah</button>';
                data.jabatan.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.jabatan + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tanggal + '</td>';
                    tableHtml += '<td>' + item.tmt + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus7-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah7-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#jabatanContent').html(tableHtml);
                $('#jabatanBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahjabatan?idDosen=' + idDosen;
                        });
                    $('.hapus5-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusjabatan',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshjabatanContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshjabatanContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.jabatan) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Jabatan</th><th>NO SK</th><th>Tanggal</th><th>TMT</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="jabatanBtn" class="btn btn-success">Tambah</button>';
                data.jabatan.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.jabatan + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tanggal + '</td>';
                    tableHtml += '<td>' + item.tmt + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus7-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah7-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#jabatanContent').html(tableHtml);
                $('#jabatanBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahjabatan?idDosen=' + idDosen;
                        });
                        } else {
                            $('#jabatanContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah7-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahjabatan?idPrimary=' + idPrimary ;
                });
            } else {
                $('#jabatan Content').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            $('#ijinbelajarContent').empty(); // Menghapus konten sebelumnya
            if (data.ijinbelajar) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Ijin</th><th>NO SK</th><th>Tahun Mulai</th><th>Kota</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="ijinbelajarBtn" class="btn btn-success">Tambah</button>';
                data.ijinbelajar.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.berizin + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tahunmulai + '</td>';
                    tableHtml += '<td>' + item.kota + '</td>';
                    tableHtml += '<td>' + item.keterangan + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus8-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah8-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#ijinbelajarContent').html(tableHtml);
                $('#ijinbelajarBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahijinbelajar?idDosen=' + idDosen;
                        });
                    $('.hapus8-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusijinbelajar',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshijinbelajarContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshijinbelajarContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.ijinbelajar) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Ijin</th><th>NO SK</th><th>Tahun Mulai</th><th>Kota</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="ijinbelajarBtn" class="btn btn-success">Tambah</button>';
                data.ijinbelajar.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.berizin + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tahunmulai + '</td>';
                    tableHtml += '<td>' + item.kota + '</td>';
                    tableHtml += '<td>' + item.keterangan + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus8-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah8-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#ijinbelajarContent').html(tableHtml);
                $('#ijinbelajarBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahijinbelajar?idDosen=' + idDosen;
                        });
                        } else {
                            $('#ijinbelajarContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah8-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahijinbelajar?idPrimary=' + idPrimary ;
                });
            } else {
                $('#ijinbelajarContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
             $('#tugasbelajarContent').empty(); // Menghapus konten sebelumnya
            if (data.tugasbelajar) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Tugas</th><th>NO SK</th><th>Tahun Mulai</th><th>Kota</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="tugasbelajarBtn" class="btn btn-success">Tambah</button>';
                data.tugasbelajar.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.tugas + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tahunmulai + '</td>';
                    tableHtml += '<td>' + item.kota + '</td>';
                    tableHtml += '<td>' + item.keterangan + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus8-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah8-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#tugasbelajarContent').html(tableHtml);
                $('#tugasbelajarBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahtugasbelajar?idDosen=' + idDosen;
                        });
                    $('.hapus8-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapustugasbelajar',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshtugasbelajarContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshtugasbelajarContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.tugasbelajar) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Tugas</th><th>NO SK</th><th>Tahun Mulai</th><th>Kota</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="tugasbelajarBtn" class="btn btn-success">Tambah</button>';
                data.tugasbelajar.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.tugas + '</td>';
                    tableHtml += '<td>' + item.nosk + '</td>';
                    tableHtml += '<td>' + item.tahunmulai + '</td>';
                    tableHtml += '<td>' + item.kota + '</td>';
                    tableHtml += '<td>' + item.keterangan + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus8-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah8-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#tugasbelajarContent').html(tableHtml);
                $('#tugasbelajarBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahtugasbelajar?idDosen=' + idDosen;
                        });
                        } else {
                            $('#tugasbelajarContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah8-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahtugasbelajar?idPrimary=' + idPrimary ;
                });
            } else {
                $('#tugasbelajarContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
             $('#dosenkelompokContent').empty(); // Menghapus konten sebelumnya
            if (data.dosenkelompok) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>IPA</th><th>IPS</th><th>Bahasa</th><th>Matematika</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="dosenkelompokBtn" class="btn btn-success">Tambah</button>';
                data.dosenkelompok.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.ilmuipa + '</td>';
                    tableHtml += '<td>' + item.ilmuips + '</td>';
                    tableHtml += '<td>' + item.ilmubahasa + '</td>';
                    tableHtml += '<td>' + item.ilmumatematika + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus10-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah10-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#dosenkelompokContent').html(tableHtml);
                $('#dosenkelompokBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahdosenkelompok?idDosen=' + idDosen;
                        });
                    $('.hapus10-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusdosenkelompok',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshdosenkelompokContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshdosenkelompokContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
                if (data.dosenkelompok) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>IPA</th><th>IPS</th><th>Bahasa</th><th>Matematika</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="dosenkelompokBtn" class="btn btn-success">Tambah</button>';
                data.dosenkelompok.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.ilmuipa + '</td>';
                    tableHtml += '<td>' + item.ilmuips + '</td>';
                    tableHtml += '<td>' + item.ilmubahasa + '</td>';
                    tableHtml += '<td>' + item.ilmumatematika + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus10-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah10-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#dosenkelompokContent').html(tableHtml);
                $('#dosenkelompokBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahdosenkelompok?idDosen=' + idDosen;
                        });
                        } else {
                            $('#dosenkelompokContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah10-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahdosenkelompok?idPrimary=' + idPrimary ;
                });
            } else {
                $('#dosenkelompokContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
             $('#dosenpembimbingContent').empty(); // Menghapus konten sebelumnya
            if (data.dosenpembimbing) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>dosenwali</th><th>Pembimbing 1</th><th>Pembimbing 2</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="dosenpembimbingBtn" class="btn btn-success">Tambah</button>';
                data.dosenpembimbing.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.dosenwali + '</td>';
                    tableHtml += '<td>' + item.pembimbing1 + '</td>';
                    tableHtml += '<td>' + item.pembimbing2 + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus11-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah10-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#dosenpembimbingContent').html(tableHtml);
                $('#dosenpembimbingBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahdosenpembimbing?idDosen=' + idDosen;
                        });
                    $('.hapus11-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapusdosenpembimbing',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshdosenpembimbingContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshdosenpembimbingContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
               if (data.dosenpembimbing) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>dosenwali</th><th>embimbing 1</th><th>Pembimbing 2</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="dosenpembimbingBtn" class="btn btn-success">Tambah</button>';
                data.dosenpembimbing.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.dosenwali + '</td>';
                    tableHtml += '<td>' + item.pembimbing1 + '</td>';
                    tableHtml += '<td>' + item.pembimbing2 + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus11-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah10-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#dosenpembimbingContent').html(tableHtml);
                $('#dosenpembimbingBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahdosenpembimbing?idDosen=' + idDosen;
                        });
                        } else {
                            $('#dosenpembimbingContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah10-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahdosenpembimbing?idPrimary=' + idPrimary ;
                });
            } else {
                $('#ddosenpembimbingContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
             $('#pengabdianContent').empty(); // Menghapus konten sebelumnya
            if (data.pengabdian) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Kegiatan</th><th>Jenis</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="pengabdianBtn" class="btn btn-success">Tambah</button>';
                data.pengabdian.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.namakegiatan + '</td>';
                    tableHtml += '<td>' + item.jeniskegiatan + '</td>';
                    tableHtml += '<td>' + item.tahun + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus12-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah12-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#pengabdianContent').html(tableHtml);
                $('#pengabdianBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpengabdian?idDosen=' + idDosen;
                        });
                    $('.hapus12-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapuspengabdian',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshpengabdianContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshpengabdianContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
              if (data.pengabdian) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Kegiatan</th><th>Jenis</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="pengabdianBtn" class="btn btn-success">Tambah</button>';
                data.pengabdian.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.namakegiatan + '</td>';
                    tableHtml += '<td>' + item.jeniskegiatan + '</td>';
                    tableHtml += '<td>' + item.tahun + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus12-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah12-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
               
            
                $('#pengabdianContent').html(tableHtml);
                $('#pengabdianBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahpengabdian?idDosen=' + idDosen;
                        });
                        } else {
                            $('#pengabdianContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah12-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahpengabdian?idPrimary=' + idPrimary ;
                });
            } else {
                $('#dpengabdianContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
             $('#hibahContent').empty(); // Menghapus konten sebelumnya
            if (data.hibah) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Sumber</th><th>Jumlah</th><th>TA</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="hibahBtn" class="btn btn-success">Tambah</button>';
                data.hibah.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.Sumber + '</td>';
                    tableHtml += '<td>' + item.Jumlah + '</td>';
                    tableHtml += '<td>' + item.TA + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus13-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah13-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
            
                $('#hibahContent').html(tableHtml);
                $('#hibahBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahhibah?idDosen=' + idDosen;
                        });
                    $('.hapus13-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    // Kirim permintaan AJAX untuk menghapus data dengan idPrimary tertentu
                    $.ajax({
                        url: 'input/hapushibah',
                        method: 'POST',
                        data: { idPrimary: idPrimary, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log('id primary : ', idPrimary);
                            // Handle response jika berhasil dihapus
                            console.log('Data berhasil dihapus');
                            // Muat ulang hanya bagian konten matakuliah
                            Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                            refreshhibahContent();
                        },
                        error: function (error) {
                            console.error('Error menghapus data:', error);
                        }
                    });
                    });
                    function refreshhibahContent() {
                var idDosen = $('#iddosen').val();
                // Kirim permintaan AJAX untuk memperbarui konten matakuliah
                $.ajax({
                    url: 'input/fetchProfile',
                    method: 'GET',
                    data: { iddosen: idDosen },
                    success: function(data) {
                        // Memperbarui tabel matakuliah dengan data baru
             if (data.hibah) {
                var tableHtml = '<table class="table"><thead><tr><th>NO</th><th>IDDOSEN</th><th>Sumber</th><th>Jumlah</th><th>TA</th><th>Aksi</th></tr></thead><tbody>';
                tableHtml += '<button id="hibahBtn" class="btn btn-success">Tambah</button>';
                data.hibah.forEach(function(item) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + item.ITEMNO + '</td>';
                    tableHtml += '<td>' + item.iddosen + '</td>';
                    tableHtml += '<td>' + item.Sumber + '</td>';
                    tableHtml += '<td>' + item.Jumlah + '</td>';
                    tableHtml += '<td>' + item.TA + '</td>';
                    tableHtml += '<td>';
                    tableHtml += '<button class="btn btn-danger btn-sm hapus13-btn" data-id="' + item.idprimary + '">Hapus</button>';
                    tableHtml += '<button class="btn btn-primary btn-sm ubah13-btn" data-id="' + item.idprimary + '">Edit</button>';
                    tableHtml += '</td>';
                    tableHtml += '</tr>';
                    console.log('Response from server:', item.iddosen);
                });
                tableHtml += '</tbody></table>';
               
            
                $('#hibahContent').html(tableHtml);
                $('#hibahBtn').click(function () {
                    var idDosen = $('#iddosen').val();
                    // Arahkan pengguna ke halaman tambahpendidikan.blade.php
                    window.location.href = 'input/tambahhibah?idDosen=' + idDosen;
                        });
                        } else {
                            $('#hibahContent').html('<p>Data matakuliah tidak ditemukan.</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
                }

                   $('.ubah13-btn').click(function () {
                    var idPrimary = $(this).data('id');
                    var idDosen = $('#iddosen').val();
                    var itemNo = $(this).closest('tr').find('td:eq(0)').text().trim();
                    console.log('id primary : ', idPrimary);
                    console.log('id dosen : ', idDosen);
                    console.log('item no : ', itemNo);
                    // Arahkan pengguna ke halaman ubahpendidikan.blade.php dengan menyertakan ID yang sesuai
                    window.location.href = 'input/ubahhibah?idPrimary=' + idPrimary ;
                });
            } else {
                $('#hibahContent').html('<p>Data pendidikan dosen tidak ditemukan.</p>');
            }
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });
});

</script>


@endsection