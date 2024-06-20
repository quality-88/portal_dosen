@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Jumlah Mahasiswa /Prodi</h4>
                    <form class="row g-5" action="{{ route('viewjlhMahasiswaProdi') }}" method="POST">
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
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose Prodi...</option>
                                @foreach($prodis as $pilihanProdi)
                                    <option value="{{ $pilihanProdi->prodi }}" {{ old('prodi', isset($prodi) ? $prodi : '') == $pilihanProdi->prodi ? 'selected' : '' }}>{{ $pilihanProdi->prodi }}</option>
                                @endforeach
                            </select>                            
                        </div>
                        <div class="col-md-3">
                            <label for="ta_mulai" class="form-label">Dari TA</label>
                            <input type="text" class="form-control" id="ta_mulai" name="ta_mulai" value="{{ old('ta_mulai', isset($ta_start) ? $ta_start : '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="ta_akhir" class="form-label">Sampai TA</label>
                            <input type="text" class="form-control" id="ta_akhir" name="ta_akhir" value="{{ old('ta_akhir', isset($ta_end) ? $ta_end : '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($data))
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>TA</th>
                                    <th>Jumlah Pendaftar</th>
                                    <th>Jumlah Lulus Seleksi</th>
                                    <th>Jumlah Mahasiswa Regular</th>
                                    <th>Total Mahasiswa Keseluruhan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Sort the data by TA in ascending order
                                    $sortedData = $data['result']->sortBy('ta');
                                @endphp
                                @foreach($sortedData as $index => $result)
                                <tr>
                                    <td>{{ $result->ta }}</td>
                                    <td>{{ $result->jumlah }}</td>
                                    <td>{{ $data['result1'][$index]->jumlah ?? 0 }}</td>
                                    <td>{{ $data['result2'][$index]->jumlah ?? 0 }}</td>
                                    <td>{{ $data['result3'][$index]->jumlah ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-lg float-end" onclick="downloadPDF()">Download PDF</button>
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
    window.jsPDF = window.jspdf.jsPDF;
    
    function downloadPDF() {
        var doc = new jsPDF('l', 'pt', 'a4');
        doc.setFontSize(15);
        var ta_start = document.getElementById('ta_mulai').value;
        var ta_end = document.getElementById('ta_akhir').value;
        const prodi = '{{ session('prodi') }}';
        const universitas = '{{ session('universitas') }}';
        doc.setFontSize(20);
        var lokasiText = universitas === 'UQM' ? 'Medan' : (universitas === 'UQB' ? 'Berastagi' : '');
        doc.text(`Universitas Quality : ${lokasiText}`, 20, 40);
        doc.text('Data Unit Pengelola Program Studi', 20, 80);
        doc.setFontSize(10);
        doc.text(`TA: ${ta_start} - ${ta_end}`, 20, 120);
    
        var headers = ['Tahun Akademik', 'Jumlah Calon Pendaftar', 'Jumlah Pendaftar Lulus Seleksi', 'Jumlah Mahasiswa Reguler', 'Jumlah Mahasiswa Keseluruhan'];
        var data = [];
    
        @if(isset($data) && count($data['result']) > 0)
            @foreach ($data['result'] as $index => $result)
                data.push([
                    "{{ $result->ta }}",
                    "{{ $result->jumlah }}",
                    "{{ $data['result1'][$index]->jumlah ?? 0 }}",
                    "{{ $data['result2'][$index]->jumlah ?? 0 }}",
                    "{{ $data['result3'][$index]->jumlah ?? 0 }}"
                ]);
            @endforeach
        @endif
    
        var startY = 160;
        doc.autoTable({
            head: [headers],
            body: data,
            startY: startY
        });
    
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);
    
        const fileName = `Jumlah Mahasiswa Reguler_${universitas}_TA${ta_start}-${ta_end}_${formattedDate}.pdf`;
        doc.save(fileName);
    }
    
    function downloadExcel() {
        @if(isset($universitas) && isset($ta_start) && isset($ta_end))
        var universitas = "{{ $universitas }}";
        var ta_start = "{{ $ta_start }}";
        var ta_end = "{{ $ta_end }}";
    @else
        var universitas = "Unknown"; // Provide a default value if $universitas is not set
        var ta_start = "Unknown"; // Provide a default value if $ta_start is not set
        var ta_end = "Unknown"; // Provide a default value if $ta_end is not set
    @endif
    
        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "Jumlah Mahasiswa Reguler",
            Subject: "Data Mahasiswa",
            Author: "Universitas Quality",
            CreatedDate: new Date()
        };
        wb.SheetNames.push("Data");
    
        var ws_data = [['Tahun Akademik', 'Jumlah Calon Pendaftar', 'Jumlah Pendaftar Lulus Seleksi', 'Jumlah Mahasiswa Reguler', 'Jumlah Mahasiswa Keseluruhan']];
        
        @if(isset($data) && count($data['result']) > 0)
            @foreach ($data['result'] as $index => $result)
                ws_data.push([
                    "{{ $result->ta }}",
                    "{{ $result->jumlah }}",
                    "{{ $data['result1'][$index]->jumlah ?? 0 }}",
                    "{{ $data['result2'][$index]->jumlah ?? 0 }}",
                    "{{ $data['result3'][$index]->jumlah ?? 0 }}"
                ]);
            @endforeach
        @endif
    
        var ws = XLSX.utils.aoa_to_sheet(ws_data);
        wb.Sheets["Data"] = ws;
    
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const fileName = `Jumlah Mahasiswa Reguler_${universitas}_TA${ta_start}-${ta_end}_${formattedDate}.xlsx`;
    
        XLSX.writeFile(wb, fileName);
    }
    </script>
    
@endsection