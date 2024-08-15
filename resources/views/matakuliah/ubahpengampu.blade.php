@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
    @endif
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('showMatakuliah')}}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Pengampu</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Edit Dosen Pengampu</h4>
                    <form action="{{route('updatePengampu')}}" method="POST">

                        @csrf
                        <input type="hidden" name="idPrimary" value="{{ $results->idPrimary }}">       
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">IDMK</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="idmk" name="idmk" 
                                    value="{{ $results->IDMK }}" readonly required>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">TA</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="ta" name="ta" 
                                    value="{{ $results->TA }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Semester</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="semester" required>
                                    @foreach($allSemester as $semester)
                                        <option value="{{ $semester->semester }}" 
                                            {{ $results->Semester == $semester->semester ? 'selected' : '' }}>
                                            {{ $semester->semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">ID Dosen</label>
                            <div class="col-sm-4">
                                <!-- Input field untuk ID Dosen -->
                                <input type="text" class="form-control" id="iddosen" name="iddosen" 
                                value="{{ $results->IdDosenPengampu }}">
                                
                                <!-- Daftar hasil pencarian -->
                                <ul id="resultList" style="display: none;"></ul>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Dosen</label>
                            <div class="col-sm-4">      
                                <!-- Input field tersembunyi untuk menyimpan Nama Dosen -->
                                <input type="text" class="form-control" id="NamaDosen" name="NamaDosen" 
                                value="{{ $results->NAMADOSEN }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary float-end">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Scripts and includes here -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />                    
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    jQuery(document).ready(function ($) {
        $('#iddosen').on('input', function () {
            var searchQuery = $(this).val();

            if (searchQuery.length >= 4) {
                // Lakukan permintaan AJAX ke server untuk mencari Dosen
                $.ajax({
                    url: '{{ route("searchDosen") }}',
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
</script>
@endsection
