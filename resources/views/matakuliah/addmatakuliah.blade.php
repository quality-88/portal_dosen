@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
   
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('showMatakuliah')}}">Form</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Matakuliah</li>
    </ol>
</nav>
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Tambah Matakuliah</h4>
                    <form id="tambahMatakuliahForm">
                        @csrf     
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">IDMK</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="idmk" name="idmk"  required>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Mata Kuliah</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="matakuliah" name="matakuliah" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Prodi</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()" required>
                                        <option value="" disabled>Choose Prodi...</option>
                                        @foreach($allProdi as $prodiItem)
                                            <option value="{{ $prodiItem->prodi }}">{{ $prodiItem->prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Golongan Mata Kuliah</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <select class="form-select" id="golmk" name="golmk" aria-label="Default select example"  required>
                                        <option value="" disabled>Choose Tipe...</option>
                                        @foreach($allTipe as $tipe)
                                            <option value="{{ $tipe->tipe }}">{{ $tipe->tipe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- SKS Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="sks" class="col-form-label me-2">SKS</label>
                                <input type="text" class="form-control" id="sks" name="sks" required>
                            </div>
                            <!-- Teori Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="teori" class="col-form-label me-2">Teori</label>
                                <input type="text" class="form-control" id="teori" name="teori" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="praktek" class="col-form-label me-2">Praktek</label>
                                <input type="text" class="form-control" id="praktek" name="praktek" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="seminar" class="col-form-label me-2">Seminar</label>
                                <input type="text" class="form-control" id="seminar" name="seminar"  required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- Sikap Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="sikap" class="col-form-label me-2">Sikap</label>
                                <select class="form-control" id="sikap" name="sikap" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                            <!-- Keterampilan Khusus Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="k_khusus" class="col-form-label me-2">Keterampilan Khusus</label>
                                <select class="form-control" id="k_khusus" name="k_khusus" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                            <!-- Keterampilan Umum Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="k_umum" class="col-form-label me-2">Keterampilan Umum</label>
                                <select class="form-control" id="k_umum" name="k_umum" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                            <!-- Pengetahuan Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="pengetahuan" class="col-form-label me-2">Pengetahuan</label>
                                <select class="form-control" id="pengetahuan" name="pengetahuan" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- Semester Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="semester" class="col-form-label me-2">Semester</label>
                                <select class="form-control" id="semester" name="semester" required>
                                    <option value="" disabled>Pilih Semester...</option>
                                    @foreach($allSemester as $semester)
                                        <option value="{{ $semester->idprimary }}">{{ $semester->semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- PKL Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="pkl" class="col-form-label me-2">PKL</label>
                                <select class="form-control" id="pkl" name="pkl" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                            <!-- Skripsi Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="skrpsi" class="col-form-label me-2">Skripsi</label>
                                <select class="form-control" id="skrpsi" name="skrpsi" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                            <!-- RPL Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="rpl" class="col-form-label me-2">RPL</label>
                                <select class="form-control" id="rpl" name="rpl" required>
                                    <option value="" disabled>...</option>
                                    <option value="Y">Ya</option>
                                    <option value="N">Tidak</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary float-end">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     
    </div>



<!-- Include Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />                    
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tambahMatakuliahForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('tambahMatakuliah') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Optionally, redirect or reset the form
                        window.location.href = "{{ route('showMatakuliah') }}";
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem saving the data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endsection
