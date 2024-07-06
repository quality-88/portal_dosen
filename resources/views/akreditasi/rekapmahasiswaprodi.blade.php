@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Rekap Mahasiswa Aktif /Prodi</h4>
                    <form class="row g-5" action="{{ route('viewRekapProdi') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose Prodi...</option>
                                @foreach($prodis as $pilihanProdi)
                                    <option value="{{ $pilihanProdi->prodi }}" {{ old('prodi', isset($prodi) ? $prodi : '') == $pilihanProdi->prodi ? 'selected' : '' }}>{{ $pilihanProdi->prodi }}</option>
                                @endforeach
                            </select>                            
                        </div>
                        <div class="col-md-4">
                            <label for="ta_mulai" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta_mulai" name="ta_mulai" value="{{ old('ta_mulai', isset($ta_start) ? $ta_start : '') }}" required>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table">
                            <thead>
                                <tr>
                                    <th>Angkatan</th>
                                    <th>Jumlah Mahasiswa Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->Angkatan }}</td>
                                    <td>{{ $result->JumlahMahasiswa }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td>{{ $results->sum('JumlahMahasiswa') }}</td>
                                </tr>
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

<!-- Include the necessary scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script>
     @if(isset($results))
    window.jsPDF = window.jspdf.jsPDF;

    function downloadPDF() {
        var doc = new jsPDF('l', 'pt', 'a4');
        doc.setFontSize(15);

        const prodi = '{{ session('prodi') }}';
        const ta_start = '{{ session('ta_start') }}';
        const universitas = '{{ session('universitas') }}';
 

        doc.setFontSize(20);
        var lokasiText = universitas === 'UQM' ? 'Medan' : (universitas === 'UQB' ? 'Berastagi' : '');
        doc.text(`Universitas Quality : ${lokasiText}`, 20, 40);
        doc.text(`Rekap Jumlah Mahasiswa: ${prodi}`, 20, 80);
        doc.setFontSize(10);
        doc.text(`TA: ${ta_start} - ${ta_end}`, 20, 120);

        var headers = [['Angkatan', 'Jumlah Mahasiswa Aktif']];
        var data = [
            @foreach($results as $result)
            ['{{ $result->Angkatan }}', '{{ $result->JumlahMahasiswa }}'],
            @endforeach
            ['Total', '{{ $results->sum('JumlahMahasiswa') }}']
        ];

        var startY = 160;
        doc.autoTable({
            head: headers,
            body: data,
            startY: startY
        });

        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);

        const fileName = `Rekap Keseluruhan Mahasiswa_${universitas}_TA${ta_start}-${ta_end}_${prodi}_${formattedDate}.pdf`;
        doc.save(fileName);
    }

    function downloadExcel() {
        const prodi = '{{ session('prodi') }}';
        const ta_start = '{{ session('ta_start') }}';
        const universitas = '{{ session('universitas') }}';
        const ta_end = '2024'; // Replace with the actual end year if available

        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "Rekap Keseluruhan Mahasiswa",
            Subject: "Data Mahasiswa",
            Author: "Universitas Quality",
            CreatedDate: new Date()
        };
        wb.SheetNames.push("Data");

        var ws_data = [
            ['Angkatan', 'Total Jumlah Mahasiswa'],
            @foreach($results as $result)
            ['{{ $result->Angkatan }}', {{ $result->JumlahMahasiswa }}],
            @endforeach
            ['Total', {{ $results->sum('JumlahMahasiswa') }}]
        ];

        var ws = XLSX.utils.aoa_to_sheet(ws_data);
        wb.Sheets["Data"] = ws;

        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const fileName = `Rekap Keseluruhan Mahasiswa_${universitas}_${prodi}_TA${ta_start}-${ta_end}_${formattedDate}.xlsx`;

        XLSX.writeFile(wb, fileName);
    }
    @endif
</script>

@endsection
