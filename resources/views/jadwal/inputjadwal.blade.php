@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" class= "custom-modal-dialog">
                    <!-- Isi form tambahan di sini -->
                    <form id="tambahForm custom-modal-dialog">
                        <input hidden id="hari" name="hari">
                        <input hidden id="harijadwal" name="harijadwal">
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="kelas1" name="kelas1" placeholder="Kelas" >
                                    <button type="button" class="btn btn-outline-primary" id="searchButton8">
                                       <i class="fas fa-search" data-feather="search"></i>
                                    </button>
                                    <ul id="resultsList" style="display: none;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Kurikulum</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="kurikulum1" name="kurikulum1" placeholder="Kurikulum" required>
                                    <button type="button" class="btn btn-outline-primary" id="searchButton7">
                                        <i class="fas fa-search" data-feather="search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">IDMK</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="idmk" name="idmk" placeholder="Masukkan Mata Kuliah" required>
                                    <button type="button" class="btn btn-outline-primary" id="searchButton1">
                                        <i class="fas fa-search" data-feather="search"></i>
                                    </button>
                                    <ul id="resultList" style="display: none;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Mata Kuliah</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="matakuliah" name="matakuliah" placeholder="matakuliah" readonly>
                                    <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">SKS</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="sks" name="sks" readonly>
                                    <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">ID DOSEN</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="iddosen" name="iddosen" readonly>
                                    <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">ID. Ruang</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="idruang" name="idruang" placeholder="ID Ruang" required>
                            <button type="button" class="btn btn-outline-primary" id="searchButton">
                               <i class="fas fa-search" data-feather="search"></i>
                            </button>
                            <ul id="resultsList" style="display: none;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Jam Masuk</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="jammasuk" name="jammasuk"  readonly>
                                    <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                </div>
                            </div>
                        </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">Jam Keluar</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="jamkeluar" name="jamkeluar" readonly>
                                         <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">No Silabus</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="silabus" name="silabus" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">ID Pengajar</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="idpengajar" name="idpengajar" placeholder="ID Pengajar" required>
                                        <button type="button" class="btn btn-outline-primary" id="searchButton2">
                                           <i class="fas fa-search" data-feather="search"></i>
                                        </button>
                                        <ul id="resultsList" style="display: none;"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nama" name="nama"  readonly>
                                        <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                    </div>
                                </div>
                            </div>
                       
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">Honor</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="honor" name="honor" readonly>
                                        <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">SK 2</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sk2" name="sk2" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">ID Dosen 2</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="dosen" name="dosen" placeholder="ID Dosen 2" >
                                        <button type="button" class="btn btn-outline-primary" id="searchButton3">
                                           <i class="fas fa-search" data-feather="search"></i>
                                        </button>
                                        <ul id="resultsList" style="display: none;"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nama1" name="nama1" readonly>
                                        <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">SK 3</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="sk3" name="sk3" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">ID Dosen 3</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="dosen1" name="dosen1" placeholder="ID Dosen 3" >
                                            <button type="button" class="btn btn-outline-primary" id="searchButton4">
                                               <i class="fas fa-search" data-feather="search"></i>
                                            </button>
                                            <ul id="resultsList" style="display: none;"></ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nama2" name="nama2" readonly>
                                            <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                        </div>
                                        </div>
                                    </div> 
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-3 col-form-label">SK 4</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sk4" name="sk4" >
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-3 col-form-label">Gabungan</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select class="form-select" id="gabungan" name="gabungan" aria-label="Default select example" >
                                                    <option value="" disabled selected>...</option>
                                                    <option value="Y">Ya</option>
                                                    <option value="T">Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-3 col-form-label">Kelas Gabungan</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="kelasgabungan" name="kelasgabungan" placeholder="Kelas Gabungan" >
                                                <button type="button" class="btn btn-outline-primary" id="searchButton5">
                                                   <i class="fas fa-search" data-feather="search"></i>
                                                </button>
                                                <ul id="resultsList" style="display: none;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-3 col-form-label">Gabungan Prodi</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select class="form-select" id="gabunganprodi" name="gabunganprodi" aria-label="Default select example" >
                                                    <option value="" disabled selected>...</option>
                                                    <option value="R">Ya</option>
                                                    <option value="£">Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-3 col-form-label">Prodi Gabungan</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="prodigabungan" name="prodigabungan" placeholder="Kelas Gabungan" >
                                                <button type="button" class="btn btn-outline-primary" id="searchButton6">
                                                   <i class="fas fa-search" data-feather="search"></i>
                                                </button>
                                                <ul id="resultsList" style="display: none;"></ul>
                                            </div>
                                        </div>
                                    </div>
                             </form>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="simpanData">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Second Modal for showing the query results -->
    <div class="modal fade" id="ruangModal" tabindex="-1" aria-labelledby="ruangModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ruangModalLabel">Pilih Ruang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox" class="form-control mb-3" placeholder="Search by ID Ruang or Jam Masuk...">
                    <table class="table table-bordered" id="ruangTable">
                        <thead>
                            <tr>
                                <th>ID Ruang</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Third Modal for showing the query results -->
    <div class="modal fade" id="idmkModal" tabindex="-1" aria-labelledby="idmkModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idmkModalLabel">Pilih Mata Kuliah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox1" class="form-control mb-3" placeholder="Search by Mata Kuliah...">
                    <table class="table table-bordered" id="idmkTable">
                        <thead>
                            <tr>
                                <th>IDMK</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>ID Dosen</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fourth Modal for showing the query results -->
    <div class="modal fade" id="honorModal" tabindex="-1" aria-labelledby="honorModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="honorModalLabel">Pilih Dosen Pengajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox2" class="form-control mb-3" placeholder="Search by Nama...">
                    <table class="table table-bordered" id="honorTable">
                        <thead>
                            <tr>
                                <th>ID Dosen</th>
                                <th>Nama</th>
                                <th>Honor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fifth Modal for showing the query results -->
    <div class="modal fade" id="dosenModal" tabindex="-1" aria-labelledby="dosenModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dosenModalLabel">Pilih Dosen Pengajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox3" class="form-control mb-3" placeholder="Search by Nama...">
                    <table class="table table-bordered" id="dosenTable">
                        <thead>
                            <tr>
                                <th>ID Dosen</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      <!-- sixth Modal for showing the query results -->
    <div class="modal fade" id="dosen1Modal" tabindex="-1" aria-labelledby="dosen1ModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dosen1ModalLabel">Pilih Dosen Pengajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox4" class="form-control mb-3" placeholder="Search by Nama...">
                    <table class="table table-bordered" id="dosen1Table">
                        <thead>
                            <tr>
                                <th>ID Dosen</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      <!-- eight Modal for showing the query results -->
    <div class="modal fade" id="kelasgabunganModal" tabindex="-1" aria-labelledby="kelasgabunganModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kelasgabunganModalLabel">Pilih Dosen Pengajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox5" class="form-control mb-3" placeholder="Search by Nama...">
                    <table class="table table-bordered" id="kelasgabunganTable">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      <!-- ninth Modal for showing the query results -->
    <div class="modal fade" id="prodigabunganModal" tabindex="-1" aria-labelledby="prodigabunganModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prodigabunganModalLabel">Pilih Dosen Pengajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox6" class="form-control mb-3" placeholder="Search by Nama...">
                    <table class="table table-bordered" id="prodigabunganTable">
                        <thead>
                            <tr>
                                <th>Prodi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kurikulum1Modal" tabindex="-1" aria-labelledby="kurikulum1ModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kurikulum1ModalLabel">Kurikulum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox7" class="form-control mb-3" placeholder="Search by Kurikulum...">
                    <table class="table table-bordered" id="kurikulum1Table">
                        <thead>
                            <tr>
                                <th>Kurikulum</th>
                                <th>Tahun Ajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      <!-- eleventh Modal for showing the query results -->
      <div class="modal fade" id="kelas1Modal" tabindex="-1" aria-labelledby="kelas1ModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kelas1ModalLabel">KELAS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox8" class="form-control mb-3" placeholder="Search by kelas...">
                    <table class="table table-bordered" id="kelas1Table">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      <!-- Main Form-->
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="row g-3" action="#" method="POST">
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
                    <button type="button" class="btn btn-primary mb-3" onclick="validateAllByDay()">Validasi Semua Data Hari Ini</button>
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
    padding: 10px;
    cursor: pointer;
}

.tab-item.active {
    background-color: #007bff;
    color: white;
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
    height: 900px; /* Ganti nilai ini sesuai dengan kebutuhan Anda */
    overflow-y: auto; /* Mengaktifkan overflow secara vertikal agar tabel dapat di-scroll jika kontennya lebih panjang */
}
.custom-modal-dialog {
    max-width: 70%;
}
.custom-modal-dialog {
    max-width: 900px; /* Ubah menjadi lebar tetap dalam piksel */
}

.modal-content {
    padding: 20px; /* Menyesuaikan padding di dalam modal */
}

 </style>
<!-- Your custom JavaScript to load and display PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Add these lines to the head section of your HTML document -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk QRCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
        function updateFakultas() {
        var selectedProdi = $('#prodi').val();
                
        if (selectedProdi) {
            $.ajax({
                url: '{{ route("fetchFakultas") }}',
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
        $("#lokasi").val(lokasi);
    });
    $('#prodi').change(function () {
        updateFakultas();
    })   
});              
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
    
    // Ambil nomor hari dari tabName
    var hariMap = {
        '1': 'Senin',
        '2': 'Selasa',
        '3': 'Rabu',
        '4': 'Kamis',
        '5': 'Jumat',
        '6': 'Sabtu'
    };

    var hari = hariMap[tabName] || 'Unknown';
    var harijadwal = tabName;
    
    // Simpan nilai hari dan harijadwal dalam elemen tersembunyi atau variabel global
    $('#hari').val(hari);
    $('#harijadwal').val(harijadwal);
    
    var idkampus = $('#idkampus').val();
    var prodi = $('#prodi').val();
    var idfakultas = $('#id_fakultas').val();
    var ta = $('#ta').val();
    var semester = $('#semester').val();
    // Menghapus kelas 'active' dari semua tab-item
    tablinks = document.getElementsByClassName("tab-item");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    
    // Menambahkan kelas 'active' ke tab yang ditekan
    evt.currentTarget.classList.add("active");
    $.ajax({
        url: '{{ route("fetchJadwal") }}',
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
            tableContent += '<th></th>';
            tableContent += '<th>Kelas</th>';
            tableContent += '<th>Kurikulum</th>';
            tableContent += '<th>IDMK</th>';
            tableContent += '<th>Matakuliah</th>';
            tableContent += '<th>SKS</th>';
            tableContent += '<th>ID Ruang</th>';
            tableContent += '<th>Jam Masuk</th>';
            tableContent += '<th>Jam Keluar</th>';
            tableContent += '<th>No Silabus</th>';
            tableContent += '<th>ID DOSEN</th>';
            tableContent += '<th>NAMA</th>';
            tableContent += '<th>KETERANGAN</th>';
            tableContent += '<th>Honor SKS</th>';
            tableContent += '<th>ID DOSEN PENGAJAR</th>';
            tableContent += '<th>NAMA</th>';
            tableContent += '<th>SK</th>';
            tableContent += '<th>ID DOSEN 2</th>';
            tableContent += '<th>NAMA</th>';
            tableContent += '<th>ID DOSEN 3</th>';
            tableContent += '<th>NAMA DOSEN 3</th>';
            tableContent += '<th>Validasi</th>'; // Kolom untuk tombol validasi
            tableContent += '</tr></thead>';
            tableContent += '<tbody>';

            response.forEach(function(row) {
                tableContent += '<tr>';
                tableContent += '<td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(' + row.idprimary + ')">Delete</button></td>';
                tableContent += '<td>' + row.kelas + '</td>';
                tableContent += '<td>' + row.kurikulum + '</td>';
                tableContent += '<td>' + row.idmk + '</td>';
                tableContent += '<td>' + row.matakuliah + '</td>';
                tableContent += '<td>' + row.sks + '</td>';
                tableContent += '<td>' + row.idruang + '</td>';
                tableContent += '<td>' + row.jammasuk + '</td>';
                tableContent += '<td>' + row.jamkeluar + '</td>';
                tableContent += '<td>' + row.nosilabus + '</td>';
                tableContent += '<td>' + row.iddosen + '</td>';
                tableContent += '<td>' + row.nama + '</td>';
                tableContent += '<td>' + row.Keterangan + '</td>';
                tableContent += '<td>' + parseFloat(row.HonorSKS).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td>';

                tableContent += '<td>' + (row.iddosen2 || '-') + '</td>';
                tableContent += '<td>' + (row.nama_dosen2 || '-') + '</td>';
                tableContent += '<td>' + row.SK2 + '</td>';
                tableContent += '<td>' + (row.iddosen3 || '-') + '</td>';
                tableContent += '<td>' + (row.nama_dosen3 || '-') + '</td>';
                tableContent += '<td>' + (row.iddosen4 || '-') + '</td>';
                tableContent += '<td>' + (row.nama_dosen4 || '-') + '</td>';

                // Tambahkan tombol validasi
                tableContent += '<td><button type="button" class="btn btn-success btn-sm" onclick="validateRow(' + row.idprimary + ')">Validasi</button></td>';

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
function validateAllByDay() {
    var harijadwal = $('#harijadwal').val(); // Ambil nilai hari
    var idkampus = $('#idkampus').val();
    var prodi = $('#prodi').val();
    var idfakultas = $('#id_fakultas').val();
    var ta = $('#ta').val();
    var semester = $('#semester').val();
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Semua data untuk hari ini akan divalidasi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, validasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("validateAllByDay") }}',
                method: 'POST',
                data: {
                    harijadwal: harijadwal,
                    idkampus: idkampus,
                    prodi: prodi,
                    idfakultas: idfakultas,
                    ta: ta,
                    semester: semester,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Tervalidasi!',
                            response.message,
                            'success'
                        );
                        // Refresh data di tab yang relevan jika perlu
                        openTab(event, harijadwal);
                    } else {
                        Swal.fire(
                            'Gagal!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat memvalidasi data.',
                        'error'
                    );
                }
            });
        }
    });
}

// Function to handle row validation
function validateRow(idprimary) {
    // Tampilkan SweetAlert untuk konfirmasi validasi
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data akan divalidasi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, validasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Lakukan validasi di sini, misalnya dengan AJAX
            $.ajax({
                url: '{{ route("validateJadwal") }}',
                method: 'POST',
                data: { idprimary: idprimary,
                    _token: $('meta[name="csrf-token"]').attr('content')
                 },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Tervalidasi!',
                            'Data telah divalidasi.',
                            'success'
                        );
                        // Tambahkan logika untuk memperbarui tampilan jika perlu
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat memvalidasi data.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat memvalidasi data.',
                        'error'
                    );
                }
            });
        }
    });
}
function deleteRow(idprimary) {
    // Tampilkan SweetAlert untuk konfirmasi penghapusan
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data akan dihapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("deleteJadwal") }}',
                method: 'POST',
                data: {
                    idprimary: idprimary,
                    _token: '{{ csrf_token() }}' // Pastikan CSRF token disertakan
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Terhapus!',
                            response.message,
                            'success'
                        );
                        // Hapus baris dari tabel atau lakukan tindakan lain
                        $('tr[data-id="'+idprimary+'"]').remove();
                    } else {
                        Swal.fire(
                            'Gagal!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 403) {
                        Swal.fire(
                            'Gagal!',
                            'Jadwal yang sudah divalidasi tidak dapat dihapus.',
                            'error'
                        );
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                }
            });
        }
    });
}

    $(document).on('click', '#tambahButton', function () {
        $('#tambahModal').modal('show'); // Menampilkan modal saat tombol tambah ditekan
    });
 

$(document).ready(function() {
    $('#searchButton1').on('click', function() {
        var ta = $('#ta').val(); // Get the academic year value
        var semester = $('#semester').val(); // Get the semester value
        var prodi = $('#prodi').val(); // Get the program study value

        console.log('semester:', semester);
        console.log('ta:', ta);
        console.log('prodi:', prodi);
        
        $.ajax({
            url: '{{ route("getIDMK") }}',
            method: 'POST',
            data: {
                ta: ta,
                semester: semester,
                prodi: prodi,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#idmkTable tbody');
                tbody.empty();

                $.each(response, function(index, item) {
                    var row = '<tr data-idmk="' + item.idmk + '" data-matakuliah="' + item.matakuliah + '" data-sks="' + item.sks + '" data-iddosen="' + item.iddosen + '">' +
                        '<td>' + item.idmk + '</td>' +
                        '<td>' + item.matakuliah + '</td>' +
                        '<td>' + item.sks + '</td>' +
                        '<td>' + item.iddosen + '</td>' +
                        '<td>' + item.nama + '</td>' +
                        '</tr>';
                    tbody.append(row);
                });

                $('#idmkModal').modal('show');
            }
        });
    });

    $('#idmkTable').on('click', 'tr', function() {
        var idmk = $(this).data('idmk');
        var matakuliah = $(this).data('matakuliah');
        var sks = $(this).data('sks');
        var iddosen = $(this).data('iddosen');

        $('#idmk').val(idmk);
        $('#matakuliah').val(matakuliah);
        $('#sks').val(sks);
        $('#iddosen').val(iddosen);

        $('#idmkModal').modal('hide');
    });

    $('#searchBox1').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#idmkTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});


    $(document).ready(function() {
    // Event listener for search button click
    $('#searchButton').click(function() {
        var sks = $('#sks').val();
        $.ajax({
            url: '{{ route("getRuang") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sks: sks
            },
            success: function(response) {
                var tableBody = $('#ruangTable tbody');
                tableBody.empty();
                response.forEach(function(ruang) {
                    tableBody.append(
                        '<tr data-idruang="' + ruang.idruang + '" data-jammasuk="' + ruang.jammasuk + '" data-jamkeluar="' + ruang.jamkeluar + '">' +
                        '<td>' + ruang.idruang + '</td>' +
                        '<td>' + ruang.jammasuk + '</td>' +
                        '<td>' + ruang.jamkeluar + '</td>' +
                        '</tr>'
                    );
                });
                $('#ruangModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Event listener for table row click
    $('#ruangTable tbody').on('click', 'tr', function() {
        var idruang = $(this).data('idruang');
        var jammasuk = $(this).data('jammasuk');
        var jamkeluar = $(this).data('jamkeluar');
        
        $('#idruang').val(idruang);
        $('#jammasuk').val(jammasuk);
        $('#jamkeluar').val(jamkeluar);
        $('#ruangModal').modal('hide');
    });

    // Event listener for search box input
    $('#searchBox').on('input', function() {
        var query = $(this).val().toLowerCase();
        $('#ruangTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1)
        });
    });
});
$(document).ready(function() {
    $('#searchButton2').on('click', function() {
        var idmk = $('#idmk').val();
        var prodi = $('#prodi').val();

        console.log('idmk:', idmk);
        console.log('prodi:', prodi);
        
        $.ajax({
            url: '{{ route("getHonor") }}',
            method: 'POST',
            data: {
                idmk: idmk,
                prodi: prodi,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#honorTable tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-idpengajar="' + item.idpengajar + '" data-nama="' + item.nama + '" data-honor="' + item.honor + '">' +
                        '<td>' + item.idpengajar + '</td>' +
                        '<td>' + item.nama + '</td>' +
                        '<td>' + item.honor + '</td>' +
                        '</tr>'
                    );
                });

                $('#honorModal').modal('show');
            }
        });
    });

    $('#honorTable').on('click', 'tr', function() {
        var idpengajar = $(this).data('idpengajar');
        var nama = $(this).data('nama');
        var honor = $(this).data('honor');

        $('#idpengajar').val(idpengajar);
        $('#nama').val(nama);
        $('#honor').val(honor);

        $('#honorModal').modal('hide');
    });

    $('#searchBox2').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#honorTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
$(document).ready(function() {
    $('#searchButton3').on('click', function() {
        var idmk = $('#idmk').val();
        var prodi = $('#prodi').val();

        console.log('idmk:', idmk);
        console.log('prodi:', prodi);
        
        $.ajax({
            url: '{{ route("getDosen2") }}',
            method: 'POST',
            data: {
                idmk: idmk,
                prodi: prodi,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#dosenTable tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-dosen="' + item.dosen + '" data-nama1="' + item.nama1 + '">' +
                        '<td>' + item.dosen + '</td>' +
                        '<td>' + item.nama1 + '</td>' +
                        '</tr>'
                    );
                });

                $('#dosenModal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $('#dosenTable').on('click', 'tr', function() {
        var dosen = $(this).data('dosen');
        var nama1 = $(this).data('nama1');

        $('#dosen').val(dosen);
        $('#nama1').val(nama1);

        $('#dosenModal').modal('hide');
    });

    $('#searchBox3').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#dosenTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
$(document).ready(function() {
    $('#searchButton4').on('click', function() {
        var idmk = $('#idmk').val();
        var prodi = $('#prodi').val();

        console.log('idmk:', idmk);
        console.log('prodi:', prodi);
        
        $.ajax({
            url: '{{ route("getDosen3") }}',
            method: 'POST',
            data: {
                idmk: idmk,
                prodi: prodi,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#dosen1Table tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-dosen1="' + item.dosen1 + '" data-nama2="' + item.nama2 + '">' +
                        '<td>' + item.dosen1 + '</td>' +
                        '<td>' + item.nama2 + '</td>' +
                        '</tr>'
                    );
                });

                $('#dosen1Modal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $('#dosen1Table').on('click', 'tr', function() {
        var dosen1 = $(this).data('dosen1');
        var nama2 = $(this).data('nama2');

        $('#dosen1').val(dosen1);
        $('#nama2').val(nama2);

        $('#dosen1Modal').modal('hide');
    });

    $('#searchBox4').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#dosen1Table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
$(document).ready(function() {
    $('#searchButton5').on('click', function() {
        var idkampus = $('#idkampus').val();
        
        $.ajax({
            url: '{{ route("getGabungan") }}',
            method: 'POST',
            data: {
                idkampus: idkampus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#kelasgabunganTable tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-kelasgabungan="' + item.kelasgabungan + '">' +
                        '<td>' + item.kelasgabungan + '</td>' +
                        '</tr>'
                    );
                });

                $('#kelasgabunganModal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $('#kelasgabunganTable').on('click', 'tr', function() {
        var kelasgabungan = $(this).data('kelasgabungan');

        $('#kelasgabungan').val(kelasgabungan);

        $('#kelasgabunganModal').modal('hide');
    });

    $('#searchBox5').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#kelasgabunganTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
$(document).ready(function() {
    $('#searchButton6').on('click', function() {
        
        $.ajax({
            url: '{{ route("getProdiGabungan") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#prodigabunganTable tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-prodigabungan="' + item.prodigabungan + '">' +
                        '<td>' + item.prodigabungan + '</td>' +
                        '</tr>'
                    );
                });

                $('#prodigabunganModal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $('#prodigabunganTable').on('click', 'tr', function() {
        var prodigabungan = $(this).data('prodigabungan');

        $('#prodigabungan').val(prodigabungan);

        $('#prodigabunganModal').modal('hide');
    });

    $('#searchBox6').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#prodigabunganTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
$(document).ready(function() {
            $('#searchButton7').on('click', function() {
                $.ajax({
                    url: '{{ route("getKurikulum") }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        var tbody = $('#kurikulum1Table tbody');
                        tbody.empty();

                        response.forEach(function(item) {
                            tbody.append(
                                '<tr data-kurikulum1="' + item.kurikulum1 + '" data-tahunajaran="' + item.tahunajaran1 + '">' +
                                '<td>' + item.kurikulum1 + '</td>' +
                                '<td>' + item.tahunajaran1 + '</td>' +
                                '</tr>'
                            );
                        });

                        $('#kurikulum1Modal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });

            $('#kurikulum1Table').on('click', 'tr', function() {
                var kurikulum1 = $(this).data('kurikulum1');
                console.log('Selected kurikulum1:', kurikulum1);
                $('#kurikulum1').val(kurikulum1);
                $('#kurikulum1Modal').modal('hide');
            });

            $('#kurikulum1Modal').on('hidden.bs.modal', function() {
                console.log('kurikulum1Modal hidden');
                $('#tambahModal').modal('show');
                console.log('tambahModal shown');
            });

            $('#searchBox7').on('input', function() {
                var value = $(this).val().toLowerCase();
                $('#kurikulum1Table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
        $(document).ready(function() {
    $('#searchButton8').on('click', function() {
        var idkampus = $('#idkampus').val();
        console.log('Selected idkampus:', idkampus);
        $.ajax({
            url: '{{ route("getKelas") }}',
            method: 'POST',
            data: {
                idkampus: idkampus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#kelas1Table tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-kelas1="' + item.kelas1 + '">' +
                        '<td>' + item.kelas1 + '</td>' +
                        '</tr>'
                    );
                });

                $('#kelas1Modal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $('#kelas1Table').on('click', 'tr', function() {
        var kelas1 = $(this).data('kelas1');

        $('#kelas1').val(kelas1);

        $('#kelas1Modal').modal('hide');
    });

    $('#searchBox8').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#kelas1Table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
$(document).ready(function() {
    $('#simpanData').on('click', function() {
        var formData = {
            hari: $('#hari').val(),
            harijadwal: $('#harijadwal').val(),
            idkampus: $('#idkampus').val(),
            prodi: $('#prodi').val(),
            idfakultas: $('#id_fakultas').val(),
            ta: $('#ta').val(),
            semester: $('#semester').val(),
            kelas: $('#kelas1').val(),
            kurikulum: $('#kurikulum1').val(),
            idmk: $('#idmk').val(),
            matakuliah: $('#matakuliah').val(),
            sks: $('#sks').val(),
            iddosen: $('#iddosen').val(),
            keterangan: $('#keterangan').val(),
            idruang: $('#idruang').val(),
            jammasuk: $('#jammasuk').val(),
            jamkeluar: $('#jamkeluar').val(),
            silabus: $('#silabus').val(),
            idpengajar: $('#idpengajar').val(),
            nama: $('#nama').val(),
            honor: $('#honor').val(),
            sk2: $('#sk2').val(),
            dosen: $('#dosen').val(),
            nama1: $('#nama1').val(),
            sk3: $('#sk3').val(),
            dosen1: $('#dosen1').val(),
            nama2: $('#nama2').val(),
            sk4: $('#sk4').val(),
            gabungan: $('#gabungan').val(),
            kelasgabungan: $('#kelasgabungan').val(),
            gabunganprodi: $('#gabunganprodi').val(),
            prodigabungan: $('#prodigabungan').val(),
            _token: '{{ csrf_token() }}' // Pastikan Anda menambahkan token CSRF
        };

        // Kirim data menggunakan AJAX
        $.ajax({
            url: '{{ route("simpan.data") }}', // Ganti dengan URL endpoint penyimpanan Anda
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'error') {
                    if (response.message === 'Dosen sudah memiliki kelas pada jam yang sama') {
                        // Tampilkan SweetAlert jika ada bentrok
                        let message = 'Dosen sudah memiliki kelas pada jam yang sama:\n\n';
                        response.data.forEach(function(item) {
                            // Gunakan item.hari untuk menampilkan nama hari
                            message += `Hari: ${item.hari}, Kelas: ${item.kelas}, IDMK: ${item.idmk}, Jam: ${item.jammasuk} - ${item.jamkeluar}\n`;
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Jadwal Bentrok',
                            text: message,
                            confirmButtonText: 'OK'
                        });
                    } else if (response.message === 'Dosen sudah memiliki jadwal mengajar untuk mata kuliah ini') {
                        // Tampilkan SweetAlert jika dosen sudah mengajar idmk yang sama
                        let detailsMessage = 'Dosen sudah memiliki jadwal mengajar untuk mata kuliah ' + response.matakuliah + ' pada semester dan tahun ajaran yang sama:\n\n';
                        response.details.forEach(function(item) {
                            detailsMessage += `Hari: ${item.harijadwal}, Kelas: ${item.kelas}, Jam: ${item.jammasuk} - ${item.jamkeluar}\n`;
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Jadwal Mengajar Sama',
                            text: detailsMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                } else if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Berhasil Disimpan',
                        text: 'Jadwal berhasil disimpan.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

</script>

@endsection
