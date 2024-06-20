@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Cetak UPPS</h4>
                    <form class="row g-5" action="{{ route('viewUPPS') }}" method="POST">
                        @csrf

                        <div class="col-md-4">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <!--<div class="col-md-4">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-select" id="program" name="program" required>
                                <option value="">Choose .....</option>
                                <option value="S1"{{ old('program', isset($program) ? $program : '') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2">S2</option>
                            </select>
                        </div>-->
                        <div class="col-md-4">
                            <label for="ta" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta', isset($ta) ? $ta : '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
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
                    <h6 class="card-title">Data Table</h6>
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

<!-- Your custom JavaScript to load and display PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script>
    window.jsPDF = window.jspdf.jsPDF;

    function downloadPDF() {
        var doc = new jsPDF('l', 'pt', 'a4');
        doc.setFontSize(15);

        var ta = "{{ session('ta') }}";
        var universitas = "{{ session('universitas') }}";
        var lokasiText = universitas === 'UQM' ? 'Medan' : (universitas === 'UQB' ? 'Berastagi' : '');

        doc.setFontSize(15);
        doc.text(`Universitas Quality : ${lokasiText}`, 10, 20);
        doc.text('Data Unit Pengelola Program Studi', 10, 40);
        doc.setFontSize(10);
        doc.text(`TA: ${ta}`, 10, 60);

        var data = [];
        var headers = ['Program', 'Prodi', 'Akreditasi', 'Tgl Izin', 'Tgl Kadaluarsa', 'NoAkreditasi','Mahasiswa Lulus', 'Jlh Mahasiswa', 'Jlh Dosen', 'Rerata Masa Studi'];

        @if(isset($data) && count($data['prodiList']) > 0)
            @foreach ($data['prodiList'] as $result)
                data.push([
                    "{{ $result->GelarPanjang }}",
                    "{{ $result->prodi }}",
                    "{{ in_array($result->akreditasi, ['Baik', 'Baik Sekali']) ? 'B' : $result->akreditasi }}",
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
        const fileName = `UPPS_${universitas}_TA${ta}_${formattedDate}.pdf`;
        doc.save(fileName);
    }

    function downloadExcel() {
        var ta = "{{ session('ta') }}";
        var universitas = "{{ session('universitas') }}";

        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "Jumlah Mahasiswa Reguler",
            Subject: "Data Mahasiswa",
            Author: "Universitas Quality",
            CreatedDate: new Date()
        };
        wb.SheetNames.push("Data");

        var ws_data = [['Program', 'Prodi', 'Akreditasi', 'Tgl Izin', 'Tgl Kadaluarsa', 'NoAkreditasi','Mahasiswa Lulus', 'Jlh Mahasiswa', 'Jlh Dosen', 'Rerata Masa Studi']];

        @if(isset($data) && count($data['prodiList']) > 0)
            @foreach ($data['prodiList'] as $result)
                ws_data.push([
                    "{{ $result->GelarPanjang }}",
                    "{{ $result->prodi }}",
                    "{{ in_array($result->akreditasi, ['Baik', 'Baik Sekali']) ? 'B' : $result->akreditasi }}",
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

        var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }

        var blob = new Blob([s2ab(wbout)], { type: "application/octet-stream" });
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `UPPS_${universitas}_TA${ta}.xlsx`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endsection
