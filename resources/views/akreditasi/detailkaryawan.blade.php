
@extends('admin.dashboard')
@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('form.karyawan') }}">Form</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"> Mahasiswa Karyawan Aktif </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Detail Mahasiswa Karyawan Aktif</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>HP</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                        
                                @foreach($result as $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data->npm }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->hp }}</td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      
                </div>
            </div>
        </div>
        
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
<!-- Include toastr CSS and JS files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection