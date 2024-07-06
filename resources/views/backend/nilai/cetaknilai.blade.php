@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('showNilai') }}">Kembali</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nilai Mahasiswa</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    
                    <div class="table-responsive">
                        <table id="dataNilai" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Lokasi</th>
                                    <th>ID Dosen</th>
                                    <th>Nama Dosen</th>
                                    <th>IDMK</th>
                                    <th>Mata Kuliah</th>
                                    <th>Kelas</th>
                                    <th>Blm dinilai</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $key => $result)
                                <tr>
                                    <td>{{ $result->Lokasi }}</td>
                                    <td>{{ $result->IDDosen }}</td>
                                    <td>{{ $result->NamaDosen }}</td>
                                    <td>{{ $result->IdMK }}</td>
                                    <td>{{ $result->MataKuliah }}</td>
                                    <td>{{ $result->Kelas }}</td>
                                    <td>
                                        @if($differences[$key]['Difference'] == 0)
                                            <span>0</span> <!-- Tanda centang unicode -->
                                        @else
                                            {{ $differences[$key]['Difference'] }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="downloadExcel" class="btn btn-primary">Download Excel</button>
</div>
<style>
    /* Tambahkan gaya kursor pointer saat mengarahkan kursor ke baris tabel */
    #dataNilai tbody tr:hover {
        cursor: pointer;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    document.getElementById('downloadExcel').addEventListener('click', function () {
        var prodi = "{{ session('prodi') }}";
        var ta = "{{ session('ta') }}";
        var semester = "{{ session('semester') }}";
        var wb = XLSX.utils.book_new();
  // Create a new worksheet
  var ws = XLSX.utils.table_to_sheet(document.getElementById('dataNilai'));
  // Set column width manually (adjust the value as needed)
  var columnCount = 13; // Adjust based on the number of columns you want to display
  ws['!cols'] = Array.from({ length: columnCount }, () => ({ wch: 30 })); // Set the width to 30 characters (adjust as needed)
  // Append the worksheet to the workbook
  XLSX.utils.book_append_sheet(wb, ws, 'AllData')
        var filename = prodi + '_TA' + ta + '_Semester' + semester + '_Prodi'+prodi+ '_table_data.xlsx';
        XLSX.writeFile(wb, filename);
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Add click event listener to each table row
        document.querySelectorAll('#dataNilai tbody tr').forEach(function(row) {
            row.addEventListener('click', function() {
                // Get data from the clicked row
                var Lokasi = row.cells[0].textContent.trim();
                var idMK = row.cells[3].textContent.trim();
                var nama = row.cells[2].textContent.trim();
                var iddosen = row.cells[1].textContent.trim();
                var matakuliah = row.cells[4].textContent.trim();
                var kelas = row.cells[5].textContent.trim();
                var idKampus = "{{ session('idKampus') }}";
                var prodi = "{{ session('prodi') }}";
                
                var ta = "{{ session('ta') }}";
                var semester = "{{ session('semester') }}";
                // Convert dd/mm/yyyy to yyyy-mm-dd
                //var tglUAS = convertDateFormat(row.cells[2].textContent.trim());

                // Redirect to nilaikelas.blade.php with parameters
                var url = "{{ route('nilaikelas') }}";
               
// Assuming 'idDosen', 'idMK', 'kelas', 'idKampus', 'prodi', and 'tglUAS' are your parameters
var parameters = {
    iddosen : iddosen,
    nama : nama,
    matakuliah : matakuliah,
    idMK: idMK,
    kelas: kelas,
    idKampus: idKampus,
    prodi: prodi,
    Lokasi : Lokasi
    //tglUAS: tglUAS
};

// Redirect to the route with parameters
window.location.href = url + '?' + new URLSearchParams(parameters).toString();

            });
        });
    });

    // Function to convert date format from dd/mm/yyyy to yyyy-mm-dd
    function convertDateFormat(inputDate) {
        if (inputDate === 'undefined') {
            // Handle undefined date
            return null; // or any default value you prefer
        } else {
            var parts = inputDate.split("/");
            return parts[2] + "-" + parts[1] + "-" + parts[0];
        }
    }
</script>

@endsection
