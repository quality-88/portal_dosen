@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Data Calon Mahasiswa S2</h4>
                    <form class="row g-5" action="#" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ta" class="form-label"> TA</label>
                            <input type="number" class="form-control" id="ta" name="ta" value="{{ old('ta', $ta ?? '') }}" required min="2024">
                        </div>
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($results))
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4"> Results</h4>
                        <button class="btn btn-primary mb-5 " onclick="downloadPDF()">Download PDF</button>
                        <button class="btn btn-primary mb-5" onclick="downloadExcel()">Download Excel</button>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th> NO </th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>HP</th>
                                    <th>Alamat Asal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                            @endphp
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $result->nama }}</td>
                                    <td>{{ $result->emailregis }}</td>
                                    <td>{{ $result->hp }}</td>
                                    <td>{{ $result->alamatasal }}</td>
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
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script>
    @if(isset($results))
    window.jsPDF = window.jspdf.jsPDF;
    
    function downloadPDF() {
        var doc = new jsPDF('l', 'pt', 'a4');
        doc.setFontSize(15);
        
        var ta = document.getElementById('ta').value;
       
        var ta = `${ta}`;
    
        var universitas = document.getElementById('universitas').value;
        var lokasiText = universitas === 'UQM' ? 'Medan' : (universitas === 'UQB' ? 'Berastagi' : '');
    
        doc.setFontSize(20);
        doc.text(`Universitas Quality : ${lokasiText}`, 300, 20);
        doc.text('Data Calon Mahasiswa S2', 300, 50);
        doc.setFontSize(15);
        doc.text(`Tahun Ajaran: ${ta}`, 340, 80);
        $no = 1;
        var data = [];
        var headers = ['No', 'Nama', 'Email', 'HP', 'Alamat Asal'];
        @foreach($results as $result)
        var row = [
            $no++,
            '{{ $result->nama }}',
            '{{ $result->emailregis }}',
            '{{ $result->hp }}',
            '{{ $result->alamatasal }}',
        ];
        data.push(row);
        @endforeach
  
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
        const fileName = `Daftar Calon Mahasiswa S2_${universitas}_${ta}_${formattedDate}.pdf`;
        doc.save(fileName);
    }
    
    function downloadExcel() {
        var data = [];
        var headers = ['No', 'Nama', 'Email', 'HP', 'Alamat Asal'];
        $no = 1;
        @foreach($results as $result)
        var row = [
            $no++,
            '{{ $result->nama }}',
            '{{ $result->emailregis }}',
            '{{ $result->hp }}',
            '{{ $result->alamatasal }}',
        ];
        data.push(row);
        @endforeach

        var ws = XLSX.utils.aoa_to_sheet([headers].concat(data));
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Data Calon Mahasiswa S2");
    
        var ta = document.getElementById('ta').value;
       
        var universitas = document.getElementById('universitas').value;
        var fileName = `Data Calon Mahasiswa S2-${universitas}_${ta}.xlsx`;
    
        XLSX.writeFile(wb, fileName);
    }
    @endif
    </script>
@endsection
