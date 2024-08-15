@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Rekap Absensi Dosen</h4>
                    <form class="row g-5" action="{{ route('rekapAbsensiDosen') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="idkampus" class="form-label">ID Kampus </label>
                            <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose ID Kampus...</option>
                                @foreach($allIdKampus as $data)
                                    <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}" {{ old('idkampus',$idkampus ?? '') == $data->idkampus ? 'selected' : '' }}>{{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" value="{{ old('lokasi',$lokasi ?? '') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example">
                                <option value="" disabled selected>Choose Prodi...</option>
                                @foreach($allProdi as $prodi)
                                @php
                                    $selected = session('prodi') == $prodi->prodi ? 'selected' : '';
                                @endphp
                                <option value="{{ $prodi->prodi }}" {{ $selected }}>{{ $prodi->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="ta" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta', $ta ?? '') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label for="semester" class="form-label">SEMESTER</label>
                            <select class="form-select" id="semester" name="semester" aria-label="Default select example" required>
                                <option value="1" {{ old('semester',$semester ?? '') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('semester',$semester ?? '') == '2' ? 'selected' : '' }}>2</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="startDate" class="form-label">Tanggal Mulai</label>
                            <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date" data-date-format="Y-m-d" value="{{ old('startDate', $startDate ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="endDate" class="form-label">Tanggal Akhir</label>
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
    @if(isset($pdfData) && count($pdfData) > 0)
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Absensi Results</h4>
                        <!-- Download All PDF button -->
                        <button class="btn btn-primary mb-4" onclick="downloadAllPDF()">Download All PDF</button>
                        <button class="btn btn-success mb-4" onclick="downloadAllExcel()">Download All Excel</button>

                        <div id="pdfContainer">
                            @foreach($pdfData as $data)
                                <div class="table-responsive mb-5">
                                    <h5>Nama Dosen: {{ $data['nama'] }}</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>ID Dosen</th>
                                                <th>IDMK</th>
                                                <th>Kelas</th>
                                                <th>Pertemuan</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach($data['attendances'] as $record)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $record->iddosen }}</td>
                                                <td>{{ $record->idmk }}</td>
                                                <td>{{ $record->kelas }}</td>
                                                <td>{{ $record->pertemuan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($record->tgl)->format('d-m-Y') }}</td>
                                            </tr>
                                        @endforeach
                                        
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr('#endDate', {
            dateFormat: 'Y-m-d'
        });
        flatpickr('#startDate', {
            dateFormat: 'Y-m-d'
        });
    });

    // Event handlers
    jQuery(document).ready(function ($) {
        $('#idkampus').change(function () {
            var idKampus = $(this).find(':selected').val();
            var lokasi = $(this).find(':selected').data('lokasi');
            $("#lokasi").val(lokasi);
        });
    });

    // Function to download all PDFs
    @if(isset($pdfData) && count($pdfData) > 0)
    window.jsPDF = window.jspdf.jsPDF;

    function downloadAllPDF() {
        var doc = new jsPDF('l', 'pt', 'a4');
        var ta = "{{ session('ta') }}";
        var semester = "{{ session('semester') }}";
        var prodi = "{{ session('prodi') }}";
        var idkampus = "{{ session('idkampus') }}";
        var lokasi = "{{ session('lokasi') }}";

        // Menambahkan teks informasi ke dokumen PDF
        doc.setFontSize(20);
        doc.text('Universitas Quality', 40, 40);
        doc.text('Rekap Absensi Dosen Universitas Quality', 40, 80);
        doc.setFontSize(10);
        doc.text(`ID Kampus: ${idkampus}/${lokasi}`, 500, 40);
        doc.text(`TA/Semester: ${ta}/${semester}`, 500, 60);
        doc.text(`Program Studi: ${prodi}`, 650, 60);
        
        var y = 120;

        @foreach($pdfData as $data)
            // Add page before each table except the first one
            @if(!$loop->first)
                doc.addPage();
                y = 120; // Reset y position for new page
            @endif
            doc.setFontSize(20);
            doc.text('Universitas Quality', 40, 40);
            doc.text('Rekap Absensi Dosen Universitas Quality', 40, 80);
            doc.setFontSize(10);
            doc.text(`ID Kampus: ${idkampus}/${lokasi}`, 500, 40);
            doc.text(`TA/Semester: ${ta}/${semester}`, 500, 60);
            doc.text(`Program Studi: ${prodi}`, 650, 60);
            doc.setFontSize(20);
            doc.text("Nama: {{ $data['nama'] }}", 40, y);
            y += 20;

            doc.autoTable({
                head: [['No', 'ID Dosen', 'IDMK', 'Kelas', 'Pertemuan', 'Tanggal']],
                body: [
                @foreach($data['attendances'] as $record)
                ['{{ $loop->iteration }}', '{{ $record->iddosen }}', '{{ $record->idmk }}', '{{ $record->kelas }}', '{{ $record->pertemuan }}', '{{ \Carbon\Carbon::parse($record->tgl)->format('d-m-Y') }}'],
                @endforeach

                ],
                startY: y,
                margin: { top: 50 }
            });

            y = doc.lastAutoTable.finalY + 10;
        @endforeach
        doc.setFontSize(10);
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);
        const fileName = `ABSENSI DOSEN /PRODI_${idkampus}_${lokasi}_${prodi}_${ta}_${formattedDate}.pdf`;
        doc.save(fileName);
    }

    function downloadAllExcel() {
        var wb = XLSX.utils.book_new();
        var idkampus = "{{ session('idkampus') }}";
        var lokasi = "{{ session('lokasi') }}";
        var prodi = "{{ session('prodi') }}";
        var ta = "{{ session('ta') }}";
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const fileName = `ABSENSI DOSEN /PRODI_${idkampus}_${lokasi}_${prodi}_${ta}_${formattedDate}.xlsx`;

        @foreach($pdfData as $data)
        var sheetName = '{{ $data['nama'] }}';
        if (sheetName.length > 31) {
                sheetName = sheetName.substring(0, 31); // Shorten to 31 characters
            }
            var attendances = [
                ['No', 'ID Dosen', 'IDMK', 'Kelas', 'Pertemuan', 'Tanggal']
                @foreach($data['attendances'] as $record)
                    ,['{{ $loop->iteration }}', '{{ $record->iddosen }}', '{{ $record->idmk }}', '{{ $record->kelas }}', '{{ $record->pertemuan }}',  '{{ \Carbon\Carbon::parse($record->tgl)->format('d-m-Y') }}'],
                @endforeach
            ];

            var ws = XLSX.utils.aoa_to_sheet(attendances);
            XLSX.utils.book_append_sheet(wb, ws, sheetName);
        @endforeach

        // Menghasilkan file Excel
        XLSX.writeFile(wb, fileName);
    }
    @endif
</script>
@endsection
