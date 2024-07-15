@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">IPK Lulusan Reguler /Prodi</h4>
                    <form class="row g-5" action="{{ route('IPKLulusanPPRODIRegular') }}" method="POST">
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
                            <label for="ta_mulai" class="form-label">Dari Stambuk</label>
                            <input type="text" class="form-control" id="ta_mulai" name="ta_mulai" value="{{ old('ta_mulai', isset($ta_start) ? $ta_start : '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="ta_akhir" class="form-label">Sampai Stambuk</label>
                            <input type="text" class="form-control" id="ta_akhir" name="ta_akhir" value="{{ old('ta_akhir', isset($ta_end) ? $ta_end : '') }}" required>
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
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>Stambuk</th>
                                    <th>Jumlah Lulus</th>
                                    <th>IPK Minimum</th>
                                    <th>IPK Rata-Rata</th>
                                    <th>IPK Maksimum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['results'] as $key => $result)
                                <tr>
                                    <td>{{ $result->TA }}</td>
                                    <td>{{ $data['total'][$key]->jumlah }}</td>
                                    <td>{{ number_format($result->Minimum_IPK, 2) }}</td>
                                    <td>{{ number_format($result->Rata_rata_IPK, 2) }}</td>
                                    <td>{{ number_format($result->Maksimum_IPK, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    
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
        var ta = `${ta_start} - ${ta_end}`;
    
        var universitas = document.getElementById('universitas').value;
        var lokasiText = universitas === 'UQM' ? 'Medan' : (universitas === 'UQB' ? 'Berastagi' : '');
    
        doc.setFontSize(15);
        doc.text(`Universitas Quality : ${lokasiText}`, 10, 20);
        doc.text('Data IPK Lulusan', 10, 40);
        doc.setFontSize(10);
        doc.text(`Tahun Ajaran: ${ta}`, 10, 60);
    
        var data = [];
        var headers = ['Stambuk', 'Jumlah Lulus', 'IPK Minimum', 'IPK Rata-Rata', 'IPK Maksimum'];
        @if(isset($data) && count($data['results']) > 0)
        @foreach($data['results'] as $result)
        var row = [
            '{{ $result->TA }}',
            '{{ $data["total"][$loop->index]->jumlah }}',
            '{{ number_format($result->Minimum_IPK, 2) }}',
            '{{ number_format($result->Rata_rata_IPK, 2) }}',
            '{{ number_format($result->Maksimum_IPK, 2) }}'
        ];
        data.push(row);
        @endforeach
    @endif
        var startY = 100;
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
        const fileName = `IPK_Lulusan_${universitas}_${ta}_${formattedDate}.pdf`;
        doc.save(fileName);
    }
    
    function downloadExcel() {
        var data = [];
        var headers = ['Stambuk', 'Jumlah Lulus', 'IPK Minimum', 'IPK Rata-Rata', 'IPK Maksimum'];
        @if(isset($data) && count($data['results']) > 0)
        @foreach($data['results'] as $result)
        var row = [
            '{{ $result->TA }}',
            '{{ $data["total"][$loop->index]->jumlah }}',
            '{{ number_format($result->Minimum_IPK, 2) }}',
            '{{ number_format($result->Rata_rata_IPK, 2) }}',
            '{{ number_format($result->Maksimum_IPK, 2) }}'
        ];
        data.push(row);
        @endforeach
        @endif
        var ws = XLSX.utils.aoa_to_sheet([headers].concat(data));
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "IPK_Lulusan");
    
        var ta_start = document.getElementById('ta_mulai').value;
        var ta_end = document.getElementById('ta_akhir').value;
        var universitas = document.getElementById('universitas').value;
        var fileName = `IPK_Lulusan_${universitas}_${ta_start}-${ta_end}.xlsx`;
    
        XLSX.writeFile(wb, fileName);
    }
    </script>
@endsection
