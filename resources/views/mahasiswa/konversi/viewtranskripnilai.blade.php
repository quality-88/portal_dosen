@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showCetakKRS') }}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cetak Transkrip Nilai</li>
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
                        @if(isset($data['result1']) && isset($data['result2']) && isset($data['missingCourses']) 
                        && (count($data['result1']) > 0 || count($data['result2']) > 0 || count($data['missingCourses']) > 0))
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>Semester</th>
                                    <th>SKS</th>
                                    <th>Nilai Akhir</th>
                                    <th>K x AP*</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                            @endphp
                                @foreach($data['result1'] as $result)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result->idmk }}</td>
                                        <td>{{ $result->MATAKULIAH }}</td>
                                        <td>{{ $result->MatakuliahSemester }}</td>
                                        <td>{{ $result->SKS }}</td>
                                        <td>{{ $result->NilaiAkhir }}</td>
                                        <td>{{ $result->kali }}</td>
                                    </tr>
                                @endforeach
                                @foreach($data['result2'] as $result)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result->idmk }}</td>
                                        <td>{{ $result->MATAKULIAH }}</td>
                                        <td>{{ $result->SEMESTER }}</td>
                                        <td>{{ $result->SKS }}</td>
                                        <td>{{ $result->NilaiAkhir }}</td>
                                        <td>{{ $result->kali }}</td>
                                    </tr>
                                @endforeach
                                @foreach($data['missingCourses'] as $result)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result['idmk'] }}</td>
                                        <td>{{ $result['matakuliah'] }}</td>
                                        <td>{{ $result['semester'] }}</td>
                                        <td>{{ $result['sks'] }}</td>
                                        <td>{{ $result['nilaiAkhir'] }}</td>
                                        <td>{{ $result['kali'] }}</td>
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
        doc.setFontSize(30);
        doc.setFont("Arial");
        doc.setFont(undefined, 'bold'); // Mengatur teks menjadi tebal
        doc.setTextColor(0, 0, 0); // Mengatur warna teks menjadi hitam
        doc.text(`Universitas Quality`, 180, 40);
       

        // Mengatur font, ukuran, dan gaya font untuk informasi
        doc.setFontSize(25);
        doc.text('Transkrip Nilai', 200, 100);

        // Menambahkan informasi data program studi, fakultas, TA/semester, dst.
        doc.setFont(undefined, 'normal'); // Mengatur teks kembali ke normal
        doc.setFontSize(10);
        doc.setFont("Tahoma");
        var startY = 140; // Mulai dari posisi yang ditentukan
        var lineHeight = 20; // Tinggi baris antara label dan nilai

        // Menambahkan setiap label dan nilainya dalam format kolom
        doc.text('TA/Semester', 40, startY + 1 * lineHeight);
        doc.text(':', 140, startY + 1 * lineHeight);
        doc.text(`${data.TA}/${data.semester}`, 160, startY + 1 * lineHeight);
        doc.text('Nama', 40, startY + 2 * lineHeight);
        doc.text(':', 140, startY + 2 * lineHeight);
        doc.text(data.nama, 160, startY + 2 * lineHeight);
        doc.text('NPM', 40, startY + 3 * lineHeight);
        doc.text(':', 140, startY + 3 * lineHeight);
        doc.text(data.npm, 160, startY + 3 * lineHeight);
        doc.setFontSize(8);
        // Menghitung total SKS
        var totalSKS = 0;


       
        var tableData = [];
        var headers = ['No', 'IDMK', 'Mata Kuliah', 'SMT', 'SKS', 'Nilai Akhir', 'K x AP*'];

        // Menambahkan header ke dalam data tabel
        tableData.push(headers);
        var no = 1;
        // Menambahkan data dari result1 ke dalam tabel
        @foreach ($data['result1'] as $result)
        tableData.push([
             no++, // Nomor
            '{{ $result->idmk }}', // IDMK
            '{{ $result->MATAKULIAH }}', // Mata Kuliah
            '{{ $result->MatakuliahSemester }}', // Semester
            '{{ $result->SKS }}', // SKS
            '{{ $result->NilaiAkhir }}', // Nilai Akhir
            '{{ $result->kali }}', // K x AP*
        ]);
        @endforeach

        // Menambahkan data dari result2 ke dalam tabel
        @foreach ($data['result2'] as $result)
        tableData.push([
             no++, // Nomor
            '{{ $result->idmk }}', // IDMK
            '{{ $result->MATAKULIAH }}', // Mata Kuliah
            '{{ $result->SEMESTER }}', // Semester
            '{{ $result->SKS }}', // SKS
            '{{ $result->NilaiAkhir }}', // Nilai Akhir
            '{{ $result->kali }}', // K x AP*
        ]);
        @endforeach

        // Menambahkan data dari missingCourses ke dalam tabel
        @foreach ($data['missingCourses'] as $result)
        tableData.push([
             no++, // Nomor
            '{{ $result['idmk'] }}', // IDMK
            '{{ $result['matakuliah'] }}', // Mata Kuliah
            '{{ $result['semester'] }}', // Semester
            '{{ $result['sks'] }}', // SKS
            '{{ $result['nilaiAkhir'] }}', // Nilai Akhir
            '{{ $result['kali'] }}', // K x AP*
        ]);
        @endforeach

        // Menghitung total SKS yang dimiliki
        var totalSKS = 0;
        @foreach ($data['result1'] as $result)
        if ('{{ $result->NilaiAkhir }}' !== null) {
            totalSKS += {{ $result->SKS }};
        }
        @endforeach
        @foreach ($data['result2'] as $result)
        if ('{{ $result->NilaiAkhir }}' !== null) {
            totalSKS += {{ $result->SKS }};
        }
        @endforeach

        var totalNilai = data.totalNilai;
        var ipk =data.IPK;
        var ipkString = ipk.toFixed(2);

        // Memeriksa apakah digit desimal terakhir adalah nol, jika ya, hapus
        if (ipkString.endsWith('.00')) {
            ipkString = ipkString.substring(0, ipkString.length - 3);
        }

        // Mengganti titik dengan koma untuk pemisah desimal
        var ipkFormatted = ipkString.replace('.', ',');
        // Menambahkan total SKS ke dalam data tabel
        tableData.push(['','','JUMLAH KREDIT YANG DIAMBIL','', totalSKS, '',totalNilai]);
        tableData.push(['','','IP KAMULATIF','', '','',ipkFormatted]);
        // Menampilkan tabel dalam PDF
        var tableHeight = doc.autoTable.previous.finalY || startY + 4 * lineHeight; // Menyimpan tinggi tabel sebelumnya atau menggunakan nilai default
        doc.autoTable({
            body: tableData,
            startY: tableHeight,
            styles: { fontSize: 7, font: "Tahoma", lineWidth: 1.2, lineColor: [0, 0, 0], textColor: [0, 0, 0] },
            theme: 'grid',
            columnStyles: {
                0: { cellWidth: 20 },
                1: { cellWidth: 70 },
                2: { cellWidth: 200 },
                3: { cellWidth: 30 },
                4: { cellWidth: 30 },
                5: { cellWidth: 30 },
                6: { cellWidth: 30 }
            },
            headerStyles: { fillColor: [255, 255, 255] },
            didDrawPage: function (data) {
                if (data.table.finalY > 600) { // Ubah nilai 600 sesuai kebutuhan
                    // Jika tabel telah mencapai akhir halaman, tambahkan halaman baru dan tambahkan QR code
                    doc.addPage();
                    // Lanjutkan kode untuk menambahkan QR code di halaman baru
                }
            }
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
        var qrPositionY = 100; // Menempatkannya di halaman baru
        doc.addImage(qrImage, 'PNG', qrPositionX, qrPositionY, 50, 50); // Menyisipkan QR Code ke dalam PDF
        // Menambahkan teks "KODE AUTHENTIKASI" di atas QR code
        doc.setFontSize(10);
        doc.text('KODE AUTHENTIKASI', qrPositionX , qrPositionY - 10);

        // Menambahkan Disahkan Oleh Ketua Program Studi
        var kaprodiName = '{{ isset($data["kaprodi"][0]->nama) ? $data["kaprodi"][0]->nama : ""  }}'; // Ambil nama Kaprodi dari data
        var disahkanText = 'Disahkan Oleh\nKetua Program Studi';
        var qrWidth = 100; // Width of the QR code image
        // Mengukur lebar teks "Disetujui Oleh Dosen Wali" untuk menyesuaikan posisi teks "Disahkan Oleh Ketua Program Studi"
        var disetujuiTextWidth = doc.getStringUnitWidth('Disetujui Oleh\nDosen Wali') * 12; // Menggunakan font size 12
        var disahkanPositionX = qrPositionX + qrWidth + 40; // Tentukan posisi awal
        var disahkanPositionY = qrPositionY + 120; // Tentukan posisi vertikal yang sesuai
        var dosenWaliName = '{{ isset($data["wali"][0]->nama) ? $data["wali"][0]->nama : "" }} '; // Ambil nama Dosen Wali dari data
        var dosenWaliText = 'Disetujui Oleh\nDosen Wali';
        var qrDosenWaliPositionX = disahkanPositionX + 110; // Sesuaikan dengan posisi yang diinginkan
        var qrDosenWaliPositionY = qrPositionY + 140; // Menempatkannya di halaman baru

        doc.text(disahkanText, qrPositionX, disahkanPositionY); // Menambahkan teks "Disahkan Oleh Ketua Program Studi"
        doc.text(kaprodiName, qrPositionX, qrPositionY + 210); // Menambahkan nama Kaprodi di atas QR Code
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
        var idDosenWali = '{{ isset($data["wali"][0]->iddosen) ? $data["wali"][0]->iddosen : "" }}'; // Ambil ID dosen wali dari data
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
