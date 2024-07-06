@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Rekap Dosen Aktif</h4>
                    <form class="row g-5" action="{{ route('detail.keuangan') }}" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="ta" class="form-label">Dari TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta_mulai', isset($ta) ? $ta : '') }}" required>
                        </div>
                      
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($dosen))
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
              
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID DOSEN</th>
                                    <th>Nama </th>
                                    <th> N.I.K </th>
                                    <th>BPJS Kesehatan </th>
                                    <th>BPJS Ketenakerjaan </th>
                                    <th> NPWP </th>
                                    <th> Kepangkatan </th>
                                    <th>Jabatan Akademik</th>
                                    <th>Status Dosen</th>
                                    <th>Prodi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dosen as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $d->iddosen }}</td>
                                    <td>{{ $d->nama}}</td>
                                    <td>{{ $d->NOKTP }}</td>
                                    <td>{{ $d->Kesehatan }}</td>
                                    <td>{{ $d->Ketenagakerjaan }}</td>
                                    <td>{{ $d->NPWP }}</td>
                                    <td>{{ $d->Kepangkatan }}</td>
                                    <td>{{ $d->jabatanakademik }}</td>
                                    <td>{{ $d->statusdosen }}</td>
                                    <td>{{ $d->PRODITERDAFTAR }}</td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        
                        <button class="btn btn-primary btn-lg float-end" style="margin-right: 10px;" onclick="downloadExcel()">
                            <i class="btn-icon-prepend" data-feather="printer"></i>Download Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script>
    @if(isset($dosen))
    function downloadExcel() {
        var data = [];
        var headers = ['No', 'ID Dosen', 'Nama', 'N.I.K', 'BPJS Kesehatan','BPJS Ketenagakerjaan','NPWP',
            'Kepangkatan','Jabatan Akademik','Status Dosen','Prodi'
        ];
        @foreach($dosen as $index => $d)
        var row = [
                '{{ $index + 1 }}',
                '{{ $d->iddosen }}',
                '{{ $d->nama}}',
                '{{ $d->NOKTP }}',
                '{{ $d->Kesehatan }}',
                '{{ $d->Ketenagakerjaan }}',
                '{{ $d->NPWP }}',
                '{{ $d->Kepangkatan }}',
                '{{ $d->jabatanakademik }}',
                '{{ $d->statusdosen }}',
                '{{ $d->PRODITERDAFTAR }}'
        ];
        data.push(row);
        @endforeach
        
        var ws = XLSX.utils.aoa_to_sheet([headers].concat(data));
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Detail Profile Dosen");
    
        var ta = document.getElementById('ta').value;
        
        var universitas = document.getElementById('universitas').value;
        var fileName = `Profile Dosen_${universitas}_${ta}.xlsx`;
    
        XLSX.writeFile(wb, fileName);
    }
    @endif
    </script>
@endsection
