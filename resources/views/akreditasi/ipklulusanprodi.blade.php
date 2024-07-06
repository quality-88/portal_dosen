@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">RINCIAN LULUSAN /PRODI</h4>
                    <form class="row g-5" action="{{ route('HitungIPK') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label for="ta" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta', isset($ta) ? $ta : session('ta')) }}" required>
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
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>NPM</th>
                                    <th>NAMA</th>
                                    <th>IPK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->NPM }}</td>
                                    <td>{{ $result->Nama }}</td>
                                    <td>{{ number_format($result->IPK, 2) }}</td> <!-- Formatting IPK to 2 decimal places -->
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
        <div class="col-md-12 grid-margin stretch-card">
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

<!-- Your custom JavaScript to load and display PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<script>
    window.jsPDF = window.jspdf.jsPDF;
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF('l', 'pt', 'a4');
        doc.setFontSize(15);
        
        var ta = document.getElementById('ta').value;
        const prodi = '{{ session('prodi') }}';
        const universitas = '{{ session('universitas') }}';
        var lokasiText = universitas === 'UQM' ? 'Medan' : (universitas === 'UQB' ? 'Berastagi' : '');
    
        doc.setFontSize(25);
        doc.text(`Universitas Quality : ${lokasiText}`, 250, 40);
        doc.text('Data IPK Lulusan', 310, 80);
        doc.setFontSize(15);
        doc.text(`Tahun Ajaran: ${ta}`, 320, 120);
        var results = [];
        var headers = ['NPM', 'Nama', 'IPK'];
        @if(isset($result) && count($results) > 0)
        @foreach($results as $result)
        var row = [
            '{{ $result->NPM }}',
            '{{ $result->Nama }}',
            '{{ number_format($result->IPK, 2) }}'
        ];
        results.push(row);
        @endforeach
    @endif
        var startY = 140;
        doc.autoTable({
            head: [headers],
            body: results,
            startY: startY
        });
        doc.setFontSize(10);
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);
        const fileName = `IPK LULUSAN /PRODI_${universitas}_${prodi}_${ta}_${formattedDate}.pdf`;
        doc.save(fileName);
}

function downloadExcel() {
    var results = [];
    var headers = ['NPM', 'Nama', 'IPK'];
    
    @if(isset($results) && count($results) > 0)
    @foreach($results as $result)
    var row = [
        '{{ $result->NPM }}',
        '{{ $result->Nama }}',
        '{{ number_format($result->IPK, 2) }}'
    ];
    results.push(row);
    @endforeach
    @endif
    
    // Mendapatkan nilai dari input
    var ta = document.getElementById('ta').value;
    var prodi = document.getElementById('prodi').value;
    var universitas = document.getElementById('universitas').value;
    
    // Membersihkan nilai prodi dari karakter yang tidak diinginkan
    prodi = prodi.replace(/[\\/:?*[\]]/g, ''); // Menghapus karakter yang tidak diizinkan
    
    var fileName = `IPK_Lulusan_${universitas}_${ta}-${prodi}.xlsx`; // Menggunakan nilai prodi yang telah dibersihkan
    
    var ws = XLSX.utils.aoa_to_sheet([headers].concat(results));
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "IPK Lulusan Prodi");
    XLSX.writeFile(wb, fileName); // Menulis file Excel
}

</script>

@endsection
