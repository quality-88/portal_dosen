@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('showMatakuliah')}}">Form</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Matakuliah</li>
    </ol>
</nav>
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Detail Matakuliah</h4>
                    <form action="{{ route('matakuliah.update') }}" method="POST">

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
                            <label class="col-sm-2 col-form-label">Mata Kuliah</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="matakuliah" name="matakuliah" 
                                    value="{{ $results->MATAKULIAH }}" required>
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
                                            @php
                                                $selected = (session('prodi') == $prodiItem->prodi) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $prodiItem->prodi }}" {{ $selected }}>{{ $prodiItem->prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Golongan Mata Kuliah</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <select class="form-select" id="tipe" name="tipe" aria-label="Default select example" required>
                                        <option value="" disabled>Choose Tipe...</option>
                                        @foreach($allTipe as $tipe)
                                            <option value="{{ $tipe->tipe }}" {{ $results->TIPE == $tipe->tipe ? 'selected' : '' }}>
                                                {{ $tipe->tipe }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <!-- SKS Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="sks" class="col-form-label me-2">SKS</label>
                                <input type="text" class="form-control" id="sks" name="sks" 
                                value="{{ $results->SKS }}" required>
                            </div>
                            <!-- Teori Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="teori" class="col-form-label me-2">Teori</label>
                                <input type="text" class="form-control" id="teori" name="teori" 
                                value="{{ $results->T }}" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="praktek" class="col-form-label me-2">Praktek</label>
                                <input type="text" class="form-control" id="praktek" name="praktek" 
                                value="{{ $results->P }}" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="seminar" class="col-form-label me-2">Seminar</label>
                                <input type="text" class="form-control" id="seminar" name="seminar" 
                                value="{{ $results->SEMINAR }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- Sikap Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="sikap" class="col-form-label me-2">Sikap</label>
                                <select class="form-control" id="sikap" name="sikap" required>
                                    <option value="Y" {{ $results->SIKAP == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->SIKAP == 'N' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <!-- Keterampilan Khusus Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="k_khusus" class="col-form-label me-2">Keterampilan Khusus</label>
                                <select class="form-control" id="k_khusus" name="k_khusus" required>
                                    <option value="Y" {{ $results->K_KHUSUS == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->K_KHUSUS == 'N' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <!-- Keterampilan Umum Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="k_umum" class="col-form-label me-2">Keterampilan Umum</label>
                                <select class="form-control" id="k_umum" name="k_umum" required>
                                    <option value="Y" {{ $results->K_UMUM == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->K_UMUM == 'N' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <!-- Pengetahuan Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="pengetahuan" class="col-form-label me-2">Pengetahuan</label>
                                <select class="form-control" id="pengetahuan" name="pengetahuan" required>
                                    <option value="Y" {{ $results->PENGETAHUAN == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->PENGETAHUAN == 'N' ? 'selected' : '' }}>Tidak</option>
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
                                        <option value="{{ $semester->idprimary }}" {{ $results->SEMESTER == $semester->semester ? 'selected' : '' }}>
                                            {{ $semester->semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- PKL Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="pkl" class="col-form-label me-2">PKL</label>
                                <select class="form-control" id="pkl" name="pkl" required>
                                    <option value="Y" {{ $results->PKL == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->PKL == 'N' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <!-- Skripsi Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="skrpsi" class="col-form-label me-2">Skripsi</label>
                                <select class="form-control" id="skrpsi" name="skrpsi" required>
                                    <option value="Y" {{ $results->SKRIPSI == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->SKRIPSI == 'N' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <!-- RPL Field -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="rpl" class="col-form-label me-2">RPL</label>
                                <select class="form-control" id="rpl" name="rpl" required>
                                    <option value="Y" {{ $results->Rpl == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ $results->Rpl == 'N' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary float-end">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel untuk result1 -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Dosen Pengampu</h4>
                        <!-- Tombol Add Dosen Pengampu -->
                        <a href="{{ route('matakuliah.addPengampu', ['idmk' => $results->IDMK]) }}" class="btn btn-primary mb-3">Add Dosen Pengampu</a>

                        <!-- Tabel untuk result1 -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>IDDOSEN</th>
                                    <th>Nama</th>
                                    <th>TA</th>
                                    <th>Semester</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result1 as $item1)
                                <tr>
                                    <td>{{ $item1->IdDosenPengampu }}</td>
                                    <td>{{ $item1->NAMADOSEN }}</td>
                                    <td>{{ $item1->TA }}</td>
                                    <td>{{ $item1->Semester }}</td>
                                    <td>
                                        <form action="{{ route('matakuliah.editPengampu') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="idPrimary" value="{{ $item1->idPrimary }}">
                                            <input type="hidden" name="idmk" value="{{ $results->IDMK }}">
                                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                        </form>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>       
        <!-- Tabel untuk result2 -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Dosen Pengajar</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Dosen</th>
                                    <th>Nama Dosen</th>
                                    <!-- Tambahkan kolom lain sesuai kebutuhan -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result2 as $item2)
                                <tr>
                                    <td>{{ $item2->iddosen }}</td>
                                    <td>{{ $item2->nama }}</td>
                                    <!-- Tambahkan data lain sesuai kebutuhan -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

@endsection
