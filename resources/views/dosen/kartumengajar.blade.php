@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
     <!-- fourth Modal for showing the query results -->
     <div class="modal fade" id="honorModal" tabindex="-1" aria-labelledby="honorModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="honorModalLabel">Pilih Dosen Pengajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchBox2" class="form-control mb-3" placeholder="Search by Nama...">
                    <table class="table table-bordered" id="honorTable">
                        <thead>
                            <tr>
                                <th>ID Dosen</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Query results will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-0">Kartu Mengajar Dosen</h4>
                            <hr class="my-4">
                            <form class="row g-3" action="{{ route('viewKartuMengajar') }}" method="POST">
                                @csrf
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">ID Kampus</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                            <option value="" disabled selected>Choose ID Kampus...</option>
                                            @foreach($allIdKampus as $data)
                                                <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}" {{ old('idkampus',$idkampus ?? '') == $data->idkampus ? 'selected' : '' }}>{{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">Lokasi</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" readonly value="{{ old('lokasi', $lokasi ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">TA</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="ta" name="ta" placeholder="TA" value="{{ old('ta', $ta ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">Semester</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="semester" name="semester" placeholder="semester" value="{{ old('semester', $semester ?? '') }}" required>
                                    </div>
                                </div>
                               
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">ID Pengajar</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="idpengajar" name="iddosen" placeholder="ID Pengajar" required>
                                            <button type="button" class="btn btn-outline-primary" id="searchButton2">
                                               <i class="fas fa-search" data-feather="search"></i>
                                            </button>
                                            <ul id="resultsList" style="display: none;"></ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nama" name="nama"  readonly>
                                            <ul id="resultsList" style="display: none; overflow-y: auto;"></ul>
                                        </div>
                                    </div>
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
    @if(isset($jadwal) && count($jadwal) > 0)
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Kartu Mengajar</h4>
                    <div class="table-responsive">
                        <table class="table" id="kartuMengajarTable">
                            <thead>
                                <tr>
                                    <th>Prodi</th>
                                    <th>Kelas</th>
                                    <th>IDMK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Hari</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hariMapping = [
                                        1 => 'Senin',
                                        2 => 'Selasa',
                                        3 => 'Rabu',
                                        4 => 'Kamis',
                                        5 => 'Jumat',
                                        6 => 'Sabtu',
                                        7 => 'Minggu'
                                    ];
                                @endphp
                                @foreach($jadwal as $item)
                                <tr>
                                    <td>{{ $item->prodi }}</td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>{{ $item->idmk }}</td>
                                    <td>{{ $item->matakuliah }}</td>
                                    <td>{{ $item->sks }}</td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>{{ $item->jammasuk }}</td>
                                    <td>{{ $item->jamkeluar }}</td>
                                    <td>{{ $hariMapping[$item->harijadwal] }}</td>
                                    <td>{{ $item->peran_pengajar }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Total SKS</strong></td>
                                    <td><strong>{{ $totalSKS }}</strong></td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                    <!-- Tombol Download PDF -->
                    <button id="downloadPDF" class="btn btn-primary mt-4">Download PDF</button>
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
  jQuery(document).ready(function ($) {
           // Initialize flatpickr for date fields
       flatpickr('#date', { dateFormat: 'Y-m-d' });
       flatpickr('#endDate', { dateFormat: 'Y-m-d' });

       // Event handlers
       $('#idkampus').change(function () {
           var idKampus = $(this).find(':selected').val();
           var lokasi = $(this).find(':selected').data('lokasi');

           console.log('ID Kampus:', idKampus);
           console.log('Lokasi:', lokasi);

           $("#lokasi").val(lokasi);
       });


    });
    $(document).ready(function() {
    $('#searchButton2').on('click', function() {
        var prodi = $('#prodi').val(); // Ensure prodi is retrieved from the form

        $.ajax({
            url: '{{ route("getHonorSKS") }}',
            method: 'POST',
            data: {
                prodi: prodi,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var tbody = $('#honorTable tbody');
                tbody.empty();

                response.forEach(function(item) {
                    tbody.append(
                        '<tr data-idpengajar="' + item.idpengajar + '" data-nama="' + item.nama + '" data-honor="' + item.honor + '">' +
                        '<td>' + item.idpengajar + '</td>' +
                        '<td>' + item.nama + '</td>' +
                        '</tr>'
                    );
                });

                $('#honorModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

    $('#honorTable').on('click', 'tr', function() {
        var idpengajar = $(this).data('idpengajar');
        var nama = $(this).data('nama');

        $('#idpengajar').val(idpengajar);
        $('#nama').val(nama);

        $('#honorModal').modal('hide');
    });

    $('#searchBox2').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#honorTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
    window.jsPDF = window.jspdf.jsPDF;
    @if(isset($jadwal) && count($jadwal) > 0)
    @if(isset($results) && count($results) > 0)
    // Data results diubah menjadi JSON dan disimpan dalam variabel JavaScript
    var results = @json($results);
    function downloadPDF() {
    var doc = new jsPDF('l', 'pt', 'a4');
    var ta = "{{ session('ta') }}";
    var semester = "{{ session('semester') }}";
    var idkampus = "{{ session('idkampus') }}";
    var iddosen = "{{ session('iddosen') }}";
    var lokasi = "{{ session('lokasi') }}";

    doc.setFontSize(25);
    doc.text('Kartu Mengajar Dosen', 30, 40);
    doc.setFontSize(10);
    doc.text(`ID Kampus: ${idkampus}/${lokasi}`, 500, 20);
    doc.text(`TA/Semester: ${ta}/${semester}`, 500, 40);

    // Display information from results below the title
    var yPosition = 70; // Adjust Y position for displaying results below the title
    results.forEach(function(result) {
        doc.setFontSize(12);
        doc.text(`Nama: ${result.nama}`, 30, yPosition);
        doc.text(`NIDN: ${result.nidn}`, 30, yPosition + 20);
        doc.text(`Alamat: ${result.alamat}`, 30, yPosition + 40);
        doc.text(`HP: ${result.hp}`, 500, yPosition);
        doc.text(`Status Dosen: ${result.statusdosen}`, 500, yPosition + 20);
        doc.text(`Email Pribadi: ${result.emailpribadi}`, 500, yPosition + 40);
        yPosition += 70; // Add space between each lecturer's information
    });

    doc.setFontSize(10);
    // Save table data into an array
    var data = [];
    var headers = ['Prodi', 'Kelas', 'IDMK', 'Mata Kuliah', 'SKS', 'Kelas', 'Jam Masuk', 'Jam Keluar', 'Hari', 'Status'];

    @foreach ($jadwal as $item)
    data.push([
        '{{ $item->prodi }}',
        '{{ $item->kelas }}',
        '{{ $item->idmk }}',
        '{{ $item->matakuliah }}',
        '{{ $item->sks }}',
        '{{ $item->kelas }}',
        '{{ $item->jammasuk }}',
        '{{ $item->jamkeluar }}',
        '{{ $hariMapping[$item->harijadwal] }}',
        '{{ $item->peran_pengajar }}'
    ]);
    @endforeach

    // Add total SKS to the table data
    data.push(['', '', 'Total SKS', '{{ $totalSKS }}', '', '', '', '', '']);

    // Adjust Y position for the table
    var startY = yPosition + 30; // Position of the table after lecturer information

    // Create table in the PDF
    doc.autoTable({
        head: [headers],
        body: data,
        startY: startY // Start from the specified position
    });

    // Add current date and time
    const currentDate = new Date();
    const formattedDate = currentDate.toLocaleDateString('en-US');
    const formattedTime = currentDate.toLocaleTimeString('en-US');
    const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;

    // Add text at the bottom of the page
    const bottomYPosition = doc.internal.pageSize.height - 20;
    doc.text(printDateTime, 50, bottomYPosition);
    doc.text('Downloaded from Q-Enterprise', 50, bottomYPosition + 15);

    // Download the PDF
    const fileName = `Kartu_Mengajar_Dosen_${formattedDate}.pdf`;
    doc.save(fileName);
}
@endif
@endif
    document.getElementById('downloadPDF').addEventListener('click', downloadPDF);
</script>
@endsection
