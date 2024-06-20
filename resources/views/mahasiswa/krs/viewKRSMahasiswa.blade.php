@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showCetakKRS') }}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cetak KRS Mahasiswa</li>
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
                        @if(count($results) > 0)
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>urut</th>
                                    <th>NPM</th>
                                    <th>ID Kampus</th>
                                    <th>Prodi</th>
                                    <th>TA</th>
                                    <th>IDMK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->Urut}}</td>
                                    <td>{{ $result->NPM }}</td>
                                    <td>{{ $result->Idkampus }}</td>
                                    <td>{{ $result->Prodi }}</td>
                                    <td>{{ $result->TA }}</td>
                                    <td>{{ $result->IDMK }}</td>
                                    <td>{{ $result->Matakuliah }}</td>
                                    <td>{{ $result->SKS }}</td>
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
        var doc = new jsPDF('p', 'pt', 'a4');
        // Set font size for the document
        doc.setFontSize(15);

        // Menambahkan informasi TA, Semester, stambuk, prodi, idkampus, dan lokasi
        var data = @json($data);
        var lokasiText = data.lokasi === 'UQM' ? 'Medan' : (data.lokasi === 'UQB' ? 'Berastagi' : '');

        // Menambahkan teks informasi ke dokumen PDF
        doc.setFontSize(25);
        doc.setFont("Arial");
        doc.setFont(undefined, 'bold'); // Mengatur teks menjadi tebal
        doc.setTextColor(0, 0, 0); // Mengatur warna teks menjadi hitam
        doc.text(`Universitas Quality`, 200, 40);
        doc.text(`${lokasiText}`, 250, 80); // Teks ${lokasiText}

        // Mengatur font, ukuran, dan gaya font untuk informasi
        doc.setFontSize(20);
        doc.text('Kartu Rencana Studi', 220, 180);

        // Menambahkan informasi data program studi, fakultas, TA/semester, dst.
        doc.setFont(undefined, 'normal'); // Mengatur teks kembali ke normal
        doc.setFontSize(10);
        doc.setFont("Tahoma");
        var startY = 180; // Mulai dari posisi yang ditentukan
        var lineHeight = 20; // Tinggi baris antara label dan nilai

        // Menentukan posisi vertikal untuk "Program Studi" dan "Fakultas"
        var verticalPos = 120; // Sesuaikan posisi vertikal ini sesuai kebutuhan

        // Menambahkan setiap label dan nilainya dalam format kolom
        doc.text('Program Studi', 40, verticalPos);
        doc.text(':', 140, verticalPos);
        doc.text(data.prodi, 160, verticalPos);
        doc.text('Fakultas', 40, verticalPos + lineHeight);
        doc.text(':', 140, verticalPos + lineHeight);
        doc.text(data.fakultas, 160, verticalPos + lineHeight);
        doc.text('TA/Semester', 40, startY + 1 * lineHeight);
        doc.text(':', 140, startY + 1 * lineHeight);
        doc.text(`${data.TA}/${data.semester}`, 160, startY + 1 * lineHeight);
        doc.text('Nama', 40, startY + 2 * lineHeight);
        doc.text(':', 140, startY + 2 * lineHeight);
        doc.text(data.nama, 160, startY + 2 * lineHeight);
        doc.text('NPM', 40, startY + 3 * lineHeight);
        doc.text(':', 140, startY + 3 * lineHeight);
        doc.text(data.npm, 160, startY + 3 * lineHeight);
        doc.text('Dosen Wali', 40, startY + 4 * lineHeight);
        doc.text(':', 140, startY + 4 * lineHeight);
        doc.text(data.namaDosen || '', 160, startY + 4 * lineHeight);
        doc.text('Alamat', 40, startY + 5 * lineHeight);
        doc.text(':', 140, startY + 5 * lineHeight);
        doc.text(data.alamat, 160, startY + 5 * lineHeight);
        doc.setFontSize(8);
        // Menghitung total SKS
        var totalSKS = 0;
        @foreach ($results as $key => $result)
            totalSKS += {{ $result->SKS }};
        @endforeach

        var startY = 140;
        // Menyimpan data tabel ke dalam array
        var tableData = [];
        var headers = ['No', 'IDMK', 'Mata Kuliah', 'SKS'];
        @foreach ($results as $key => $result)
            tableData.push([
                {{ $result->Urut }},
                '{{ $result->IDMK }}',
                '{{ $result->Matakuliah }}',
                '{{ $result->SKS }}'
            ]);
        @endforeach

        // Menambahkan total SKS ke dalam data tabel
        tableData.push(['', '',  'JUMLAH KREDIT YANG DIAMBIL', totalSKS]);

        // Menambahkan tabel mata kuliah
        // Define the headers separately
        var headers = ['No', 'IDMK', 'Mata Kuliah', 'SKS'];

        // Insert the headers as the first row in the tableData array
        tableData.unshift(headers);

        // Generate the table with the updated tableData array
        doc.autoTable({
            body: tableData,
            startY: startY + 9 * lineHeight,
            styles: { fontSize: 10, font: "Tahoma", lineWidth: 1.2,lineColor: [0, 0, 0],textColor: [0, 0, 0] },
            theme: 'grid',
            columnStyles: {
                0: { cellWidth: 30 },
                1: { cellWidth: 80 },
                2: { cellWidth: 270 },
                3: { cellWidth: 30 }
            },
            headerStyles: { fillColor: [255, 255, 255] }
        });

        // Menambahkan halaman baru
        doc.addPage();

        // Membuat QR Code dari NPM
        var npm = data.npm; // Ambil NPM dari data
        var qr = new QRCode(document.createElement("div"), {
            text: npm,
            width: 100,
            height: 100
        });
        // Mendapatkan gambar QR Code dari elemen QR Code
        var qrImage = qr._el.childNodes[0].toDataURL("image/png");
        // Menambahkan QR Code ke dalam dokumen PDF
        var qrPositionX = 70; // Sesuaikan dengan posisi yang diinginkan
        var qrPositionY = 70; // Menempatkannya di halaman baru
        doc.addImage(qrImage, 'PNG', qrPositionX, qrPositionY, 50, 50); // Menyisipkan QR Code ke dalam PDF
        // Menambahkan teks "KODE AUTHENTIKASI" di atas QR code
        doc.setFontSize(10);
        doc.text('KODE AUTHENTIKASI', qrPositionX , qrPositionY - 10);
        
        // Menambahkan Disahkan Oleh Ketua Program Studi
        var kaprodiName = '{{ isset($data["kaprodi"][0]->nama) ? $data["kaprodi"][0]->nama : "" }}'; // Ambil nama Kaprodi dari data
        var disahkanText = 'Disahkan Oleh\nKetua Program Studi';
        var qrWidth = 100; // Width of the QR code image
        // Mengukur lebar teks "Disetujui Oleh Dosen Wali" untuk menyesuaikan posisi teks "Disahkan Oleh Ketua Program Studi"
        var disetujuiTextWidth = doc.getStringUnitWidth('Disetujui Oleh\nDosen Wali') * 12; // Menggunakan font size 12
        var disahkanPositionX = qrPositionX + qrWidth + 40; // Tentukan posisi awal
        var disahkanPositionY = qrPositionY + 120; // Tentukan posisi vertikal yang sesuai
        var dosenWaliName = '{{ isset($data["wali"][0]->nama) ? $data["wali"][0]->nama : "" }}'; 

        var dosenWaliText = 'Disetujui Oleh\nDosen Wali';
        var qrDosenWaliPositionX = disahkanPositionX + 110; // Sesuaikan dengan posisi yang diinginkan
        var qrDosenWaliPositionY = qrPositionY + 140; // Menempatkannya di halaman baru

        doc.text(disahkanText, qrPositionX, disahkanPositionY); // Menambahkan teks "Disahkan Oleh Ketua Program Studi"
        doc.text(kaprodiName, qrPositionX + 10, qrPositionY + 210); // Menambahkan nama Kaprodi di atas QR Code
        doc.text(dosenWaliText, qrPositionX + 250, disahkanPositionY); // Menambahkan teks "Disetujui Oleh Dosen Wali"
        doc.text(dosenWaliName, qrDosenWaliPositionX, qrDosenWaliPositionY + 70); // Menambahkan nama Dosen Wali di atas QR Code

        // Membuat QR Code dari ID dosen kaprodi
        var idDosenKaprodi = '{{ isset($data["kaprodi"][0]->iddosen) ? $data["kaprodi"][0]->iddosen : "" }}'; // Ambil ID dosen kaprodi dari data
        var qrKaprodi = new QRCode(document.createElement("div"), {
            text: idDosenKaprodi,
            width: 100,
            height: 100
        });

        // Mendapatkan gambar QR Code dari elemen QR Code
        var qrKaprodiImage = qrKaprodi._el.childNodes[0].toDataURL("image/png");

        // Menambahkan QR Code ID dosen kaprodi ke dalam dokumen PDF
        var qrKaprodiPositionX = qrPositionX; // Sesuaikan dengan posisi yang diinginkan
        var qrKaprodiPositionY = qrPositionY + 140; // Menempatkannya di halaman baru
        doc.addImage(qrKaprodiImage, 'PNG', qrKaprodiPositionX, qrKaprodiPositionY, 50, 50); // Menyisipkan QR Code ID dosen kaprodi ke dalam PDF

        // Membuat QR Code dari ID dosen wali
        var idDosenWali = '{{ isset($data["wali"][0]->iddosen) ? $data["wali"][0]->iddosen : "" }}';
 // Ambil ID dosen wali dari data
        var qrDosenWali = new QRCode(document.createElement("div"), {
            text: idDosenWali,
            width: 100,
            height: 100
        });

        // Mendapatkan gambar QR Code dari elemen QR Code dosen wali
        var qrDosenWaliImage = qrDosenWali._el.childNodes[0].toDataURL("image/png");

        // Menambahkan QR Code ID dosen wali ke dalam dokumen PDF
        doc.addImage(qrDosenWaliImage, 'PNG', qrDosenWaliPositionX, qrDosenWaliPositionY, 50, 50); // Menyisipkan QR Code ID dosen wali ke dalam PDF
        
        // Mengunduh PDF
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20); // Menambahkan Print Date / Print Time di bagian bawah halaman terakhir
        const fileName = `KRS_Mahasiswa_${data.nama}_${data.npm}_${data.TA}_${formattedDate}.pdf`; // Nama file PDF dengan informasi nama, npm, ta, dan stambuk
        doc.save(fileName);

    }

</script>
@endsection
