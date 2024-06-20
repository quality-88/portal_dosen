@extends('admin.dashboard')

@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-content">
        
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Profil Dosen</h4>
                        <form class="row g-5" id="profilDosen">
                            @csrf
                            <div class="mb-3">
                                <label for="iddosen" class="form-label">ID Dosen</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="iddosen" name="iddosen" required>
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
                        <!-- Hasil pencarian -->
                       
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                <h5>Data Dosen Yang Lama</h5>
                <hr class="my-3">
                <table class="table">
                    <tbody id="dosenData">
                        <tr>
                            <td>Email Dosen</td>
                            <td id="emailDosen"></td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td id="dosenTelepon"></td>
                        </tr>
                        <tr>
                            <td>HP</td>
                            <td id="dosenHP"></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td id="dosenNIK"></td>
                        </tr>
                        <tr>
                            <td>NPWP</td>
                            <td id="dosenNPWP"></td>
                        </tr>
                        <tr>
                            <td>Ketenagakerjaan</td>
                            <td id="dosenKetenagakerjaan"></td>
                        </tr>
                        <tr>
                            <td>BPJS</td>
                            <td id="dosenBPJS"></td>
                        </tr>
                        <tr>
                            <td>No Rek</td>
                            <td id="dosenNoRek"></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td id="dosenAlamat"></td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td id="dosenAgama"></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td id="dosenStatus"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
                </div>
            </div>
           
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                <h5>Data Yang Ingin Di Ubah</h5>
                <hr class="my-3">
                <table class="table">
                    <tbody id="dosensementaraData">
                        <tr>
                            <td>Email Dosen</td>
                            <td id="emailDosen"></td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td id="dosenTelepon"></td>
                        </tr>
                        <tr>
                            <td>HP</td>
                            <td id="dosenHP"></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td id="dosenNIK"></td>
                        </tr>
                        <tr>
                            <td>NPWP</td>
                            <td id="dosenNPWP"></td>
                        </tr>
                        <tr>
                            <td>Ketenagakerjaan</td>
                            <td id="dosenKetenagakerjaan"></td>
                        </tr>
                        <tr>
                            <td>BPJS</td>
                            <td id="dosenBPJS"></td>
                        </tr>
                        <tr>
                            <td>No Rek</td>
                            <td id="dosenNoRek"></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td id="dosenAlamat"></td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td id="dosenAgama"></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td id="dosenStatus"></td>
                        </tr>
                    </tbody>
                </table>

            </div>
                </div>
                
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-6 text-center">
                <button id="rejectButton" class="btn btn-danger">Reject</button>
                <button id="approveButton" class="btn btn-success">Approve</button>
            </div>
        </div>
    </div>
    <!-- Combine jQuery, Select2, Bootstrap, Popper.js, Flatpickr, and SweetAlert2 into one script tag -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Flatpickr styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Include Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

   <style>
    .card {
        height: 100%; /* Set tinggi kartu menjadi 100% dari parent container */
    }

    .card-body {
        height: 100%; /* Set tinggi isi kartu menjadi 100% dari parent container */
        display: flex; /* Gunakan display flex untuk mengatur tata letak */
        flex-direction: column; /* Set tata letak ke vertikal (menjadi satu kolom) */
    }

    .table {
        flex: 1; /* Gunakan sisa ruang yang tersedia */
        overflow-y: auto; /* Tambahkan overflow-y untuk mengaktifkan scroll jika konten terlalu panjang */
    }

    .btn-group {
        margin-top: auto; /* Letakkan tombol di bagian bawah kartu */
    }
</style>

    
    <script>
        $('#iddosen').on('input', function () {
            var searchQuery = $(this).val();
    
            if (searchQuery.length >= 4) {
                // Lakukan permintaan AJAX ke server untuk mencari Dosen
                $.ajax({
                    url: '/findDosen',
                    method: 'GET',
                    data: { term: searchQuery },
                    success: function (data) {
                        var resultList = $('#resultList');
                        resultList.empty();
    
                        console.log('Server Response:', data);
    
                        resultList.show();
    
                        data.forEach(function (result) {
                            resultList.append('<li data-id="' + result.iddosen + '">' + result.iddosen + ' - ' + result.nama + '</li>');
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
        $(document).ready(function() {
    $('#searchButton').click(function() {
        var idDosen = $('#iddosen').val();
        $.ajax({
            url: '/profil/fetchData',
            method: 'GET',
            data: { iddosen: idDosen },
            success: function(data) {
                // Tampilkan data Dosen
                if (data.dosen) {
                    var dosenDataHtml = '';
                    $.each(data.dosen, function(key, value) {
                        // Ubah jenis kelamin dari M menjadi "Laki-laki" dan F menjadi "Perempuan"
                        if (key === 'Status') {
                    value = (value === 'M') ? 'Menikah' : (value === 'S') ? 'Lajang' : '-';
                        }
                        dosenDataHtml += '<tr><td>' + key + '</td><td>' + (value ? value : '-') + '</td></tr>';
                    });
                    $('#dosenData').html(dosenDataHtml);
                } else {
                    $('#dosenData').html('<tr><td colspan="2">Data tidak ditemukan</td></tr>');
                }
                // Tampilkan data Dosensementara
                if (data.dosensementara) {
                    var dosensementaraDataHtml = '';
                    $.each(data.dosensementara, function(key, value) {
                        // Ubah jenis kelamin dari M menjadi "Laki-laki" dan F menjadi "Perempuan"
                        if (key === 'Status') {
                        value = (value === 'M') ? 'Menikah' : (value === 'S') ? 'Lajang' : '-';
                        }
                        dosensementaraDataHtml += '<tr><td>' + key + '</td><td>' + (value ? value : '-') + '</td></tr>';
                    });
                    $('#dosensementaraData').html(dosensementaraDataHtml);
                } else {
                    // Jika data dosensementara kosong, tampilkan placeholder
                    var dosensementaraDataHtml = '';
                    var columnNames = [ 'Email', 'Telepon', 'HP', 'NIK', 'NPWP', 'Ketenagakerjaan', 'BPJS',
                                                                    'No Rek', 'Alamat', 'Agama', 'Status'];
                    $.each(columnNames, function(index, columnName) {
                        dosensementaraDataHtml += '<tr><td>' + columnName + '</td><td>-</td></tr>';
                    });
                    $('#dosensementaraData').html(dosensementaraDataHtml);
                }
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });
});
$(document).ready(function() {
    $('#approveButton').click(function() {
        var idDosen = $('#iddosen').val();
        var token = $('meta[name="csrf-token"]').attr('content'); // Dapatkan nilai token CSRF

        $.ajax({
            url: '/profil/approve',
            method: 'POST',
            data: {
                iddosen: idDosen,
                _token: token // Sertakan token CSRF dalam data permintaan
            },
            success: function(response) {              
                // Tampilkan SweetAlert sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Approved!',
                    text: 'Data has been approved successfully.',
                });
            },
            error: function(xhr, status, error) {
                // Tampilkan SweetAlert error
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while approving data.',
                });
            }
        });
    });
})
$(document).ready(function() {
    $('#rejectButton').click(function() {
        var idDosen = $('#iddosen').val();
        var token = $('meta[name="csrf-token"]').attr('content'); // Dapatkan nilai token CSRF

        $.ajax({
            url: '/profil/reject',
            method: 'POST',
            data: {
                iddosen: idDosen,
                _token: token // Sertakan token CSRF dalam data permintaan
            },
            success: function(response) { 
                // Tampilkan SweetAlert sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Rejected!',
                    text: 'Data rejection has been processed.',
                });
            },
            error: function(xhr, status, error) {
                // Tampilkan SweetAlert error
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while rejecting data.',
                });
            }
        });
    });
});

    </script>
@endsection
