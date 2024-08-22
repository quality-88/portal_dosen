@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-0">Report Jadwal Kuliah</h4>
                            <hr class="my-4">
                            <form class="row g-3" action="{{ route('OrderbyReport') }}" method="POST">
                                @csrf
                                <div class="col-md-4">
                                    <label for="idkampus" class="form-label">ID Kampus</label>
                                    <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                        <option value="" disabled selected>Choose ID Kampus...</option>
                                        @foreach($allIdKampus as $data)
                                            <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}" {{ old('idkampus',$idkampus ?? '') == $data->idkampus ? 'selected' : '' }}>{{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" readonly value="{{ old('lokasi', $lokasi ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="prodi" class="form-label">Prodi</label>
                                    <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()" required>
                                        <option value="" disabled>Choose Prodi...</option>
                                        @foreach($allProdi as $data)
                                            <option value="{{ $data->prodi }}" {{ old('prodi', $prodi ??'')== $data->prodi ? 'selected' : '' }}>
                                                {{ $data->prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="id_fakultas" class="form-label">ID Fakultas</label>
                                    <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" readonly value="{{ old('id_fakultas', $idfakultas ?? '') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="fakultas" class="form-label">Fakultas</label>
                                    <input type="text" class="form-control" id="fakultas" name="fakultas" readonly value="{{ old('fakultas', $fakultas ?? '') }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="ta" class="form-label">TA</label>
                                    <input type="text" class="form-control" id="ta" name="ta" placeholder="TA" value="{{ old('ta', $ta ?? '') }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="1" {{ old('semester', $semester ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('semester', $semester ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('semester', $semester ?? '') == '3' ? 'selected' : '' }}>3</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="orderby" class="form-label">Order By</label>
                                    <select class="form-select" id="orderby" name="orderby">
                                        <option value="harijadwal" {{ old('orderby', $orderby ?? '') == 'harijadwal' ? 'selected' : '' }}>Hari</option>
                                        <option value="kelas" {{ old('orderby', $orderby ?? '') == 'kelas' ? 'selected' : '' }}>Kelas</option>
                                        <option value="idruang" {{ old('orderby', $orderby ?? '') == 'idruang' ? 'selected' : '' }}>Ruang</option>
                                        <option value="nama_dosen2" {{ old('orderby', $orderby ?? '') == 'nama_dosen2' ? 'selected' : '' }}>Dosen Pengajar</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($results))
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Report Jadwal Kuliah</h4>
                <div class="col-md-12">
                    <button class="btn btn-primary  mb-4" onclick="downloadpdf()">Download PDF</button>
                    <button class="btn btn-success  mb-4" onclick="downloadExcel()">Download Excel</button>
                </div>
                <hr class="my-4">
                @php
                    $groupedResults = $results->groupBy($orderby);
                    
                    // Define the day mapping
                    $dayMapping = [
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu'
                    ];
                @endphp

                @foreach($groupedResults as $groupKey => $groupResults)
                    <h5 class="mt-4">
                        @if($orderby == 'harijadwal')
                            {{ $dayMapping[$groupKey] ?? 'Unknown' }}
                        @else
                            {{ ucfirst($groupKey) }}
                        @endif
                    </h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Kurikulum</th>
                                    <th>ID MK</th>
                                    <th>Matakuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>JlhMhs</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Ruang</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Dosen Pengajar</th>
                                    <th>Dosen 1</th>
                                    <th>Dosen 2</th>
                                    <th>Gabungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupResults as $result)
                                    <tr>
                                        <td>
                                            @if($orderby == 'harijadwal')
                                                {{ $dayMapping[$result->harijadwal] ?? 'Unknown' }}
                                            @else
                                                {{ $result->hari }}
                                            @endif
                                        </td>
                                        <td>{{ $result->kurikulum }}</td>
                                        <td>{{ $result->idmk }}</td>
                                        <td>{{ $result->matakuliah }}</td>
                                        <td>{{ $result->sks }}</td>
                                        <td>{{ $result->kelas }}</td>
                                        <td>{{ $result->jumlah_mahasiswa }}</td>
                                        <td>{{ $result->jammasuk }}</td>
                                        <td>{{ $result->jamkeluar }}</td>
                                        <td>{{ $result->idruang }}</td>
                                        <td>{{ $result->dosen }}</td>
                                        <td>{{ $result->nama_dosen2 }}</td>
                                        <td>{{ $result->nama_dosen3 }}</td>
                                        <td>{{ $result->nama_dosen4 }}</td>
                                        <td>{{ $result->gabungan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
function updateFakultas() {
    var selectedProdi = $('#prodi').val();
    if (selectedProdi) {
        $.ajax({
            url: '{{ route("fetchFakultas") }}',
            method: 'GET',
            data: { prodi: selectedProdi },
            success: function (response) {
                if (response.no_data) {
                    alert('No data found for the selected Prodi. Please choose a different Prodi.');
                } else {
                    $('#id_fakultas').val(response.idfakultas);
                    $('#fakultas').val(response.fakultas);
                    
                }
            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
    } 
}

jQuery(document).ready(function ($) {
    // Initialize flatpickr for date fields
    flatpickr('#date', { dateFormat: 'Y-m-d' });
    flatpickr('#endDate', { dateFormat: 'Y-m-d' });
    // Event handlers
    $('#idkampus').change(function () {
        var idKampus = $(this).find(':selected').val();
        var lokasi = $(this).find(':selected').data('lokasi');
        $("#lokasi").val(lokasi);
    });
    $('#prodi').change(function () {
        updateFakultas();
    });
});
@if(isset($results))
window.jsPDF = window.jspdf.jsPDF;

function downloadpdf() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4');

    var ta = "{{ old('ta', $ta ?? '') }}";
    var semester = "{{ old('semester', $semester ?? '') }}";
    var prodi =  "{{ old('prodi', $prodi ?? '') }}";
    var idkampus = "{{ old('idkampus', $idkampus ?? '') }}";
    var lokasi = "{{ old('lokasi', $lokasi ?? '') }}";
    var fakultas = "{{ old('fakultas', $fakultas ?? '') }}";
    
    const tableColumns = [
        { title: "Hari", dataKey: "hari" },
        { title: "Kurikulum", dataKey: "kurikulum" },
        { title: "ID MK", dataKey: "idmk" },
        { title: "Matakuliah", dataKey: "matakuliah" },
        { title: "SKS", dataKey: "sks" },
        { title: "Kelas", dataKey: "kelas" },
        { title: "JlhMhs", dataKey: "jumlah_mahasiswa" },
        { title: "Masuk", dataKey: "jammasuk" },
        { title: "Keluar", dataKey: "jamkeluar" },
        { title: "Ruang", dataKey: "idruang" },
        { title: "Dosen Pengampu", dataKey: "dosen" },
        { title: "Dosen Pengajar", dataKey: "nama_dosen2" },
        { title: "Dosen 1", dataKey: "nama_dosen3" },
        { title: "Dosen 2", dataKey: "nama_dosen4" },
        { title: "Gabungan", dataKey: "gabungan" }
    ];

    // Define the day mapping
    const dayMapping = {
        1: 'Senin',
        2: 'Selasa',
        3: 'Rabu',
        4: 'Kamis',
        5: 'Jumat',
        6: 'Sabtu'
    };

    // Fetch the grouped results from the server side
    const groupedResults = <?php echo json_encode($groupedResults); ?>;
    var orderbyValue = "{{ old('orderby', $orderby ?? '') }}";

    // Use the orderbyValue variable to determine the label for the group
    let orderbyLabel = '';
    switch (orderbyValue) {
        case 'harijadwal':
            orderbyLabel = 'Hari';
            break;
        case 'kelas':
            orderbyLabel = 'Kelas';
            break;
        case 'idruang':
            orderbyLabel = 'Ruang';
            break;
        case 'nama_dosen2':
            orderbyLabel = 'Dosen Pengajar';
            break;
        default:
            orderbyLabel = 'Unknown';
    }

    Object.keys(groupedResults).forEach((groupKey, index) => {
        if (index > 0) doc.addPage(); // Add new page for each group except the first one

        // Map groupKey to day name if orderbyValue is 'harijadwal'
        let displayGroupKey = orderbyValue === 'harijadwal' ? dayMapping[groupKey] || 'Unknown' : groupKey;

        // Write group key as a header
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.text('Universitas Quality', 20, 40);
        doc.text('Report Jadwal Kelas Universitas Quality', 20, 60);
        doc.text(`Ditampilkan berdasarkan ${orderbyLabel}`, 20, 80);
        doc.setFontSize(8);
        doc.text(`ID Kampus: ${idkampus}/${lokasi}`, 500, 40);
        doc.text(`TA/Semester: ${ta}/${semester}`, 500, 60);
        doc.text(`Program Studi: ${prodi}`, 650, 60);
        doc.text(`Fakultas: ${fakultas}`, 650, 40);
        doc.setFontSize(10);
        doc.text(`${orderbyLabel}: ${displayGroupKey}`, 20, 100);

        // Adjust the start position for the table to avoid overlapping with the group key
        const startY = 120;

        // Write the table
        doc.autoTable(tableColumns, groupedResults[groupKey], {
            startY: startY,
            margin: { horizontal: 10 },
            styles: { fontSize: 8, lineWidth: 0.5, lineColor: [0, 0, 0], textColor: [0, 0, 0],
                halign: 'center', // Default horizontal alignment
                valign: 'middle', // Default vertical alignment
     },
            theme: 'grid',
            columnStyles: {
                0: { cellWidth: 40 }, // Hari
                1: { cellWidth: 40 }, // Kurikulum
                2: { cellWidth: 70 }, // ID MK
                3: { cellWidth: 100 }, // Matakuliah
                4: { cellWidth: 30 }, // SKS
                5: { cellWidth: 40 }, // Kelas
                6: { cellWidth: 40 }, // jlhmhs
                7: { cellWidth: 40 }, // jam masuk
                8: { cellWidth: 40 }, // jam keluar
                9: { cellWidth: 40 }, // Ruang
                10: { cellWidth: 80 }, // Dosen Pengampu
                11: { cellWidth: 80 }, // Dosen Pengajar
                12: { cellWidth: 60 }, // Dosen 1
                13: { cellWidth: 60 }, // Dosen 2
                14: { cellWidth: 50 }  // Gabungan
            },
            headStyles: { fillColor: [255, 255, 255], textColor: [0, 0, 0] }, // White header background, black text
            bodyStyles: { fillColor: [255, 255, 255] } // White body
        });

        // Move the cursor to the end of the table for potential next content
        const finalY = doc.autoTable.previous.finalY;
    });

    // Save the PDF
        doc.setFontSize(10);
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);
        const fileName = `JADWAL KELAS_${idkampus}_${lokasi}_${prodi}_${ta}_${formattedDate}.pdf`;
        doc.save(fileName);
}
function downloadExcel() {
    const groupedResults = <?php echo json_encode($groupedResults); ?>;
    const orderbyValue = "{{ old('orderby', $orderby ?? '') }}";
    var ta = "{{ old('ta', $ta ?? '') }}";
    var semester = "{{ old('semester', $semester ?? '') }}";
    var prodi =  "{{ old('prodi', $prodi ?? '') }}";
    var idkampus = "{{ old('idkampus', $idkampus ?? '') }}";
    var lokasi = "{{ old('lokasi', $lokasi ?? '') }}";
    var fakultas = "{{ old('fakultas', $fakultas ?? '') }}";
    // Function to truncate sheet names to a maximum of 31 characters
    function truncateSheetName(name) {
        return name.length > 31 ? name.substring(0, 28) + '...' : name;
    }

    // Use the orderbyValue variable to determine the label for the group
    let orderbyLabel = '';
    switch (orderbyValue) {
        case 'harijadwal':
            orderbyLabel = 'Hari';
            break;
        case 'kelas':
            orderbyLabel = 'Kelas';
            break;
        case 'idruang':
            orderbyLabel = 'Ruang';
            break;
        case 'nama_dosen2':
            orderbyLabel = 'Dosen Pengajar';
            break;
        default:
            orderbyLabel = 'Unknown';
    }

    // Create a new workbook
    const wb = XLSX.utils.book_new();

    // Iterate over each group and create a new sheet for each group
    Object.keys(groupedResults).forEach((groupKey, index) => {
        const sheetData = [];
        // Add headers to the sheet
        sheetData.push([
            "Hari",
            "Kurikulum",
            "ID MK",
            "Matakuliah",
            "SKS",
            "Kelas",
            "JlhMhs",
            "Dosen Pengampu",
            "Dosen Pengajar",
            "Dosen 1",
            "Dosen 2",
            "ID Ruang",
            "Gabungan"
        ]);

        // Add data rows to the sheet
        groupedResults[groupKey].forEach((row) => {
            sheetData.push([
                row.hari,
                row.kurikulum,
                row.idmk,
                row.matakuliah,
                row.sks,
                row.kelas,
                row.jumlah_mahasiswa,
                row.dosen,
                row.nama_dosen2,
                row.nama_dosen3,
                row.nama_dosen4,
                row.idruang,
                row.gabungan
            ]);
        });

        // Create a worksheet
        const ws = XLSX.utils.aoa_to_sheet(sheetData);
        // Truncate the sheet name if necessary
        let sheetName = truncateSheetName(`${orderbyLabel} - ${groupKey}`);

        // Ensure the sheet name is unique
        if (wb.SheetNames.includes(sheetName)) {
            let counter = 1;
            while (wb.SheetNames.includes(`${sheetName} (${counter})`)) {
                counter++;
            }
            sheetName = `${sheetName} (${counter})`;
        }

        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, sheetName);
    });
    const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const fileName = `JADWAL KULIAH ${idkampus}_${lokasi}_${prodi}_${ta}_${formattedDate}.xlsx`;
    // Generate and download the Excel file
    XLSX.writeFile(wb, fileName);
}
@endif
</script>
@endsection
