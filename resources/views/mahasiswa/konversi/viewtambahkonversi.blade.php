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
                            <label for="idmkasal">IDMK Asal</label>
                            <input type="text" class="form-control" id="idmkasal" name="idmkasal" required>
                        </div>
                        <div class="form-group">
                            <label for="matkulasal">Mata Kuliah Asal</label>
                            <input type="text" class="form-control" id="matkulasal" name="matkulasal" required>
                        </div>
                        <div class="form-group">
                            <label for="sksasal">SKS Asal</label>
                            <input type="text" class="form-control" id="sksasal" name="sksasal" required>
                        </div>
                        <div class="form-group">
                            <label for="idmk">IDMK</label>
                            <input type="text" class="form-control" id="idmk" name="idmk" placeholder="Mata Kuliah" required>
                            <ul id="resultList" style="display: none;"></ul>
                        </div>
                        <div class="form-group">
                            <label for="matakuliah">Mata Kuliah</label>
                            <input type="text" class="form-control" id="matakuliah" name="matakuliah" placeholder="matakuliah" readonly>
                            <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                        </div>
                        <div class="form-group">
                            <label for="sks">SKS</label>
                            <input type="text" class="form-control" id="sks" name="sks" readonly>
                            <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                        </div>
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <input type="text" class="form-control" id="semester" name="semester" readonly>
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
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">-</option>
                                <option value="F">Pasangan</option>
                                <option value="T">Tidak ada Pasangan</option>
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

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showTambahKonversi') }}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Input Konversi Mahasiswa</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Tombol untuk membuka modal -->
                            <button id="tambahButton" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-toggle="modal" data-target="#tambahModal">Tambah
                                <i class="btn-icon-prepend"></i>
                            </button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($hasilKonversi) && count($hasilKonversi) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IDMK Asal</th>
                                    <th>Mata Kuliah Asal</th>
                                    <th>SKS Asal</th>
                                    <th>IDMK Tujuan</th>
                                    <th>Mata Kuliah Tujuan</th>
                                    <th>SKS Tujuan</th>
                                    <th>Nilai</th>
                                    <th>Bobot</th>
                                    <th>Hasil Pengakuan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilKonversi as $key => $result)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $result->IDMK }}</td>
                                    <td>{{ $result->MATAKULIAH }}</td>
                                    <td>{{ $result->SKS }}</td>
                                    <td>{{ $result->IDMKTUJUAN }}</td>
                                    <td>{{ $result->MATAKULIAHTUJUAN }}</td>
                                    <td>{{ $result->SKSTUJUAN }}</td>
                                    <td>{{ $result->NILAIAKHIR }}</td>
                                    <td>{{ $result->BobotNilai }}</td>
                                    <td>{{ $result->HasilPengakuan }}</td>
                                    <td>{{ $result->HasilKonversi }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                                data-idprimary="{{ $result->idPrimary }}"
                                                data-npm="{{ $result->npm }}"
                                                data-idmkasal="{{ $result->IDMK }}"
                                                data-idmk="{{ $result->IDMKTUJUAN }}">Hapus</button>
                                        <button class="btn btn-primary btn-sm edit-btn" 
                                        data-idprimary="{{ $result->idPrimary }}"
                                        data-npm="{{ $result->npm }}"
                                        data-idmkasal="{{ $result->IDMK }}"
                                        data-idmk="{{ $result->IDMKTUJUAN }}">Edit</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.0/sweetalert2.js" 
integrity="sha512-n+FwLK5s6dd4XL68lrwGn1j9TSCTFA15TgF7KbcShrGV7Ma761MniYPUAz0PPipTi18IXLbr+Ag9cxrEvIeASw=="
 crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Script untuk QRCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
<script>
    $(document).ready(function () {
        // Inisialisasi modal
        $('#tambahButton').on('click', function () {
        // Tampilkan modal tambahan
        $('#tambahModal').modal('show');
    });
        // Event listener for delete button click
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
                url: 'viewtambahkonversinilai/delete',
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
            }).then((result) => {
                // Reload the page
                location.reload();
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
            window.location.href = 'viewtambahkonversinilai/edit?idPrimary=' + idPrimary + '&npm=' + npm + '&idMkAsal=' + idMkAsal + '&idMk=' + idMk;
        });
    });

    // Function to update row numbers
    function updateRowNumbers() {
        $('.table tbody tr').each(function (index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    $('#idmk').on('input', function () {
    var searchQuery = $(this).val();

    if (searchQuery.length >= 2) {
        $.ajax({
            url: 'viewtambahkonversinilai/searchMatakuliah',
            method: 'GET',
            data: { term: searchQuery},
            success: function (data) {
                var resultList = $('#resultList');
                resultList.empty();

                console.log('Server Response:', data);

                resultList.show();

                data.forEach(function (result) {
                    resultList.append('<li data-id="' + result.IDMK + '" data-sks="' + result.SKS + 
                    '" data-semester="' + result.semester+'">' + result.IDMK + 
                        ' - ' + result.MATAKULIAH + '</li>');

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
        
            var idmk = splitResult.length > 1 ? splitResult[0] : '';
            var matakuliah = splitResult.length > 1 ? splitResult[1] : '';
        
            console.log('idmk:', idmk);
            console.log('Matakuliah:', matakuliah);
        
            $('#idmk').val(idmk);
            $('#matakuliah').val(matakuliah);

            // Ambil nilai SKS dari objek result
            var sks = $(this).data('sks');
            var semester = $(this).data('semester');
            $('#sks').val(sks);
            $('#semester').val(semester);
            if (!idmk) {
                $('#matakuliah').val('');
                $('#sks').val('');
                $('#semester').val('');
            }
        
            $('#resultList').hide();
        });
        $('#simpanData').on('click', function () {
    // Mengambil nilai dari form
    var idmkasal = $('#idmkasal').val();
    var matkulasal = $('#matkulasal').val();
    var sksasal = $('#sksasal').val();
    var idmk = $('#idmk').val();
    var matakuliah = $('#matakuliah').val();
    var sks = $('#sks').val();
    var semester = $('#semester').val();
    var nilai = $('#nilai').val();
    var hasil = $('#hasil').val();
    var status = $('#status').val();
    var npm = '{{ session('npm') }}';

    // Menghitung bobot berdasarkan nilai
    var bobot = 0;
    if (nilai === 'A') {
        bobot = sks * 4.00;
    } else if (nilai === 'A-') {
        bobot = sks * 3.75;
    } else if (nilai === 'B+') {
        bobot = sks * 3.5;
    } else if (nilai === 'B') {
        bobot = sks * 3.00;
    } else if (nilai === 'B-') {
        bobot = sks * 2.75;
    } else if (nilai === 'C+') {
        bobot = sks * 2.5;
    } else if (nilai === 'C') {
        bobot = sks * 2.0;
    } else if (nilai === 'C-') {
        bobot = sks * 1.75;
    }

    // Menyusun data untuk dikirim ke server
    var formData = {
        idmkasal: idmkasal,
        matkulasal: matkulasal,
        sksasal: sksasal,
        idmk: idmk,
        matakuliah: matakuliah,
        sks: sks,
        nilai: nilai,
        bobot: bobot,
        hasil: hasil,
        status: status
    };

    // Kirim data menggunakan AJAX
    $.ajax({
        url: 'viewtambahkonversinilai/simpanData',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            // Handle success response
            console.log(response);
            // Tutup modal setelah berhasil disimpan
            $('#tambahModal').modal('hide');
            // Tampilkan notifikasi
            Swal.fire({
                title: 'Sukses!',
                text: 'Data berhasil disimpan',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Reload tampilan data
                window.location.href = "{{ route('changeView') }}?npm=" + npm;
            });

        },
        error: function (xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText);
            // Parse JSON response
            var response = JSON.parse(xhr.responseText);
            // Tampilkan pesan kesalahan kepada pengguna
            if (response.message) {
                // Jika terdapat pesan kesalahan dari server, tampilkan pesan tersebut menggunakan SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                // Jika tidak ada pesan kesalahan yang diberikan oleh server, tampilkan pesan kesalahan umum
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
});


</script>

@endsection
