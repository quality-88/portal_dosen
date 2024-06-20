@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showCetakKRS') }}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cetak LLDikti</li>
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
                    <div class="table-responsive">
                        @if(isset($data['result1']) && isset($data['result2'])
                        && (count($data['result1']) > 0 || count($data['result2']) > 0))
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Kuliah Asal</th>
                                    <th>SKS</th>
                                    <th>Nilai</th>
                                    <th>Mata Kuliah</th>
                                    <th>IDMK</th>
                                    <th>SKS</th>
                                    <th>Nilai</th>
                                    <th>Hasil Pengakuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                            @endphp
                                @foreach($data['result1'] as $result)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result->MATAKULIAHASAL }}</td>
                                        <td>{{ $result->SKSAsal }}</td>
                                        <td>{{ $result->NILAIAKHIR }}</td>
                                        <td>{{ $result->IDMK }}</td>
                                        <td>{{ $result->Matakuliah }}</td>
                                        
                                        <td>{{ $result->SKS }}</td>
                                        <td>{{ $result->NILAIAKHIR }}</td>
                                        <td>{{ $result->HasilPengakuan }}</td>
                                    </tr>
                                @endforeach
                                @foreach($data['result2'] as $result)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result->matakuliahasal }}</td>
                                        <td>{{ $result->sksasal }}</td>
                                        <td>{{ $result->NilaiAkhir }}</td>
                                        <td>{{ $result->idmk }}</td>
                                        <td>{{ $result->matakuliah }}</td>
                                        
                                        <td>{{ $result->sks }}</td>
                                        <td>{{ $result->NilaiAkhir }}</td>
                                        <td>{{ $result->HasilPengakuan }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<!-- Add these lines to the head section of your HTML document -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
<!-- Script untuk QRCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
<script>
window.jsPDF = window.jspdf.jsPDF;

function downloadPDF() {
    var doc = new jsPDF('L', 'pt', 'a4');
    doc.setFontSize(15);

    var data = @json($data); // Ambil data dari variabel PHP
    var lokasiText = '';

if (['CREATOR MEDAN', 'SIDIKALANG', 'LANGKAT', 'TIGA BINANGA', 'SANTA MARIA', 'SERDANG BEDAGAI', 'DELI SERDANG', 'MARELAN'].includes(data.lokasi)) {
    lokasiText = 'MEDAN';
} else {
    lokasiText = 'BERASTAGI';
}

  // Menambahkan teks informasi ke dokumen PDF
  doc.setFontSize(30);
  doc.setFont("Arial");
  doc.setFont(undefined, 'bold'); // Mengatur teks menjadi tebal
  doc.setTextColor(0, 0, 0); // Mengatur warna teks menjadi hitam
  doc.text(`UNIVERSITAS QUALITY ${lokasiText}`, 180, 40);
 
  // Mengatur font, ukuran, dan gaya font untuk informasi
  doc.setFontSize(10);
  doc.text('DAFTAR PENGAKUAN/KONVERSI SKS DAN NILAI MAHASISWA TRANSFER PINDAHAN & LANJUTAN', 180, 80);
  // Menambahkan informasi data program studi, fakultas, TA/semester, dst.
  doc.setFont(undefined, 'normal'); // Mengatur teks kembali ke normal
  doc.setFontSize(10);
  doc.setFont("Tahoma");
  var startY = 100; // Mulai dari posisi yang ditentukan
  var lineHeight = 10; // Tinggi baris antara label dan nilai
  // Menambahkan setiap label dan nilainya dalam format kolom
  doc.text('Nama',20, startY + 1 * lineHeight);
  doc.text(':', 150, startY + 1 * lineHeight);
  doc.text(data.nama, 160, startY + 1 * lineHeight);
  doc.text('NPM',20, startY + 2 * lineHeight);
  doc.text(':', 150, startY + 2 * lineHeight);
  doc.text(data.npm, 160, startY + 2 * lineHeight);
  doc.text('NPM ASAL',20, startY + 3 * lineHeight);
  doc.text(':', 150, startY + 3 * lineHeight);
  doc.text(data.npmasal ||'', 160, startY + 3 * lineHeight);
  doc.text('FAKULTAS',20, startY + 4 * lineHeight);
  doc.text(':', 150, startY + 4 * lineHeight);
  doc.text(data.fakultas, 160, startY + 4 * lineHeight);
  doc.text('TA',20, startY + 5 * lineHeight);
  doc.text(':', 150, startY + 5 * lineHeight);
  doc.text(`${data.ta}`, 160, startY + 5 * lineHeight);
  doc.text('PRODI',20, startY + 6 * lineHeight);
  doc.text(':', 150, startY + 6 * lineHeight);
  doc.text(`${data.prodi}`, 160, startY + 6 * lineHeight);
  doc.text('PRODI ASAL',20, startY + 7 * lineHeight);
  doc.text(':', 150, startY + 7 * lineHeight);
  doc.text(`${data.prodiasal ||''}`, 160, startY + 7 * lineHeight);
  doc.text('PERGURUAN TINGGI ASAL',20, startY + 8 * lineHeight);
  doc.text(':', 150, startY + 8 * lineHeight);
  doc.text(`${data.universitas||''}`, 160, startY + 8 * lineHeight);
  doc.setFontSize(8);
    var tableData = [];
    var headers = ['No', 'Mata Kuliah Asal', 'SKS', 'Nilai', 'Mata Kuliah', 'IDMK', 'SKS', 'Nilai', 'Hasil Pengakuan'];

    // Header tambahan
    var additionalHeader = [
        { content: '', colSpan: 1, styles: { halign: 'center', fontStyle: 'bold' } },
        { content: 'Mata Kuliah/Bobot SKS PT.Asal', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
        { content: 'Konversi Mata Kuliah Universitas Quality', colSpan: 5, styles: { halign: 'center', fontStyle: 'bold' } }
    ];

    // Menambahkan header tambahan ke dalam data tabel
    tableData.push(additionalHeader);

    // Menambahkan header ke dalam data tabel
    tableData.push(headers);
    var no = 1;

    // Function to replace &amp; with &
    function replaceAmpersand(str) {
        return str ? str.replace(/&amp;/g, '&') : str;
    }

    // Menambahkan data dari result1 ke dalam tabel
    @foreach ($data['result1'] as $result)
    tableData.push([
        no++, // Nomor
        replaceAmpersand('{{ $result->MATAKULIAHASAL }}'),
        '{{ $result->SKSAsal }}',
        '{{ $result->NILAIAKHIR }}',
        replaceAmpersand('{{ $result->Matakuliah }}'),
        '{{ $result->IDMK }}',
        '{{ $result->SKS }}',
        '{{ $result->NILAIAKHIR }}',
        '{{ $result->HasilPengakuan }}',
    ]);
    @endforeach

    // Menambahkan data dari result2 ke dalam tabel
    @foreach ($data['result2'] as $result)
    tableData.push([
        no++, // Nomor
        replaceAmpersand('{{ $result->matakuliahasal }}'),
        '{{ $result->sksasal }}',
        '{{ $result->NilaiAkhir }}',
        replaceAmpersand('{{ $result->matakuliah }}'),
        '{{ $result->idmk }}',
        '{{ $result->sks }}',
        '{{ $result->NilaiAkhir }}',
        '{{ $result->HasilPengakuan }}',
    ]);
    @endforeach
    // Menambahkan data totalsks1, totalsks2, total, totalSKS, dan totalSKSResult2 ke dalam tabel


    var startY = 160; // Mulai dari posisi yang ditentukan
    var lineHeight = 10; // Tinggi baris antara label dan nilai
    var tableHeight = doc.autoTable.previous.finalY || startY + 4 * lineHeight; 
    var tableWidth = doc.internal.pageSize.width - 100;
    // Membuat tabel dengan menggunakan plugin jsPDF Autotable
    doc.autoTable({
        body: tableData,
        startY: tableHeight,
        styles: { fontSize: 7, lineWidth: 1.2, lineColor: [0, 0, 0], textColor: [0, 0, 0] },
        theme: 'grid',
        margin: { top: 20, right: 20, bottom: 20, left: 20 },
        tableWidth: 'auto',
        columnStyles: {
            0: { cellWidth: 30 },
            1: { cellWidth: 180 },
            2: { cellWidth: 30 },
            3: { cellWidth: 30 },
            4: { cellWidth: 250 },
            5: { cellWidth: 80 },
            6: { cellWidth: 30 },
            7: { cellWidth: 30 },
            8: { cellWidth: 130 }
        },
        margin: { top: 20, right: 20, bottom: 50, left: 20 }, // Atur margin agar tabel mencapai pinggiran kertas
        headerStyles: { fillColor: [255, 255, 255], fontWeight: 'bold' },
        didDrawPage: function (data) {
            if (data.table.finalY > 600) { // Ubah nilai 600 sesuai kebutuhan
                // Jika tabel telah mencapai akhir halaman, tambahkan halaman baru dan tambahkan QR code
                doc.addPage(); // Tambahkan halaman baru
                tableHeight = 30; // Resetting tableHeight untuk memulai tabel dari atas halaman baru
            }
        }
    });
    // Membuat tabel tambahan
var additionalTableData = [
    ['NO', 'Keterangan', 'SKS'],
    ['', 'Jumlah SKS yang diakui lansung', data.totalsks1],
    ['', 'Jumlah SKS yang diakui dengan syarat tertentu', data.totalsks2],
    ['', 'Jumlah SKS Sebagai Syarat Kelulusan', data.totalSKS],
    ['', 'Total SKS Yang Di Akui', data.total],
    ['', 'Total SKS dari Yang Harus Diikuti/ Wajib Ambil ', data.totalSKSResult2]
];

// Menambahkan tabel tambahan ke dokumen PDF
doc.autoTable({
    body: additionalTableData,
    startY:  doc.autoTable.previous.finalY + 10, // Memulai tabel tambahan di bawah tabel sebelumnya
    styles: { fontSize: 8, lineWidth: 1.2, lineColor: [0, 0, 0], textColor: [0, 0, 0] },
    theme: 'grid',
    margin: { top: 50, right: 20, bottom: 50, left: 20 }, // Atur margin sesuai kebutuhan
    tableWidth: 'auto',
    columnStyles: {
        0: { cellWidth: 30 },
        1: { cellWidth: 730 },
        2: { cellWidth: 30 }
    },
    headerStyles: { fillColor: [255, 255, 255], fontWeight: 'bold' },
    didDrawPage: function (data) {
        if (data.table.finalY > 600) { // Ubah nilai 600 sesuai kebutuhan
            doc.addPage(); // Tambahkan halaman baru jika tabel mencapai akhir halaman
        }
    }
});
doc.setFontSize(10);
    var startY = doc.autoTable.previous.finalY + 30;

    // Teks di sebelah kiri
    doc.text('Di Ketahui Dekan', 50, startY);
    doc.text('(________________________)', 50, startY + 80);

    // Teks di sebelah kanan
    var rightAlignX = doc.internal.pageSize.width - 200;
    doc.text('Team Konversi Program Studi', rightAlignX, startY);
    doc.text('Manajemen UQB', rightAlignX, startY + 20);
    doc.text('(________________________)', rightAlignX, startY + 80);
    // Mengunduh PDF
    const currentDate = new Date();
    const formattedDate = currentDate.toLocaleDateString('en-US');
    const formattedTime = currentDate.toLocaleTimeString('en-US');
    const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
    doc.text(printDateTime, 50, doc.internal.pageSize.height - 20); // Menambahkan Print Date / Print Time di bagian bawah halaman terakhir
    const fileName = `KONVERSI SKS DAN NILAI MAHASISWA TRANSFER PINDAHAN & LANJUTAN_${data.nama}_${data.npm}_${data.ta}_${formattedDate}.pdf`; // Nama file PDF dengan informasi nama, npm, ta, dan
    doc.save(fileName);
}

</script>
@endsection
