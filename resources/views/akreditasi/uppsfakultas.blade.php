@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">UPPS FAKULTAS</h4>
                    <form class="row g-5" action="{{ route('uppsFakultas') }}" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <label for="idfakultas" class="form-label">Fakultas</label>
                            <select class="form-select" id="idfakultas" name="idfakultas" aria-label="Default select example">
                                <option value="" disabled {{ empty($idfakultas) ? 'selected' : '' }}>Pilih Fakultas...</option>
                                @foreach($allFakultas as $hasil)
                                <option value="{{ $hasil->idfakultas }}" data-fakultas="{{ $hasil->fakultas }}" {{ isset($idfakultas) && $hasil->idfakultas == $idfakultas ? 'selected' : '' }}>
                                    {{ $hasil->idfakultas }} - {{ $hasil->fakultas }}
                                </option>
                                
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" placeholder="fakultas" value="{{ old('fakultas', $fakultas ?? '') }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="ta">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta', $ta ?? '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($data) && count($data['prodiList']) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title"></h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>Program</th>
                                    <th>Prodi</th>
                                    <th>Akreditasi</th>
                                    <th>Tanggal Izin</th>
                                    <th>Tanggal Kadaluarsa</th>
                                    <th>Nomor SK</th>
                                    <th>Jumlah Lulus</th>
                                    <th>Jumlah Total</th>
                                    <th>Jumlah Dosen</th>
                                    <th>Rerata Masa Studi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['prodiList'] as $result)
                                <tr>
                                    <td>{{ $result->GelarPanjang }}</td>
                                    <td>{{ $result->prodi }}</td>
                                    <td>{{ $result->akreditasi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($result->izinTgl)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($result->tglkadaluarsa)->format('d-m-Y') }}</td>
                                    <td>{{ $result->NoAkreditasiProdi }}</td>
                                    <td>{{ $data['countLulusByProdi'][$result->prodi] }}</td>
                                    <td>{{ $data['countTotalByProdi'][$result->prodi] }}</td>
                                    <td>{{ $data['countDosenByProdi'][$result->prodi] }}</td>
                                    <td>{{ isset($data['averageStudyPeriods'][$result->prodi]) ? number_format($data['averageStudyPeriods'][$result->prodi]->rata_rata, 2) : '0' }}</td> <!-- Display average study period -->
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    jQuery(document).ready(function ($) {
        $('#idfakultas').change(function () {
            var idfakultas = $(this).find(':selected').val();
            var fakultas = $(this).find(':selected').data('fakultas');
            $("#fakultas").val(fakultas);
        });

        // Set fakultas value on page load if idfakultas is selected
        var selectedFakultas = $('#idfakultas').find(':selected').data('fakultas');
        if (selectedFakultas) {
            $("#fakultas").val(selectedFakultas);
        }

        
    });
    window.jsPDF = window.jspdf.jsPDF;
    function downloadPDF() {
    var doc = new jsPDF('l', 'pt', 'a4');

    // Set font size for the document
    doc.setFontSize(15);


    // Menambahkan informasi TA, Semester, stambuk, prodi, idkampus, dan lokasi
    var ta = "{{ session('ta') }}";
    var idfakultas = "{{ session('idfakultas') }}";
    var fakultas = "{{ session('fakultas') }}";

    // Menambahkan teks informasi ke dokumen PDF
    doc.setFontSize(15);
    doc.text('Universitas Quality', 10, 20);
    doc.text('Tabel Data Unit Pengelola Program Studi', 10, 40);
    doc.setFontSize(10);
    doc.text(`Fakultas: ${fakultas}`, 10, 60);
    doc.text(`TA: ${ta}`, 10, 80);


    // Menyimpan data tabel ke dalam array
    var data = [];
    var headers = ['Prodi', 'Akreditasi', 'Tanggal Izin', 'Tanggal Kadaluarsa', 'NO SK', 'Jlh Lulus','Jlh Mahasiswa','Jlh Dosen','Rerata Masa Studi'];
    @if(isset($data) && count($data['prodiList']) > 0)
    @foreach ($data['prodiList'] as $result)
        data.push([
            "{{ $result->prodi }}",
            "{{ $result->akreditasi }}",
            "{{ \Carbon\Carbon::parse($result->izinTgl)->format('d-m-Y') }}",
            "{{ \Carbon\Carbon::parse($result->tglkadaluarsa)->format('d-m-Y') }}",
            "{{ $result->NoAkreditasiProdi }}",
            "{{ $data['countLulusByProdi'][$result->prodi] }}",
            "{{ $data['countTotalByProdi'][$result->prodi] }}",
            "{{ $data['countDosenByProdi'][$result->prodi] }}",
            "{{ isset($data['averageStudyPeriods'][$result->prodi]) ? number_format($data['averageStudyPeriods'][$result->prodi]->rata_rata, 2) : '0' }}"
        ]);
    @endforeach
    @endif
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
const fileName = `UPPS_${fakultas}_TA${ta}_${formattedDate}.pdf`;
    doc.save(fileName);

}
function downloadExcel() {
        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "UPPS Fakultas Data",
            Subject: "Data",
            Author: "Universitas Quality",
            CreatedDate: new Date()
        };
        wb.SheetNames.push("Data");

        var ws_data = [['Prodi', 'Akreditasi', 'Tanggal Izin', 'Tanggal Kadaluarsa', 'Nomor SK', 'Jumlah Lulus', 'Jumlah Mahasiswa', 'Jumlah Dosen', 'Rerata Masa Studi']];
        @if(isset($data) && count($data['prodiList']) > 0)
        @foreach ($data['prodiList'] as $result)
            ws_data.push([
                "{{ $result->prodi }}",
                "{{ $result->akreditasi == 'Baik' ? 'B' : $result->akreditasi }}",
                "{{ \Carbon\Carbon::parse($result->izinTgl)->format('d-m-Y') }}",
                "{{ \Carbon\Carbon::parse($result->tglkadaluarsa)->format('d-m-Y') }}",
                "{{ $result->NoAkreditasiProdi }}",
                "{{ $data['countLulusByProdi'][$result->prodi] }}",
                "{{ $data['countTotalByProdi'][$result->prodi] }}",
                "{{ $data['countDosenByProdi'][$result->prodi] }}",
                "{{ isset($data['averageStudyPeriods'][$result->prodi]) ? number_format($data['averageStudyPeriods'][$result->prodi]->rata_rata, 2) : '0' }}"
            ]);
        @endforeach
        @endif

        var ws = XLSX.utils.aoa_to_sheet(ws_data);
        wb.Sheets["Data"] = ws;
        var ta = "{{ session('ta') }}";
    var idfakultas = "{{ session('idfakultas') }}";
    var fakultas = "{{ session('fakultas') }}";
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const fileName = `UPPS_${fakultas}_TA${ta}_${formattedDate}.xlsx`;

        XLSX.writeFile(wb, fileName);
    }
</script>
@endsection
