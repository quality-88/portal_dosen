@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
  
    <!-- Modal -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Isi form tambahan di sini -->
                    <form id="tambahForm">
                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <select class="form-select" id="kelas" name="kelas" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose Kelas...</option>
                                <!-- Tambahkan elemen option dengan class "kelas-option" -->
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="kurikulum" class="form-label">Kurikulum</label>
                    <select class="form-select" id="kurikulum" name="kurikulum" aria-label="Default select example" required>
                        <option value="" disabled selected>Choose Kurikulum...</option>
                        @foreach($allKurikulum as $data)
                        <option value="{{ $data->kurikulum }}" data-tahunajaran1="{{ $data->tahunajaran1 }}">
                            {{ $data->kurikulum }} - {{ $data->tahunajaran1 }}</option>
                    
                        @endforeach
                    </select>
                        </div>
                        <div class="form-group">
                            <label for="idmk">IDMK</label>
                            <select class="form-select" id="idmk" name="idmk" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose Kelas...</option>
                                <!-- Tambahkan elemen option dengan class "kelas-option" -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="matakuliah">Mata Kuliah</label>
                            <input type="text" class="form-control" id="matakuliah" name="matakuliah" placeholder="matakuliah" readonly>
                            <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                        </div>
                        <div class="form-group">
                            <label for="sks">SKS</label>
                            <input type="text" class="form-control" id="sks" name="sks" >
                            <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                        </div>
                        <div class="form-group">
                            <label for="nilai">Nilai</label>
                            <select class="form-select" id="nilai" name="nilai" required>
                                <option value="">-</option>
                                <option value="A">A</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B">B</option>
                                <option value="B-">B-</option>
                                <option value="C+">C+</option>
                                <option value="C">C</option>
                                <option value="C-">C-</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hasil">Hasil Pengakuan</label>
                            <select class="form-select" id="hasil" name="hasil" required>
                                <option value="">-</option>
                                <option value="Di Akui Dengan Syarat Tertentu">Di Akui Dengan Syarat Tertentu</option>
                                <option value="Di Akui Lansung">Di Akui Lansung</option>
                                <option value="Wajib">Wajib</option>
                            </select>
                        </div>
                        <!-- Tambah input form sesuai kebutuhan -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="simpanData">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="row g-3" action="{{ route('viewKurikulum') }}" method="POST">
                                @csrf
                                <div class="col-md-4">
                                    <label for="idkampus" class="form-label">ID Kampus </label>
                                    <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                        <option value="" disabled selected>Choose ID Kampus...</option>
                                        @foreach($allIdKampus as $data)
                                            <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}">
                                                {{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" readonly >
                                </div>
                                <div class="col-md-4">
                                    <label for="prodi" class="form-label">Prodi</label>
                                    <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()" required>
                                        <option value="" disabled selected>Choose Prodi...</option>
                                        @foreach($allProdi as $prodi)
                                            <option value="{{ $prodi->prodi }}">{{ $prodi->prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="id_fakultas" class="form-label">ID Fakultas</label>
                                    <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" readonly required>
                                </div>
                                <div class="col-md-4">
                                    <label for="fakultas" class="form-label">Fakultas</label>
                                    <input type="text" class="form-control" id="fakultas" name="fakultas" readonly required>
                                </div>
                                <div class="col-md-2">
                                    <label for="ta" class="form-label">TA</label>
                                    <input type="text" class="form-control" id="ta" name="ta" placeholder="TA"
                                      required>
                                </div>
                                <div class="col-md-2">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                   <div class="custom-tabs">
                       <span class="tab-item" onclick="openTab(event, 1)">Senin</span>
                       <span class="tab-item" onclick="openTab(event, 2)">Selasa</span>
                       <span class="tab-item" onclick="openTab(event, 3)">Rabu</span>
                       <span class="tab-item" onclick="openTab(event, 4)">Kamis</span>
                       <span class="tab-item" onclick="openTab(event, 5)">Jumat</span>
                       <span class="tab-item" onclick="openTab(event, 6)">Sabtu</span>
                       
                   </div>
                    <div id="1" class="tabcontent">
                        
                    </div>
                    <div id="2" class="tabcontent">
                    </div>
                    <div id="3" class="tabcontent">
                    </div>
                    <div id="4" class="tabcontent">
                    </div>
                    <div id="5" class="tabcontent">
                    </div>
                    <div id="6" class="tabcontent">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
</div>
<style>
 .table {
    width: 100%; /* Menjadikan tabel 100% lebar dari card-body */
}

.card-body {
    overflow-x: auto; /* Mengaktifkan overflow horizontal untuk card-body jika konten melebihi lebar */
}
.tab-item {
    cursor: pointer;
}
.custom-tabs {
    /* Menetapkan ruang sekitar elemen .custom-tabs */
    margin: 20px 0; /* Atur ruang atas dan bawah 20px, tanpa margin di sisi kanan dan kiri */
}

.custom-tabs span {
    /* Menetapkan jarak antara setiap tulisan hari */
    margin-right: 10px; /* Atur jarak sebesar 10px antara setiap elemen span */
}
.tabcontent {
    display: none; /* Sembunyikan tabcontent saat halaman dimuat */
    height: 300px; /* Ganti nilai ini sesuai dengan kebutuhan Anda */
    overflow-y: auto; /* Mengaktifkan overflow secara vertikal agar tabel dapat di-scroll jika kontennya lebih panjang */
}
 </style>
<!-- Your custom JavaScript to load and display PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<!-- Add these lines to the head section of your HTML document -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
<!-- Script untuk QRCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
<script>
        function updateFakultas() {
        var selectedProdi = $('#prodi').val();
                
        if (selectedProdi) {
            $.ajax({
                url: '/fetchFakultas',
                method: 'GET',
                data: { prodi: selectedProdi },
                success: function (response) {
                    if (response.no_data) {
                        alert('No data found for the selected Prodi. Please choose a different Prodi.');
                    } else {
                        $('#id_fakultas').val(response.idfakultas);
                        $('#fakultas').val(response.fakultas);
                    }
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        } 
        
    }
    
jQuery(document).ready(function ($) {
    // Initialize flatpickr for date fields
    flatpickr('#date', { dateFormat: 'Y-m-d' });
    flatpickr('#endDate', { dateFormat: 'Y-m-d' });
    // Event handlers
    $('#idkampus').change(function () {
        var idKampus = $(this).find(':selected').val();
        var lokasi = $(this).find(':selected').data('lokasi');
        console.log('ID Kampus:', idKampus);
        console.log('Lokasi:', lokasi);
        $("#lokasi").val(lokasi);
    });
    $('#kurikulum').change(function () {
    var kurikulum = $(this).find(':selected').val();
    var tahunajaran1 = $(this).find(':selected').data('lokasi');
    console.log('ID Kampus:', kurikulum);
    console.log('Lokasi:', tahunajaran1);
    });
    $('#prodi').change(function () {
        updateFakultas();
    })
         });                      
// Event listener untuk tombol tambah pada tableContent

        // Event listener for delete button click
        $('.delete-btn').on('click', function () {
            // Retrieve data attributes
            var idPrimary = $(this).data('idprimary');
            var npm = $(this).data('npm');
            var idMkAsal = $(this).data('idmkasal');
            var idMk = $(this).data('idmk');
            // Reference to the current button
            var $button = $(this);

            // Show confirmation dialog
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menghapus data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete the item
                    $.ajax({
                        url: '/delete',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            idPrimary: idPrimary,
                            npm: npm,
                            idMkAsal: idMkAsal,
                            idMk: idMk
                        },
                        success: function (response) {
                            // Handle success response
                            console.log(response);
                            // Remove the deleted row from the table
                            $button.closest('tr').remove();
                            // Update the numbering of remaining rows
                            updateRowNumbers();
                        },
                        error: function (xhr, status, error) {
                            // Handle error response
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        // Tambahkan skrip untuk menyimpan data dari form tambahan di sini
        $('#simpanData').on('click', function () {
            // Simpan data menggunakan AJAX atau cara lain sesuai kebutuhan
            // Setelah berhasil, tutup modal
            $('#tambahModal').modal('hide');
        });

        $('.edit-btn').on('click', function () {
            // Retrieve data attributes
            var idPrimary = $(this).data('idprimary');
            var npm = $(this).data('npm');
            var idMkAsal = $(this).data('idmkasal');
            var idMk = $(this).data('idmk');
            // Optionally, you can redirect the user to an edit page with the necessary parameters
            window.location.href = '/edit?idPrimary=' + idPrimary + '&npm=' + npm + '&idMkAsal=' + idMkAsal + '&idMk=' + idMk;
        });



    // Function to update row numbers
    function updateRowNumbers() {
        $('.table tbody tr').each(function (index) {
            $(this).find('td:first').text(index + 1);
        });
    }
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
    var tabContentElement = document.getElementById(tabName);
    tabContentElement.style.height = "300px";
    tabContentElement.style.overflowY = "auto";
    
    // Ambil nomor hari dari tabName dan kirimkan permintaan AJAX untuk mendapatkan jadwal
    var harijadwal = tabName;
    var idkampus = $('#idkampus').val();
    var prodi = $('#prodi').val();
    var idfakultas = $('#id_fakultas').val();
    var ta = $('#ta').val();
    var semester = $('#semester').val();

    $.ajax({
        url: '/fetchJadwal',
        method: 'GET',
        data: {
            harijadwal: harijadwal,
            idkampus: idkampus,
            prodi: prodi,
            idfakultas: idfakultas,
            ta: ta,
            semester: semester
        },
        success: function(response) {
            // Update isi tabel dengan data jadwal yang diterima dari server
            var tableContent = '<button id="tambahButton" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-toggle="modal" data-target="#tambahModal">Tambah<i class="btn-icon-prepend"></i></button>';
           
            tableContent += '<table class="table">';
            tableContent += '<thead><tr>';
            tableContent += '<th>Kelas</th>';
            tableContent += '<th>Kurikulum</th>';
            tableContent += '<th>IDMK</th>';
            tableContent += '<th>Matakuliah</th>';
            tableContent += '<th>SKS</th>';
            tableContent += '<th>ID Ruang</th>';
            tableContent += '<th>Jam Masuk</th>';
            tableContent += '<th>Jam Keluar</th>';
            tableContent += '<th>No Silabus</th>';
            tableContent += '<th>Nama</th>';
            tableContent += '<th>Keterangan</th>';
            tableContent += '<th>Honor SKS</th>';
            tableContent += '<th>ID DOSEN PENGAJAR 1</th>';
            tableContent += '<th>NAMA DOSEN 1</th>';
            tableContent += '<th>SK</th>';
            tableContent += '</tr></thead>';
            tableContent += '<tbody>';

            response.forEach(function(row) {
                tableContent += '<tr>';
                tableContent += '<td>' + row.kelas + '</td>';
                tableContent += '<td>' + row.kurikulum + '</td>';
                tableContent += '<td>' + row.idmk + '</td>';
                tableContent += '<td>' + row.matakuliah + '</td>';
                tableContent += '<td>' + row.sks + '</td>';
                tableContent += '<td>' + row.idruang + '</td>';
                tableContent += '<td>' + row.jammasuk + '</td>';
                tableContent += '<td>' + row.jamkeluar + '</td>';
                tableContent += '<td>' + row.nosilabus + '</td>';
                tableContent += '<td>' + row.nama + '</td>';
                tableContent += '<td>' + row.Keterangan + '</td>';
                tableContent += '<td>' + parseFloat(row.HonorSKS).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td>';

                tableContent += '<td>' + row.iddosen2 + '</td>';
                tableContent += '<td>' + row.nama_dosen2 + '</td>';
                tableContent += '<td>' + row.nosilabus + '</td>';
                tableContent += '</tr>';
            });

            tableContent += '</tbody></table>';
            $('#'+tabName).html(tableContent);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
$(document).on('click', '#tambahButton', function () {
    $('#tambahModal').modal('show'); // Menampilkan modal saat tombol tambah ditekan
});
// Event listener untuk menangkap perubahan pada elemen idkampus di dalam modal
$('#idkampus').change(function () {
    var idKampus = $(this).find(':selected').val();
    var ta = $('#ta').val();
 var semester = $('#semester').val();
 var kurikulum = $('#kurikulum').val();
 var prodi = $('#prodi').val();
 console.log('ID Kampus:', idKampus);
 console.log('semester:', semester);
 console.log('ta:', ta);
 console.log(':', kurikulum);
 console.log(':', prodi);
    console.log('ID Kampus:', idKampus);
    $('#kelas').empty().append('<option value="" disabled selected>Choose Kelas...</option>');
    // Kirim permintaan AJAX untuk memuat daftar kelas berdasarkan idkampus yang dipilih
    $.ajax({
        url: 'inputjadwal/getKelas',
        method: 'GET',
        data: { idkampus: idKampus },
        success: function (response) {
            // Perbarui opsi dropdown untuk kelas di dalam modal dengan daftar kelas yang diterima dari server
            var selectKelas = $('#kelas');
            selectKelas.empty(); // Kosongkan opsi dropdown sebelum memuat kelas baru
            selectKelas.append('<option value="" disabled selected>Choose Class...</option>'); // Tambahkan opsi default
            
            // Tambahkan opsi untuk setiap kelas yang diterima dari server
            response.forEach(function (kelas) {
                selectKelas.append('<option value="' + kelas.kelas + '">' + kelas.kelas + '</option>');
            });
        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });
});
$('#idmk').change(function () {
    var ta = $('#ta').val();
    var semester = $('#semester').val();
    var kurikulum = $('#kurikulum').val();
    var prodi = $('#prodi').val();
    console.log('ID Kampus:', idKampus);
    console.log('ID Kampus:', semester);
    console.log('ID Kampus:', ta);
    console.log('ID Kampus:', kurikulum);
    console.log('ID Kampus:', prodi);
    $.ajax({
        url: 'inputjadwal/getIDMK',
        method: 'GET',
        data: { ta: ta, semester: semester, kurikulum: kurikulum, prodi: prodi },
        success: function (response) {
            var selectKelas = $('#idmk');
            selectKelas.empty(); // Kosongkan opsi dropdown sebelum memuat IDMK baru
            selectKelas.append('<option value="" disabled selected>Choose IDMK...</option>'); // Tambahkan opsi default
            
            // Tambahkan opsi untuk setiap IDMK yang diterima dari server
            $.each(response, function(data) {
                selectKelas.append('<option value="' + data.idmk + '">' + data.idmk + ' - ' + data.NamaDosen + ' - ' + data.NamaMataKuliah + ' (' + data.sks + ' SKS)</option>');
            });
        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });
});

</script>

@endsection
