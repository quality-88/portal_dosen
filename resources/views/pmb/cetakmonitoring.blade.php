@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Monitoring PMB</h4>
                    <form class="row g-5" action="{{ route('viewMonitoringPMB') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', $universitas ?? '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', $universitas ?? '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ta" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta', $ta ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggalakhir" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" data-date-format="Y-m-d" value="{{ old('endDate', $endDate ?? '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success mt-4">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(isset($data) && count($data['fakultasData']) > 0)
    
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Monitoring Results</h4>
                        <button class="btn btn-primary mb-4" onclick="downloadPDF()">Download PDF</button>
                        <div class="table-responsive">
                        <table class="table" id='pmbmonitoring'>
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Fakultas</th>
                                    <th rowspan="2">Prodi</th>
                                    <th colspan="3">DAFTAR</th>
                                    <th colspan="3">Daftar Ulang</th>
                                    <th colspan="3">Tidak Daftar Ulang</th>
                                </tr>
                                <tr>
                                    <th> ({{ date('d-m-Y', strtotime($endDate . ' -1 day')) }})</th>
                                    <th> ({{ date('d-m-Y', strtotime($endDate)) }})</th>
                                    <th>Jumlah</th>
                                    <th> ({{ date('d-m-Y', strtotime($endDate . ' -1 day')) }})</th>
                                    <th> ({{ date('d-m-Y', strtotime($endDate)) }})</th>
                                    <th>Jumlah</th>
                                    <th> ({{ date('d-m-Y', strtotime($endDate)) }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['fakultasData'] as $index => $fakultas)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $fakultas->fakultas }}</td>
                                    <td>{{ $fakultas->prodi }}</td>
                                    <td>{{ $data['daftarsebelumnya'][$fakultas->prodi] ?? 0 }}</td>
                                    <td>{{ $data['dafarhariini'][$fakultas->prodi] ?? 0 }}</td>
                                    <td>{{ $data['totalCounts'][$fakultas->prodi] ?? 0 }}</td>
                                    <td>{{ $data['daftarUlangsebelumnya'][$fakultas->prodi] ?? 0 }}</td>
                                    <td>{{ $data['dafarUlanghariini'][$fakultas->prodi] ?? 0 }}</td>
                                    <td>{{ $data['totalUlang'][$fakultas->prodi] ?? 0 }}</td>
                                    <td>{{ $data['totalTidak'][$fakultas->prodi] ?? 0 }}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <a href="{{ url()->previous() }}" class="btn btn-secondary mt-4">Back</a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Include jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Include jsPDF-AutoTable plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr('#endDate', {
            dateFormat: 'Y-m-d'
        });
    });
    window.jsPDF = window.jspdf.jsPDF;

    function downloadPDF() {
        @if(isset($data) && count($data['fakultasData']) > 0)
    var doc = new jsPDF('p', 'pt', 'a4');
    doc.setFontSize(15);

    var data = @json($data ?? null);
    var ta = "{{ $ta }}";
    var universitas = "{{ session('universitas') }}";
    var endDate = "{{ session('endDate') }}";
    var lokasiText = "{{ $universitas === 'UQM' ? 'MEDAN' : ($universitas === 'UQB' ? 'BERASTAGI' : '') }}";
    const currentDate = new Date();
    const formattedDate = currentDate.toLocaleDateString('en-US');
    const formattedTime = currentDate.toLocaleTimeString('en-US');
    const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
    doc.setFontSize(7);
    doc.text(printDateTime, 50, 90);
    // Menambahkan teks informasi ke dokumen PDF
    doc.setFontSize(25);
    doc.setTextColor(0, 0, 0);
    doc.text(`UNIVERSITAS QUALITY ${lokasiText}`, 130, 40);
    doc.text(`PMB Monitoring ${ta}`, 210, 80);
    // Menambahkan tanggal cetak di sini

    var tableData = [];

    // Header tambahan
    var additionalHeader = [
    { content: 'No', colSpan: 1, styles: { halign: 'center', valign: 'middle', fontStyle: 'bold',fontSize: 7 } },
    { content: 'Fakultas', colSpan: 1, styles: { halign: 'center', valign: 'middle', fontStyle: 'bold',fontSize: 7} },
    { content: 'Prodi', colSpan: 1, styles: { halign: 'center', valign: 'middle', fontStyle: 'bold',fontSize: 7 } },
    { content: 'Daftar', colSpan: 3, styles: { halign: 'center', valign: 'middle', fontStyle: 'bold',fontSize: 7 } },
    { content: 'Daftar Ulang', colSpan: 3, styles: { halign: 'center', valign: 'middle', fontStyle: 'bold',fontSize: 7 } },
    { content: 'Tidak Daftar Ulang', colSpan: 1, styles: { halign: 'center', valign: 'middle', fontStyle: 'bold',fontSize: 7 } }
];

    // Menambahkan header tambahan ke dalam data tabel
    tableData.push(additionalHeader);

    // Menambahkan header ke dalam data tabel
    tableData.push([
    { content: '', styles: { fontSize: 7 } },
    { content: '', styles: { fontSize: 7 } },
    { content: '', styles: { fontSize: 7 } },
    { content: `({{ date('d-m-Y', strtotime($endDate . ' -1 day')) }})`, styles: { fontSize: 7 } },
    { content: `({{ date('d-m-Y', strtotime($endDate)) }})`, styles: { fontSize: 7 } },
    { content: 'Jumlah', styles: { fontSize: 7 } },
    { content: `({{ date('d-m-Y', strtotime($endDate . ' -1 day')) }})`, styles: { fontSize: 7 } },
    { content: `({{ date('d-m-Y', strtotime($endDate)) }})`, styles: { fontSize: 7 } },
    { content: 'Jumlah', styles: { fontSize: 7 } },
    { content: `({{ date('d-m-Y', strtotime($endDate)) }})`, styles: { fontSize: 7 } }
]);

    // Menambahkan data ke dalam tabel
    data.fakultasData.forEach((fakultas, index) => {
        tableData.push([
    { content: index + 1, styles: { halign: 'center', valign: 'middle',fontSize: 7 } },
    { content: fakultas.fakultas, styles: { halign: 'center', valign: 'middle',fontSize: 7 } },
    { content: fakultas.prodi, styles: { halign: 'center', valign: 'middle',fontSize: 7 } },
    { content: data.daftarsebelumnya[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle' ,fontSize: 7 } },
    { content: data.dafarhariini[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle' ,fontSize: 7 } },
    { content: data.totalCounts[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle' ,fontSize: 7 } },
    { content: data.daftarUlangsebelumnya[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle' ,fontSize: 7 } },
    { content: data.dafarUlanghariini[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle' ,fontSize: 7 } },
    { content: data.totalUlang[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle' ,fontSize: 7 } },
    { content: data.totalTidak[fakultas.prodi] ?? 0, styles: { halign: 'center', valign: 'middle',fontSize: 7 } }
]);

});
    // Hitung total dari setiap kolom
    var totalDaftarSebelumnya = 0;
    var totalDaftarHariIni = 0;
    var totalJumlahDaftar = 0;
    var totalDaftarUlangSebelumnya = 0;
    var totalDaftarUlangHariIni = 0;
    var totalJumlahDaftarUlang = 0;
    var totalJumlahTidak = 0;

    data.fakultasData.forEach(fakultas => {
    totalDaftarSebelumnya += data.daftarsebelumnya[fakultas.prodi] ?? 0;
    totalDaftarHariIni += data.dafarhariini[fakultas.prodi] ?? 0;
    totalJumlahDaftar += data.totalCounts[fakultas.prodi] ?? 0;
    totalDaftarUlangSebelumnya += data.daftarUlangsebelumnya[fakultas.prodi] ?? 0;
    totalDaftarUlangHariIni += data.dafarUlanghariini[fakultas.prodi] ?? 0;
    totalJumlahDaftarUlang += data.totalUlang[fakultas.prodi] ?? 0;
    totalJumlahTidak += data.totalTidak[fakultas.prodi] ?? 0;
});

// Menambahkan baris total ke dalam data tabel
// Baris total dengan ukuran teks lebih besar
tableData.push([
    { content: '', styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: 'Total', styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: '', styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalDaftarSebelumnya, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalDaftarHariIni, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalJumlahDaftar, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalDaftarUlangSebelumnya, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalDaftarUlangHariIni, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalJumlahDaftarUlang, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } },
    { content: totalJumlahTidak, styles: { fontSize: 12, fillColor: [153, 255, 255], textColor: [0, 0, 0], halign: 'center', valign: 'middle' } }
]);

doc.autoTable({
    head: tableData.slice(0, 2),
    body: tableData.slice(2),
    startY: 100,
    styles: { fontSize: 5, lineWidth: 0.5, lineColor: [0, 0, 0], textColor: [0, 0, 0],
        halign: 'center', // Default horizontal alignment
                valign: 'middle', // Default vertical alignment
     },
    theme: 'grid',
    margin: { top: 20, right: 20, bottom: 50, left: 20 },
    tableWidth: 'auto',
    headerStyles: { fillColor: [255, 255, 255], fontStyle: 'bold',
    halign: 'center', // Center align header text
    valign: 'middle', // Middle align header text
     },
    
});
@endif
    // Mengunduh PDF
    doc.setFontSize(10);
    const fileName = `Monitoring_PMB_${lokasiText}_${ta}_${formattedDate}.pdf`;
    doc.save(fileName);
}

</script>
@endsection
