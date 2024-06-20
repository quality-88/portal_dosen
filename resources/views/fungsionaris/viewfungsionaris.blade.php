@extends('admin.dashboard')
@section('admin')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('formKaprodi') }}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Fungsionaris</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        @if(count($hakAkses) > 0)
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IDDOSEN</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Fakultas</th>
                                    <th>Jabatan</th>
                                    <th>Periode Awal</th>
                                    <th>Periode Akhir</th>
                                    <th>Edit</th> <!-- Tambah kolom edit di sini -->
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach ($hakAkses as $result)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $result->id_dosen_pejabat }}</td>
                                    <td>{{ $result->nama_gelar }}</td>
                                    <td>{{ $result->prodi }}</td>
                                    <td>{{ $result->fakultas }}</td>
                                    <td>{{ $result->jabatan }}</td>
                                    <td>{{ $result->priode_awal }}</td>
                                    <td>{{ $result->priode_akhir }}</td>
                                    <td>
                                        <!-- Tambahkan tombol edit di sini -->
                                        <a href="#" class="btn btn-primary">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Your custom JavaScript to load and display PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<!-- Add these lines to the head section of your HTML document -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
</script>
@endsection
