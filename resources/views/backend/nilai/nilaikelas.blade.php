
@extends('admin.dashboard')
@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showNilai') }}">Form</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cetak.nilai') }}">Nilai Mahasiswa</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Nilai Mahasiswa /Kelas</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>

                    <div class="table-responsive">
                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                        <table id="dataTableExample" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Nilai Absen</th>
                                    <th>Nilai Tugas</th>
                                    <th>Nilai Mid</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                    <th>Nilai Huruf</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                        
                                @foreach($result as $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data->NPM }}</td>
                                        <td>{{ $data->Nama }}</td>
                                        <td>{{ $data->NilaiAbsen }}</td>
                                        <td>{{ $data->NilaiTugas }}</td>
                                        <td>{{ $data->NilaiMid }}</td>
                                        <td>{{ $data->NilaiUAS }}</td>
                                        <td>{{ $data->NilaiAkhir }}</td>
                                        <td>{{ $data->NilaiHuruf }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Notifikasi --}}
                
                      @if(isset($jumlahTidakSesuai) && $jumlahTidakSesuai !== 0)
                      <div class="alert alert-danger">
                          Terdapat {{ $jumlahTidakSesuai }} mahasiswa yang belum diberi nilai:
                          <ul>
                              @foreach($mahasiswaBelumDiberiNilai as $mahasiswa)
                                  <li>{{ $mahasiswa->NPM }} - {{ $mahasiswa->Nama }}</li>
                              @endforeach
                          </ul>
                      </div>
                      @else
                      <div class="alert alert-success">
                          Semua Mahasiswa sudah diberi nilai.
                      </div>
                  @endif
                      
                    <div class ="row">
                        <div class ="col-md-6">
                            <div class="mb-3">
                    <button id="downloadExcel" class="btn btn-primary">Download Excel</button></div></div>
                    <div class="col-md-6">
                        <div class="mb-3">
                <button id="downloadPdf" class="btn btn-primary">Download PDF</button></div></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
<!-- Include toastr CSS and JS files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    toastr.options = {
        "positionClass": "toast-top-right",
    }
    document.getElementById('downloadExcel').addEventListener('click', function () {
    // Get the URL parameters
    var urlParams = new URLSearchParams(window.location.search);
    var kelas = urlParams.get('kelas') || '';

    // Get the user input values from session (make sure the session key matches)
    var prodi = "{{ session('prodi') }}";
    var kelas = "{{ session('kelas') }}";
    var matakuliah = "{{ session('matakuliah') }}"; // Make sure this matches your session key
    var ta = "{{ session('ta') }}";
    var nama = "{{ session('nama') }}";
    var iddosen = "{{ session('iddosen') }}";
    var tglUAS = "{{ session('tglUAS') }}";
    var semester = "{{ session('semester') }}";
    var idKampus = "{{ session('idKampus') }}";
    var Lokasi ="{{ session('Lokasi') }}";
    console.log('kelas:', kelas);
    console.log('idMK:', idMK);
    console.log('idKampus:', idKampus);
    // Create a new workbook
    var wb = XLSX.utils.book_new();

    // Create a new worksheet
    var ws = XLSX.utils.table_to_sheet(document.getElementById('dataTableExample'));

    // Set column width manually (adjust the value as needed)
    var columnCount = 13; // Adjust based on the number of columns you want to display
    ws['!cols'] = Array.from({ length: columnCount }, () => ({ wch: 30 })); // Set the width to 30 characters (adjust as needed)

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'AllData');

    // Create a dynamic filename based on user input
    var filenameExcel = prodi + '_TA:' + ta + '_Semester:' + semester +'_Dosen:' + nama +'_IDMK:' 
    + idMK +'_Kelas:' + kelas + '_TGLUAS:' + tglUAS +  '.xlsx';

    // Save the workbook as an Excel file with the dynamic filename
    XLSX.writeFile(wb, filenameExcel);
    });


    //PDF
    window.jsPDF = window.jspdf.jsPDF;
    window.jsPDF = window.jspdf.jsPDF;
document.getElementById('downloadPdf').addEventListener('click', function () {
    // Create a new jsPDF instance
    var urlParams = new URLSearchParams(window.location.search);
    var kelas = urlParams.get('kelas') || '';
    var prodi = "{{ session('prodi') }}";
    var ta = "{{ session('ta') }}";
    var nama = "{{ session('nama') }}";
    var iddosen = "{{ session('iddosen') }}";
    var MataKuliah = "{{ session('matakuliah') }}";
    var semester = "{{ session('semester') }}";
    var idKampus = "{{ session('idKampus') }}"; // Assuming this is the session variable for campus ID
    var Lokasi ="{{ session('Lokasi') }}";
    // Create a new jsPDF instance
    var pdf = new jsPDF({
        format: 'a4',
        orientation: 'landscape',
    });
    
    // Set the title
    pdf.setFontSize(16);
    pdf.text("Daftar Nilai Mahasiswa / Kelas", 10, 20);
    pdf.setFontSize(14);
    pdf.text("Universitas Quality", 10, 30);
    pdf.text("Dosen: " + nama +"(" + iddosen +")", 10, 40);
    // Set the information section
    pdf.setFontSize(10);
    pdf.text("ID Kampus / Lokasi : " + idKampus + "/" + Lokasi, 140, 20);
    pdf.text("TA/Semester: " + ta + "/" + semester, 250, 20);
    pdf.text("Program Studi: " + prodi, 250, 30);
    pdf.text("Mata Kuliah: " + MataKuliah, 140, 30);
    pdf.setFontSize(14);
    // Get the table element
    var table = document.getElementById('dataTableExample');
    
    // Check if kelas is not undefined or empty
    if (kelas) {
        // Set the header row as a title for the PDF with kelas
        pdf.text(10, 60, 'Table Data for Nilai Kelas ' + kelas);
    } else {
        // Set the header row as a title for the PDF without kelas
        pdf.text(10, 70, 'Table Data for Nilai Kelas');
    }

    // Convert the table to a PDF
    pdf.autoTable({
        html: table,
        startY: 70 // Adjust startY value to leave space for added information
    });

    // Create a dynamic filename based on user input
    var idMK = "{{ session('idMK') }}";
    var tglUAS = "{{ session('tglUAS') }}";
    var namadosen = "{{ session('namadosen') }}";
    var filenamePdf = prodi + '_TA:' + ta + '_Semester:' + semester + '_Dosen:' + nama + '_IDMK:' +
        idMK + '_Kelas:' + kelas + '_TGLUAS:' + tglUAS + '.pdf';
    // Save the PDF with the dynamic filename
    pdf.save(filenamePdf);
});

</script>


@endsection
