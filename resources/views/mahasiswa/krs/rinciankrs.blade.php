@extends('admin.dashboard')
@section('admin')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showRincian') }}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rincian KRS Mahasiswa</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class ="row">
                    <div class="col-md-6">
                       <button onclick="downloadPDF()" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">Download PDF
                           <i class="btn-icon-prepend" data-feather="printer"></i>
                       </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success" onclick="exportToExcel()">Export to Excel
                        <i class="btn-icon-prepend" data-feather="printer"></i>
                    </button>
                    </div>
                </div>
                </div>
            </div></div></div>

                <div class="row">
     <div class="col-md-12 ">
         <div class="card">
             <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        @if(count($results) > 0)
                            <table id="myExportableTable" class="table" font-size="10">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th>SKS Di Ambil</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                @endphp
                                    @foreach ($results as $result)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result->NPM }}</td>
                                        <td>{{ $result->nama }}</td>
                                        <td>{{ $result->SKS }}</td>
                                        <td>{{ $result->Keterangan }}</td>
                                        <td>{{ $result->StatusMHS }}</td>
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
<script>
    window.jsPDF = window.jspdf.jsPDF;
    function downloadPDF() {
    var doc = new jsPDF('l', 'pt', 'a4');

    // Set font size for the document
    doc.setFontSize(15);


    // Menambahkan informasi TA, Semester, stambuk, prodi, idkampus, dan lokasi
    var TA = "{{ session('TA') }}";
    var Semester = "{{ session('Semester') }}";
    var stambuk = "{{ session('stambuk') }}";
    var prodi = "{{ session('prodi') }}";
    var idkampus = "{{ session('idkampus') }}";
    var lokasi = "{{ session('lokasi') }}";

    // Menambahkan teks informasi ke dokumen PDF
    doc.setFontSize(15);
    doc.text('Universitas Quality', 10, 20);
    doc.text('Rincian KRS Mahasiswa Quality', 10, 40);
    doc.setFontSize(8);
    doc.text(`ID Kampus: ${idkampus}/${lokasi}`, 500, 20);
    doc.text(`TA/Semester: ${TA}/${Semester}`, 500, 40);
    doc.text(`Stambuk: ${stambuk}`, 650, 20);
    doc.text(`Program Studi: ${prodi}`, 650, 40);
 
    // Menyimpan data tabel ke dalam array
    var data = [];
    var headers = ['No', 'NPM', 'Nama', 'SKS Di Ambil', 'Keterangan'];
    @foreach ($results as $key => $result)
    data.push([
        {{ $key + 1 }}, // Nomor urut, dimulai dari 1
        '{{ $result->NPM }}',
        '{{ $result->nama }}',
        '{{ $result->SKS }}',
        '{{ $result->Keterangan }}'
    ]);
@endforeach

    // Pindahkan posisi tabel ke bawah untuk memberikan ruang bagi teks
    var startY = 100;

    // Membuat tabel di PDF
    doc.autoTable({
        head: [headers],
        body: data,
        startY: startY // Mulai dari posisi yang ditentukan
    });

    // Mengunduh PDF
    const currentDate = new Date();
const formattedDate = currentDate.toLocaleDateString('en-US');
const formattedTime = currentDate.toLocaleTimeString('en-US');
const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);
const fileName = `Rincian_KRS_Mahasiswa_${lokasi}_${prodi}_TA${TA}_Semester${Semester}_Stambuk${stambuk}_${formattedDate}.pdf`;
    doc.save(fileName);

}
function exportToExcel() {
    // Ambil semester dan tahun ajaran dari session
    const semester = "{{ session('Semester') }}";
    const TA = "{{ session('TA') }}";
    const statusMHS = "{{ $statusMHS }}"; // Menambahkan baris ini
    const stambuk = "{{ session('stambuk') }}";
    const prodi = "{{ session('prodi') }}";
    const idkampus = "{{ session('idkampus') }}";
    let sheetName;
    let fileName;

    if (statusMHS !== '') {
        sheetName = `RincianKRS ${semester} ${TA} ${statusMHS}`;
        fileName = `Rincian_KRS_Mahasiswa_${semester}_${TA}_${statusMHS}_${stambuk}_${prodi}.xlsx`;
    } else {
        sheetName = `RincianKRS ${semester} ${TA} ${stambuk} Keseluruhan `;
        fileName = `Rincian_KRS_Mahasiswa_${semester}_${TA}_Keseluruhan_${stambuk}_${prodi}.xlsx`;
    }

    // Inisialisasi tabel Excel
    const table = document.getElementById('myExportableTable');

    // Buat objek Excel
    const wb = XLSX.utils.table_to_book(table, { sheet: sheetName });

    // Simpan file Excel
    XLSX.writeFile(wb, fileName);
}
</script>
@endsection
