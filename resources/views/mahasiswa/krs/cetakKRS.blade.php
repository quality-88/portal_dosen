@extends('admin.dashboard')

@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-content">
    <div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Cetak KRS Mahasiswa</h4>
                <form class="row g-5" action="{{ route('cetakKRS') }}" method="POST">
                    @csrf
                    <div class="col-md-4">
                        <label for="npm" class="form-label">NPM</label>
                        <div class="input-group">
                        <input type="text" class="form-control" id="npm" name="npm" placeholder="Masukkan Nama atau NPM" required>
                        <button type="button" class="btn btn-outline-primary" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                       
                        <ul id="resultList" style="display: none;"></ul>
                    </div>
                    </div>
                    <div class="col-md-4">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="ta" class="form-label">TA</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="ta" name="ta" placeholder="TA" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


                        <!-- Hasil pencarian -->
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-right">Data Mahasiswa</h5>
                                        <hr class="my-3">
                                        <form class="row g-3">
                                            <div class="col-md-6">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="nama" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="idKampus" class="form-label">ID Kampus</label>
                                                <input type="text" class="form-control" id="idKampus" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="lokasi" class="form-label">Lokasi</label>
                                                <input type="text" class="form-control" id="lokasi" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="idFakultas" class="form-label">ID Fakultas</label>
                                                <input type="text" class="form-control" id="idFakultas" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fakultas" class="form-label">Fakultas</label>
                                                <input type="text" class="form-control" id="fakultas" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="prodi" class="form-label">Prodi</label>
                                                <input type="text" class="form-control" id="prodi" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="kurikulum" class="form-label">Kurikulum</label>
                                                <input type="text" class="form-control" id="kurikulum" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tipeKelas" class="form-label">Tipe Kelas</label>
                                                <input type="text" class="form-control" id="tipeKelas" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="namaDosen" class="form-label">Dosen Wali</label>
                                                <input type="text" class="form-control" id="namaDosen" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" class="form-control" id="alamat" readonly>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
    #resultList {
   max-height: 200px; /* Set a maximum height for the list */
   overflow-y: auto; /* Add a scrollbar when the list overflows */
   position: absolute;
   width: 100%; /* Make the list full-width */
   z-index: 1000; /* Adjust the z-index to make sure the list appears above other elements */
   background-color: #ffffff; /* Set a background color */
   border-radius: 5px; /* Optional: Add border-radius for rounded corners */
   top: calc(100% + 10px); /* Position the resultList below the input field */
   left: 0; /* Align the left edge of resultList with the left edge of its containing block */
   padding: 0; /* Remove padding to align with the input field */
   margin: 0; /* Remove margin to align with the input field */
 }
 
 #resultList li {
   padding: 8px; /* Add padding to each list item */
   cursor: pointer; /* Change the cursor to a pointer for better user experience */
   list-style: none; /* Remove default list styling */
   border-bottom: 1px solid #ccc; /* Add a border between list items */
 }
 
 #resultList li:last-child {
   border-bottom: none; /* Remove border from the last list item */
 }
 </style>

    
    <script>
        $('#npm').on('input', function () {
            var searchQuery = $(this).val();
    
            if (searchQuery.length >= 4) {
                // Lakukan permintaan AJAX ke server untuk mencari Dosen
                $.ajax({
                    url: 'cetakkrs/findMahasiswa',
                    method: 'GET',
                    data: { term: searchQuery },
                    success: function (data) {
                        var resultList = $('#resultList');
                        resultList.empty();
    
                        console.log('Server Response:', data);
    
                        resultList.show();
    
                        data.forEach(function (result) {
                            resultList.append('<li data-id="' + result.npm + '">' + result.npm + ' - ' + result.nama + '</li>');
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
    
            var npm = splitResult.length > 1 ? splitResult[0] : '';
            var nama = splitResult.length > 1 ? splitResult[1] : '';
    
            console.log('id dosen:', npm);
            console.log('Nama:', nama);
    
            $('#npm').val(npm);
            $('#nama').val(nama);
    
            if (!npm) {
                $('#nama').val('');
            }
    
            $('#resultList').hide();
        });
        $('#searchButton').click(function () {
    var npm = $('#npm').val();

    // Lakukan permintaan AJAX ke server untuk mencari Mahasiswa
    $.ajax({
        url: 'cetakkrs/showMahasiswa',
        method: 'GET',
        data: { npm: npm },
        success: function (data) {
            console.log('Server Response:', data);

            // Memasukkan data mahasiswa ke dalam input form
            var mahasiswa = data; // mengambil objek pertama dari array (asumsi hanya ada satu objek)
            $('#nama').val(mahasiswa.NAMA);
            $('#idKampus').val(mahasiswa.IDKAMPUS);
            $('#lokasi').val(mahasiswa.LOKASI);
            $('#idFakultas').val(mahasiswa.IDFAKULTAS);
            $('#fakultas').val(mahasiswa.FAKULTAS);
            $('#prodi').val(mahasiswa.PRODI);
            $('#kurikulum').val(mahasiswa.kurikulum);
            $('#tipeKelas').val(mahasiswa.tipekelas);
            $('#namaDosen').val(mahasiswa.NamaDosen);
            $('#alamat').val(mahasiswa.Alamat);
        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });
});


    </script>
@endsection
