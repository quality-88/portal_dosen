@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Kurikulum Prodi</h4>
                    <form class="row g-3" action="{{ route('viewKurikulum') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="idkampus" class="form-label">ID Kampus </label>
                            <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose ID Kampus...</option>
                                @foreach($allIdKampus as $data)
                                @php
                                    $selected = session('idkampus') == $data->idkampus ? 'selected' : '';
                                @endphp
                                <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}" {{ $selected }}>
                                    {{ $data->idkampus }} - {{ $data->lokasi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" readonly 
                            value="{{ old('lokasi')?? session('lokasi') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" 
                            onchange="updateFakultas()" required>
                                <option value="" disabled selected>Choose Prodi...</option>
                                @foreach($allProdi as $prodi)
                                @php
                                    $selected = session('prodi') == $prodi->prodi ? 'selected' : '';
                                @endphp
                                <option value="{{ $prodi->prodi }}" {{ $selected }}>{{ $prodi->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="id_fakultas" class="form-label">ID Fakultas</label>
                            <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" readonly 
                            value="{{ old('id_fakultas') ?? session('fakultas') }}"required>
                        </div>
                        <div class="col-md-4">
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" readonly
                            value="{{ old('fakultas')?? session('fakultas') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="kurikulum" class="form-label">Kurikulum</label>
                            
                           <input type="text" class="form-control" id="kurikulum" name="kurikulum" placeholder="Kurikulum"
                                  value="{{ old('kurikulum') ?? session('kurikulum') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(isset($results) && count($results) > 0)
                    <h4 class="mb-4">Hasil Kurikulum</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <!-- Sesuaikan header tabel dengan struktur data yang diterima -->
                                    <th>ID Mata Kuliah</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->IDMK }}</td>
                                    <td>{{ $result->matakuliah }}</td>
                                    <td>{{ $result->SKS }}</td>
                                    <td>{{ $result->SEMESTER }}</td>
                                    <td>
                                        <a href="{{ route('editKurikulum', ['id' => $result->idPrimary]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $result->idPrimary }})">Hapus</button>
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
<div class="col-md-12">
    <button type="button" class="btn btn-primary btn-lg float-end" onclick="handleTambahClick()">Tambah</button>
</div>
</div>

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
                    
                    url: '{{ route("fetchFakultash") }}',
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
   
        $('#prodi').change(function () {
            updateFakultas();
        })
             });                      

       function handleTambahClick() {
        // Kumpulkan data yang diperlukan dari formulir
        var kurikulum = "{{ session('kurikulum') }}";
        var idKampus = "{{ session('idkampus') }}";
        var idFakultas = "{{ session('idFakultas') }}";
        var prodi = "{{ session('prodi') }}";
        var url = "{{ route('tambahKurikulum') }}";
        console.log('Kurikulum:', kurikulum);
        console.log('ID Kampus:', idKampus);
        console.log('ID Fakultas:', idFakultas);
        console.log('Prodi:', prodi);
    // Assuming 'idDosen', 'idMK', 'kelas', 'idKampus', 'prodi', and 'tglUAS' are your parameters
    var parameters = {
        kurikulum : kurikulum,
        idKampus : idKampus,
        idFakultas : idFakultas,
        prodi: prodi,
    };

    // Redirect to the route with parameters
    window.location.href = url + '?' + new URLSearchParams(parameters).toString();

  }

    function editClick() {
            // Kumpulkan data yang diperlukan dari formulir
            var kurikulum = "{{ session('kurikulum') }}";
            var idKampus = "{{ session('idkampus') }}";
            var idFakultas = "{{ session('idFakultas') }}";
            var prodi = "{{ session('prodi') }}";
            var url = "{{ route('tambahKurikulum') }}";
            console.log('Kurikulum:', kurikulum);
        console.log('ID Kampus:', idKampus);
        console.log('ID Fakultas:', idFakultas);
        console.log('Prodi:', prodi);
    // Assuming 'idDosen', 'idMK', 'kelas', 'idKampus', 'prodi', and 'tglUAS' are your parameters
    var parameters = {
        kurikulum : kurikulum,
        idKampus : idKampus,
        idFakultas : idFakultas,
        prodi: prodi,
    };

    // Redirect to the route with parameters
    window.location.href = url + '?' + new URLSearchParams(parameters).toString();

  }

    function deleteData(idPrimary) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                $.ajax({
                    url: "{{ route('deleteKurikulum') }}", // Pastikan ini sesuai dengan route yang akan dibuat
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // Token CSRF untuk keamanan
                        idPrimary: idPrimary
                    },
                    success: function(response) {
                        alert("Data berhasil dihapus");
                        location.reload(); // Refresh halaman setelah penghapusan berhasil
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan saat menghapus data");
                    }
                });
            }
        }
       </script>
@endsection
