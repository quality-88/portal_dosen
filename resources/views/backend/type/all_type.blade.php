@extends('admin.dashboard')
@section('admin')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('showFormData')}}">Form</a></li>
            <li class="breadcrumb-item active" aria-current="page">Honor Dosen SKS</li>
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
                                        <th>Id Dosen</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>IDMK</th>
                                        <th>MataKuliah</th>
                                        <th>sks</th>
                                        <th>masuk</th>
                                        <th>keluar</th>
                                        <th>kelas</th>
                                        <th>jlh_mhs</th>
                                        <th>pertemuan</th>
                                        <th>honor</th>
                                        <th>keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentName = null;
                                        $totalHonorForName = 0;
                                    @endphp

                                    @foreach ($results as $result)
                                        @php
                                            // Check if the name has changed                        
                                            if ($result->namadosen !== $currentName) {
                                            // If yes, print the total honor for the previous name and reset the total honor
                                            if ($currentName !== null) {
                                             echo "<tr><td colspan=\"4\"></td><td>Total Honor  " .
                                            number_format($totalHonorForName, 0, ',', '.') . "
                                           </td><td><button onclick=\"bayar('$currentName', $totalHonorForName); this.disabled=true;\" class='btn btn-success'>Bayar</button>
                                               </td></tr>";
                                            }
                                            $currentName = $result->namadosen;
                                            $totalHonorForName = 0;
                                            }
                                            // Add the honor for the current row to the total honor
                                            $totalHonorForName += intval(floatval($result->honorSKSDosen));
                                        @endphp
                                        <tr>
                                            <td>{{ $result->iddosen }}</td>
                                            <td>{{ $result->namadosen }}</td>
                                            <td>{{ (new DateTime($result->tglin))->format('d/m/Y') }}</td>
                                            <td>{{ $result->idmk }}</td>
                                            <td>{{ $result->matakuliah }}</td>
                                            <td>{{ $result->sks }}</td>
                                            <td>{{ $result->masuk }}</td>
                                            <td>{{ $result->keluar }}</td>
                                            <td>{{ $result->kelas }}</td>
                                            <td>{{ $result->jumlah }}</td>
                                            <td>{{ $result->pertemuanke }}</td>
                                            <td>{{ number_format(intval(floatval($result->honorSKSDosen)), 0, ',', '.') }}</td>
                                            <td>{{ $result->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                    @if (!empty($currentName))
                                    <tr>
                                        <td colspan="4"></td>
                                        <td>Total Honor for :</td>
                                        <td>{{ number_format($totalHonorForName, 0, ',', '.') }}</td>
                                        <td> <button onclick="bayar('{{ $currentName }}', {{ $totalHonorForName }})" class="btn btn-success">Bayar</button></td>
                                    </tr>
                                @endif                                
                                </tbody>
                            </table>
                        @else
                            <p>No results found.</p>
                            @php $totalHonor = 0; @endphp
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="btnBayarSemua" type="button" class="btn btn-danger mb-2 mb-md-0" onclick="bayarSemua()">Bayar Semua</button>
                        </div>
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
<!-- Add this style to override DataTables styles -->
<script>
    function bayar(namaDosen, totalHonor) {
        const TA = '{{ session('TA') }}';
        const semester = '{{ session('Semester') }}';
    var rows = [];
    $('#myExportableTable tbody tr').each(function() {
        var namaDosenRow = $(this).find('td:eq(1)').text();
        if (namaDosenRow === namaDosen) {
            var rowData = {
                nama_dosen: $(this).find('td:eq(1)').text().trim(),
                id_dosen: $(this).find('td:eq(0)').text().trim(),
                tanggal: formatDate($(this).find('td:eq(2)').text().trim()), // Format tanggal
                id_mk: $(this).find('td:eq(3)').text().trim(),
                matakuliah: $(this).find('td:eq(4)').text().trim(),
                sks: $(this).find('td:eq(5)').text().trim(),
                masuk: $(this).find('td:eq(6)').text().trim(),
                keluar: $(this).find('td:eq(7)').text().trim(),
                kelas: $(this).find('td:eq(8)').text().trim(),
                jumlah_mahasiswa: $(this).find('td:eq(9)').text().trim(),
                pertemuan_ke: $(this).find('td:eq(10)').text().trim(),
                honor: $(this).find('td:eq(11)').text().trim(),
                keterangan: $(this).find('td:eq(12)').text().trim(),
                TA: TA, // Menambahkan nilai TA
                Semester: semester // Menambahkan nilai Semester
            };
            rows.push(rowData);
        }
    });
    console.log('Data yang akan dikirimkan:', rows);
    
    console.log('semester:', semester);
    console.log('TA:', TA);

// Kirim permintaan AJAX ke endpoint server untuk menyimpan data pembayaran
$.ajax({
    method: 'POST',
    url: 'formHonorDosen/endpoint-pembayaran',
    data: {
        data: rows, // Kirim semua baris yang sesuai dengan nama dosen
        _token: '{{ csrf_token() }}'
    },
    success: function(response) {
        // Tampilkan pesan SweetAlert jika pembayaran berhasil
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.message
        });
        // Menonaktifkan tombol bayar setelah berhasil melakukan pembayaran
        $('#btnBayar').prop('disabled', true);
    },
    error: function(xhr, status, error) {
        // Tampilkan pesan SweetAlert jika terjadi kesalahan
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Spertinya sudah dibayar.'
        });
        console.error(xhr.responseText);
    }
});
}
function bayarSemua() {
    var allRows = [];
    $('#myExportableTable tbody tr').each(function() {
        var rowData = {
            nama_dosen: $(this).find('td:eq(1)').text().trim(),
            id_dosen: $(this).find('td:eq(0)').text().trim(),
            tanggal: formatDate($(this).find('td:eq(2)').text().trim()), // Format tanggal
            id_mk: $(this).find('td:eq(3)').text().trim(),
            matakuliah: $(this).find('td:eq(4)').text().trim(),
            sks: $(this).find('td:eq(5)').text().trim(),
            masuk: $(this).find('td:eq(6)').text().trim(),
            keluar: $(this).find('td:eq(7)').text().trim(),
            kelas: $(this).find('td:eq(8)').text().trim(),
            jumlah_mahasiswa: $(this).find('td:eq(9)').text().trim(),
            pertemuan_ke: $(this).find('td:eq(10)').text().trim(),
            honor: $(this).find('td:eq(11)').text().trim(),
            keterangan: $(this).find('td:eq(12)').text().trim()
        };
        allRows.push(rowData);
    });
    console.log('Data yang akan dikirimkan:', allRows);
    if (allRows.length > 0) {
        $.ajax({
            method: 'POST',
            url: 'formHonorDosen/bayar-semua',
            data: {
                data: allRows,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Tampilkan pesan SweetAlert jika pembayaran berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                });
                // Menonaktifkan tombol bayar setelah berhasil melakukan pembayaran
                $('#btnBayarSemua').prop('disabled', true);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan SweetAlert jika terjadi kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Semua pembayaran telah dilakukan sebelumnya.'
                });
                console.error(xhr.responseText);
            }
        });
    } else {
        // Tampilkan pesan SweetAlert jika tidak ada data yang dapat dibayarkan
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: 'Tidak ada data untuk dibayar.'
        });
    }
}

function formatDate(dateString) {
    // Format the date string as needed (adjust based on your date format)
    // For example, if your date format is "DD/MM/YYYY", you can split the string and reconstruct it as "YYYY-MM-DD"
    var parts = dateString.split('/');
    return parts[2] + '-' + parts[1] + '-' + parts[0]; // Assuming format is "DD/MM/YYYY"
}

function formatNumber(number) {
    // Format angka dengan menggunakan bilangan ribuan yang dipisahkan oleh titik
    return new Intl.NumberFormat('id-ID').format(number);
}
    var excelColumns = ['Nama', 'Tanggal', 'IDMK', 'MataKuliah', 'SKS', 'Masuk', 'Keluar', 'Kelas', 
                        'Jumlah Mahasiswa', 'Pertemuan ke', 'Honor', 'Keterangan'];

    function exportToExcel() {
        // Create a workbook
        var wb = XLSX.utils.book_new();

        // Initialize variables to track current name and total honor for the current name
        let currentName = null;
        let totalHonorForName = 0;
        let rows = [
            excelColumns // Use the column headers
        ];
        if (typeof excelColumns === 'undefined') {
        var excelColumns = ['Nama', 'Tanggal', 'IDMK', 'MataKuliah', 'SKS', 'Masuk', 'Keluar', 'Kelas', 
                              'Jumlah Mahasiswa', 'Pertemuan ke', 'Honor', 'Keterangan'];
    }
    
    // ... (your existing code)

// Iterate over the results and add rows to the Excel workbook
@foreach ($results as $result)
    // Check if the name has changed
    if ("{{ $result->namadosen }}" !== currentName) {
        // If yes, create a new sheet for the new professor's name
        if (currentName !== null) {
            // Add the last total honor row to the current sheet
            rows.push(["", "", "", "", "", "", "", "", "", "", "Total Honor", totalHonorForName]);
            // Create a worksheet for the current sheet
            var wsData = rows;
            var ws = XLSX.utils.aoa_to_sheet(wsData);
            ws['!cols'] = Array.from({ length: excelColumns }, () => ({ wch: 50 }));
            // Append the worksheet to the workbook with the professor's name as the sheet name
            XLSX.utils.book_append_sheet(wb, ws, currentName.substring(0, 31));
        }
        
        // Reset total honor and set the current name to the new professor's name
        currentName = "{{ $result->namadosen }}";
        totalHonorForName = 0;
        // Reset rows array for the new sheet
        rows = [
            excelColumns.slice(1) // Use the column headers without the 'Nama' column
        ];
    }

    // Add the row data to the array
    rows.push([
        "{{ (new DateTime($result->tglin))->format('d/m/Y') }}",
        "{{ $result->idmk }}",
        "{{ $result->matakuliah }}",
        "{{ $result->sks }}",
        "{{ $result->masuk }}",
        "{{ $result->keluar }}",
        "{{ $result->kelas }}",
        "{{ $result->jumlah }}",
        "{{ $result->pertemuanke }}",
        "{{ number_format(intval(floatval($result->honorSKSDosen)), 0, ',', '.') }}",
        "{{ $result->keterangan }}",
    ]);
    // Add the honor for the current row to the total honor
    totalHonorForName += parseInt("{{ $result->honorSKSDosen }}") || 0; // Handle NaN case
@endforeach

// Add the last total honor row and sheet to the Excel workbook
if (currentName !== null) {
    // Add the last total honor row to the current sheet
    rows.push(["", "", "", "", "", "", "", "", "",  "Total Honor", totalHonorForName]);
    // Create a worksheet for the last sheet
    var wsData = rows;
    var ws = XLSX.utils.aoa_to_sheet(wsData);
    ws['!cols'] = Array.from({ length: excelColumns.length }, () => ({ wch: 15 })); // Set the width to 15 characters (adjust as needed)

    // Append the worksheet to the workbook with the professor's name as the sheet name
    XLSX.utils.book_append_sheet(wb, ws, currentName.substring(0, 31));
}

// Save the Excel file
const currentDate = new Date();
const formattedDate = currentDate.toLocaleDateString('en-US');
const prodiName = '{{ session('prodi') }}';
const lokasi = '{{ session('lokasi') }}';
const fileName = `daftar honor dosen_${lokasi}_${prodiName}_${formattedDate}.xlsx`;
XLSX.writeFile(wb, fileName);

    }

    window.jsPDF = window.jspdf.jsPDF;

function downloadPDF() {
    
    // Retrieve session data for additional information
    const idKampus = '{{ session('idkampus') }}';
    const TA = '{{ session('TA') }}';
    const semester = '{{ session('Semester') }}';
    const fakultas = '{{ session('fakultas') }}';
    const prodi = '{{ session('prodi') }}';
    const lokasi = '{{ session('lokasi') }}';
    // Create a single instance of jsPDF
    const doc = new jsPDF('l', 'pt', 'a4');
    // Set font size for the document
    doc.setFontSize(15);

    // ... (remaining code for header and additional information)

    // Initialize an array to store tables for each professor
    let allTables = [];
    let currentName = null;
    let totalHonorForName = 0;
    let rows = [
        [ 'Tanggal', 'IDMK', 'MataKuliah', 'SKS', 'Masuk', 'Keluar', 'Kelas', 
        'Jumlah Mahasiswa', 'Pertemuan ke', 'Honor', 'Keterangan']
    ];
    // Iterate over the results and add rows to the PDF
    @foreach ($results as $result)
        // Check if the name has changed
        if ("{{ $result->namadosen }}" !== currentName) {
            // If yes, create a new table for the new professor's name
            if (currentName !== null) {
                // Convert totalHonorForName to integer and handle NaN case
                const totalHonorValue = isNaN(totalHonorForName) ? 0 : parseInt(totalHonorForName);
                // Add the last total honor row to the current table
                rows.push(["", "", "", "", "", "", "", "", "",  {content: `Total for ${currentName}:`, styles: {halign: 'justify '}}, {content: formatNumber(totalHonorValue), styles: {halign: 'justify '}}]);

                // Add the current table to the allTables array
                allTables.push({
                    name: currentName,
                    table: rows,
                });
            }

            // Reset total honor and set the current name to the new professor's name
            currentName = "{{ $result->namadosen }}";
            totalHonorForName = 0;

            // Reset rows array for the new table
            rows = [
                [ 'Tanggal', 'IDMK', 'MataKuliah', 'SKS', 'Masuk', 'Keluar', 'Kelas', 
                'Jumlah Mahasiswa', 'Pertemuan ke', 'Honor', 'Keterangan']
            ];
        }

        // Add the row data to the array
        rows.push([
            "{{ (new DateTime($result->tglin))->format('d/m/Y') }}",
            "{{ $result->idmk }}",
            "{{ $result->matakuliah }}",
            "{{ $result->sks }}",
            "{{ $result->masuk }}",
            "{{ $result->keluar }}",
            "{{ $result->kelas }}",
            "{{ $result->jumlah }}",
            "{{ $result->pertemuanke }}",
            "{{ number_format(intval(floatval($result->honorSKSDosen)), 0, ',', '.') }}",
            "{{ $result->keterangan }}",
        ]);

        // Add the honor for the current row to the total honor
        totalHonorForName += parseInt("{{ $result->honorSKSDosen }}") || 0; // Handle NaN case
    @endforeach

    // Add the last total honor row and sheet to the Excel workbook
    if (currentName !== null) {
        // Convert totalHonorForName to integer and handle NaN case
        const totalHonorValue = isNaN(totalHonorForName) ? 0 : parseInt(totalHonorForName);
        rows.push(["", "", "", "", "", "", "", "", "", {content: `Total for ${currentName}:`, styles: {halign: 'justify '}}, {content: formatNumber(totalHonorValue), styles: {halign: 'justify '}}]);
        // Add the last table to the allTables array

        
        allTables.push({
            name: currentName,
            table: rows,
        });
    }

    // Function to format number as currency with comma separator
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Now 'allTables' array contains tables for each professor's name
    for (const tableData of allTables) {
        // Add a page break before each new section, except for the first section
        if (tableData !== allTables[0]) {
            doc.addPage();
        }
        
        // Customize the header or any additional information if needed
        doc.setFontSize(15);
        doc.text('Universitas Quality', 10, 20);
        doc.text('Daftar Honor Dosen', 10, 40);
        doc.setFontSize(8);
        doc.text(`ID Kampus: ${idKampus}/${lokasi}`, 500, 20);
        doc.text(`TA/Semester: ${TA}/${semester}`, 500, 40);
        doc.text(`Fakultas: ${fakultas}`, 650, 20);
        doc.text(`Program Studi: ${prodi}`, 650, 40);

        // Display professor's name after "Daftar Honor Dosen"
        doc.setFontSize(12);
        doc.text(`Nama Dosen: ${tableData.name}`, 10, 80);
        doc.setFontSize(10);

        

        // Add the table to the PDF
        doc.autoTable({
            body: tableData.table,
            startY: 100,
            margin: { top: 60 },
            tableWidth: 'auto',
            styles: {
                fontSize: 10,
                cellPadding: 6,
                valign: 'middle',
                halign: 'center',
            },
            alternateRowStyles: {
                fillColor: [193, 202, 237],
            },
        });
    }

    // Save the PDF file
    const currentDate = new Date();
    const formattedDate = currentDate.toLocaleDateString('en-US');
    const fileName = `daftar honor dosen_${lokasi}_${prodi}_${formattedDate}.pdf`;
    doc.save(fileName);
    
    // Selesai menghasilkan dan mengirimkan file PDF
// Refresh halaman setelah jeda singkat (misalnya, 1 detik)
//setTimeout(function(){
//    location.reload(true); // Parameter true memaksa pemuatan ulang dari server (bypass cache)
//}, 1000); // Ubah angka sesuai dengan kebutuhan jeda yang diinginkan
}


</script>

@endsection


