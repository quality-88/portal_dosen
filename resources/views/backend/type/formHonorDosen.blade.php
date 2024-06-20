@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <h4 class="mb-0">Validasi Honor Dosen SKS</h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{ route('formHonorDosen') }}" method="POST" id="honorDosen" >

                        @csrf
                        
                        <div class="col-md-6">
                            <label for="idkampus" class="form-label">ID Kampus </label>
                            <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose ID Kampus...</option>
                                @foreach($allIdKampus as $data)
                                    <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}">{{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()">
                                <option value="" disabled selected>Choose Prodi...</option>
                                
                                @foreach($allProdi as $prodi)
                                    <option value="{{ $prodi->prodi }}">{{ $prodi->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_fakultas" class="form-label">ID Fakultas</label>
                            <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="iddosen" class="form-label">ID Dosen</label>
                            <input type="text" class="form-control" id="iddosen" name="iddosen" >
                            <!-- Input field tersembunyi untuk menyimpan namadosen -->
                            <input type="hidden" id="NamaDosen" name="NamaDosen">
                            <ul id="resultList" style="display: none;"></ul>

                        </div>
                        <div class="col-md-6">
                            <label for="TA" class="form-label">T.A</label>
                            <input type="text" class="form-control" id="TA" name="TA" placeholder="T.A" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Semester" class="form-label">SEMESTER</label>
                            <select class="form-select" id="Semester" name="Semester" aria-label="Default select example" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Start Date</label>
                            <input type="text" class="form-control" id="date" name="startDate" placeholder="Start Date" data-date-format="Y-m-d" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" data-date-format="Y-m-d" required>
                        </div>
                    
                        <div class="col-md-6">
                            <button type="button" onclick="clearForm()" class="btn btn-danger btn-lg float-end">New</button>
                        </div> 
                        <div class="col-md-6">
                            <!--<button type="button" onclick="submitForm()" class="btn btn-success">Submit</button>-->
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                            
                        </div>   
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="row">
        <div class="col-md-12" id="resultContainer">
        </div>
        </div>-->
</div>
        
                    <!-- Include Bootstrap and jQuery -->
                    <!-- Tambahkan di dalam tag <head> -->
                    <!-- Include jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                    <!-- Include Select2 -->
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                        <!-- Add these lines to the head section of your HTML document -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>

                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />                    
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                    <!-- Add this script at the bottom of your <head> section -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                    <!-- Manually initialize the datepicker -->
                    
                    <script>
                        
         window.jsPDF = window.jspdf.jsPDF;                                   
        // Function to update Fakultas based on selected Prodi
        function updateFakultas() {
            var selectedProdi = $('#prodi').val();
                    
            if (selectedProdi) {
                $.ajax({
                    url: 'formHonorDosen/fetchFakultas',
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
            }    $('#iddosen').val('');
                $('#NamaDosen').val('');
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
                    
                            $('#prodi').change(function () {
                                updateFakultas();
                            })

                            // Ganti event handler untuk input
                            // Ganti event handler untuk input
$('#iddosen').on('input', function () {
    var searchQuery = $(this).val();

    if (searchQuery.length >= 4) {
        // Lakukan permintaan AJAX ke server untuk mencari Dosen
        $.ajax({
            url: 'formHonorDosen/searchDosen',
            method: 'GET',
            data: { term: searchQuery },
            success: function (data) {
                // Bersihkan elemen daftar hasil pencarian sebelum menambahkan yang baru
                var resultList = $('#resultList');
                resultList.empty();

                // Tampilkan hasil langsung sebagai JSON untuk debug
                console.log('Server Response:', data);

                // Tampilkan daftar hasil
                resultList.show();

                // Tambahkan setiap hasil ke dalam daftar
                data.forEach(function (result) {
                    resultList.append('<li data-id="' + result.iddosen + '">' + result.iddosen + ' - ' + result.nama + '</li>');
                });
            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
    } else {
        // Sembunyikan daftar jika input kurang dari 2 karakter
        $('#resultList').hide();
    }
});



                        // Tangani klik pada hasil list
                        $(document).on('click', '#resultList li', function () {
                            // Ambil ID dan Nama dari list yang dipilih
                            var fullName = $(this).text();
                            var splitResult = fullName.split(' - ');
                        
                            // Ambil ID Dosen dan Nama dari hasil split
                            var idDosen = splitResult.length > 1 ? splitResult[0] : '';
                            var namaDosen = splitResult.length > 1 ? splitResult[1] : '';
                        
                            console.log('id dosen:', idDosen);
                            console.log('Nama:', namaDosen);
                        
                            // Setel nilai input dan ID Dosen tersembunyi
                            $('#iddosen').val(idDosen);
                            $('#NamaDosen').val(namaDosen);
                        
                            // Jika idDosen kosong, kosongkan juga nilai NamaDosen
                            if (!idDosen) {
                                $('#NamaDosen').val('');
                            }
                        
                            // Sembunyikan list hasil
                            $('#resultList').hide();
                        
                        });


                                 });
                                  //function submitForm() {
                                  //    // Lakukan pengiriman formulir secara asinkron menggunakan Ajax
                                  //    // Setelah mendapatkan hasil, tampilkan hasil di bawah formulir

                                  //    // Contoh Ajax menggunakan jQuery
                                  //    $.ajax({
                                  //        type: "POST",
                                  //        url: $("#honorDosen").attr("action"),
                                  //        data: $("#honorDosen").serializeArray(),
                                  //        
                                  //        success: function (response) {
                                  //            // Tampilkan hasil di bawah formulir
                                  //            $("#resultContainer").html(response);
                                  //        },
                                  //        error: function (error) {
                                  //            console.log("Error:", error);
                                  //        }
                                  //    });
                                  //}
                                  function clearForm() {
                                        // Mengosongkan semua nilai input pada formulir
                                        $('#idkampus').val('');
                                        $('#lokasi').val('');
                                        $('#prodi').val('');
                                        $('#id_fakultas').val('');
                                        $('#fakultas').val('');
                                        $('#iddosen').val('');
                                        $('#NamaDosen').val('');
                                        $('#TA').val('');
                                        $('#Semester').val('');
                                        $('#date').val('');
                                        $('#endDate').val('');

                                        // Jika menggunakan Select2, tambahkan perintah untuk mengosongkan Select2
                                        $('#idkampus').trigger('change');
                                        $('#prodi').trigger('change');

                                        // Tambahan sesuai kebutuhan untuk mengosongkan parameter lainnya
                                        }
                    </script>
@endsection
