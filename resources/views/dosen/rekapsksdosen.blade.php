@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <!-- Modal for showing the query results -->
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
                            <h4 class="mb-0">Rekap SKS Dosen</h4>
                            <hr class="my-4">
                            <form class="row g-3" action="{{ route('viewRekapSksDosen') }}" method="POST">
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
    @if(isset($results) && count($results) > 0)
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Rekap SKS Dosen</h4>
                    <div class="table-responsive">
                        <table class="table" id="rekapsksTable">
                            <thead>
                                <tr>
                                    <th>ID Dosen</th>
                                    <th>Nama</th>
                                    @if($idkampus == 11 || $idkampus == 16)
                                        <th>PGSD UQB</th>
                                        <th>PPKN UQB</th>
                                        <th>HUKUM UQB</th>
                                        <th>MANAJEMEN UQB</th>
                                        <th>Akuntansi UQB</th>
                                        <th>AGROTEKNOLOGI UQB</th>
                                        <th>AGRIBISNIS UQB</th>
                                        <th>MATEMATIKA UQB</th>
                                        <th>POR</th>
                                        <th>PBING</th>
                                        <th>uq</th>
                                    @else
                                        <th>PGSD</th>
                                        <th>PPKN</th>
                                        <th>HUKUM</th>
                                        <th>MANAJEMEN</th>
                                        <th>TEKNIK SIPIL</th>
                                        <th>AGROTEKNOLOGI</th>
                                        <th>AGRIBISNIS</th>
                                        <th>MATEMATIKA</th>
                                        <th>uqb</th>
                                    @endif
                                    <th>Total SKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->IDDOSEN2 }}</td>
                                    <td>{{ $result->NAMA }}</td>
                                    @if($idkampus == 11 || $idkampus == 16)
                                        <td>{{ $result->PGSD ?? 0 }}</td>
                                        <td>{{ $result->PPKN ?? 0 }}</td>
                                        <td>{{ $result->HUKUM ?? 0 }}</td>
                                        <td>{{ $result->MANAJEMEN ?? 0 }}</td>
                                        <td>{{ $result->Akuntansi ?? 0 }}</td>
                                        <td>{{ $result->AGROTEKNOLOGI ?? 0 }}</td>
                                        <td>{{ $result->AGRIBISNIS ?? 0 }}</td>
                                        <td>{{ $result->MATEMATIKA ?? 0 }}</td>
                                        <td>{{ $result->PORUQB ?? 0 }}</td>
                                        <td>{{ $result->PBINGUQB ?? 0 }}</td>
                                        <td>{{ $result->uq ?? 0 }}</td>
                                    @else
                                        <td>{{ $result->PGSD ?? 0 }}</td>
                                        <td>{{ $result->PPKN ?? 0 }}</td>
                                        <td>{{ $result->HUKUM ?? 0 }}</td>
                                        <td>{{ $result->MANAJEMEN ?? 0 }}</td>
                                        <td>{{ $result->TEKNIKSIPIL ?? 0 }}</td>
                                        <td>{{ $result->AGROTEKNOLOGI ?? 0 }}</td>
                                        <td>{{ $result->AGRIBISNIS ?? 0 }}</td>
                                        <td>{{ $result->MATEMATIKA ?? 0 }}</td>
                                        <td>{{ $result->uqb ?? 0 }}</td>
                                    @endif
                                    <td>{{ $result->TotalSKS ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Button to Download PDF -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
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

            $("#lokasi").val(lokasi);
        });
        @if(isset($results) && count($results) > 0)
        // Handle PDF download
        window.jsPDF = window.jspdf.jsPDF;
        function downloadPDF() {
            var doc = new jsPDF('l', 'pt', 'a4');
            var ta = "{{ $ta }}";
            var semester = "{{ $semester }}";
            var idkampus = "{{ $idkampus }}";
            var lokasi = "{{ $lokasi }}";

            // Determine university name based on idkampus
            var universityName = '';
            if (idkampus == 02 || idkampus == 04) {
                universityName = 'Universitas Quality';
            } else if (idkampus == 11 || idkampus == 16) {
                universityName = 'Universitas Quality Berastagi';
            }

            doc.setFontSize(15);
            doc.text(`Rekap Beban SKS Dosen ${universityName}`, 250, 60);

            doc.text(`TA/Semester: ${ta}/${semester}`,370, 100);

            // Collect data from the table
            var data = [];
            @foreach ($results as $result)
            data.push([
                '{{ $result->IDDOSEN2 }}',
                '{{ $result->NAMA }}',
                @if($idkampus == 11 || $idkampus == 16)
                '{{ $result->PGSD ?? 0 }}',
                '{{ $result->PPKN ?? 0 }}',
                '{{ $result->HUKUM ?? 0 }}',
                '{{ $result->MANAJEMEN ?? 0 }}',
                '{{ $result->Akuntansi ?? 0 }}',
                '{{ $result->AGROTEKNOLOGI ?? 0 }}',
                '{{ $result->AGRIBISNIS ?? 0 }}',
                '{{ $result->MATEMATIKA ?? 0 }}',
                '{{ $result->PORUQB ?? 0 }}',
                '{{ $result->PBINGUQB ?? 0 }}',
                '{{ $result->uq ?? 0 }}',
                @else
                '{{ $result->PGSD ?? 0 }}',
                '{{ $result->PPKN ?? 0 }}',
                '{{ $result->HUKUM ?? 0 }}',
                '{{ $result->MANAJEMEN ?? 0 }}',
                '{{ $result->TEKNIKSIPIL ?? 0 }}',
                '{{ $result->AGROTEKNOLOGI ?? 0 }}',
                '{{ $result->AGRIBISNIS ?? 0 }}',
                '{{ $result->MATEMATIKA ?? 0 }}',
                '{{ $result->uqb ?? 0 }}',
                @endif
                '{{ $result->TotalSKS ?? 0 }}'
            ]);
            @endforeach

            // Add table to PDF
            doc.autoTable({
                head: [['ID Dosen', 'Nama', @if($idkampus == 11 || $idkampus == 16) 'PGSD', 'PPKN', 'HUKUM', 'MANAJEMEN', 'Akuntansi',
                 'AGROTEKNOLOGI', 'AGRIBISNIS', 'MATEMATIKA', 'POR', 'PBING', 'UQ' @else 'PGSD', 'PPKN', 'HUKUM', 'MANAJEMEN',
                  'TEKNIK SIPIL', 'AGROTEKNOLOGI', 'AGRIBISNIS', 'MATEMATIKA', 'UQB' @endif, 'Total SKS']],
                body: data,
                startY: 140, // Adjust startY to fit the university name and other info
                theme: 'grid',
                styles: { fontSize: 8, cellPadding: 5 },
                headStyles: { fillColor: [100, 100, 100], textColor: [255, 255, 255] }
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
            const fileName = `Rekap_SKS_Dosen_${formattedDate}.pdf`;
            doc.save(fileName);
        }
        @endif
        document.getElementById('downloadPDF').addEventListener('click', downloadPDF);
    });
</script>

@endsection
