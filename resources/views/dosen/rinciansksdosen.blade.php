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
                            <h4 class="mb-0">Rincian SKS Dosen</h4>
                            <hr class="my-4">
                            <form class="row g-3" action="{{ route('viewRincianSksDosen') }}" method="POST">
                                @csrf
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">ID Kampus</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="idkampus" name="idkampus" aria-label="Pilih ID Kampus" required>
                                            <option value="" disabled selected>Pilih ID Kampus...</option>
                                            @foreach($allIdKampus as $data)
                                                <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}" {{ old('idkampus', $idkampus ?? '') == $data->idkampus ? 'selected' : '' }}>
                                                    {{ $data->idkampus }} - {{ $data->lokasi }}
                                                </option>
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
                                    <label class="col-sm-2 col-form-label">Prodi</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="prodi" name="prodi" aria-label="Pilih Prodi" onchange="updateFakultas()" required>
                                            <option value="" disabled>Pilih Prodi...</option>
                                            @foreach($allProdi as $data)
                                                <option value="{{ $data->prodi }}" {{ old('prodi', $prodi ?? '') == $data->prodi ? 'selected' : '' }}>
                                                    {{ $data->prodi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">ID Fakultas</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" readonly value="{{ old('id_fakultas', $idfakultas ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-2 col-form-label">Fakultas</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="fakultas" name="fakultas" readonly value="{{ old('fakultas', $fakultas ?? '') }}" required>
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
                                        <input type="text" class="form-control" id="semester" name="semester" placeholder="Semester" value="{{ old('semester', $semester ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg float-end">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($results) && count($results) > 0)
        @php
            $dosenGroups = $results->groupBy('IDDOSEN2');
        @endphp

        @foreach($dosenGroups as $dosenId => $dosenData)
            <div class="row justify-content-center">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Rincian SKS Dosen - {{ $dosenData->first()->NAMA }}</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Hari Jadwal</th>
                                            <th>ID Mata Kuliah</th>
                                            <th>Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Kelas</th>
                                            <th>Ruang</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Kurikulum</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalSks = 0;
                                        @endphp
                                        @foreach($dosenData as $data)
                                            <tr>
                                                <td>{{ $data->harijadwal }}</td>
                                                <td>{{ $data->IDMK }}</td>
                                                <td>{{ $data->MATAKULIAH }}</td>
                                                <td>{{ $data->SKS }}</td>
                                                <td>{{ $data->kelas }}</td>
                                                <td>{{ $data->IDRUANG }}</td>
                                                <td>{{ $data->JAMMASUK }}</td>
                                                <td>{{ $data->JAMKELUAR }}</td>
                                                <td>{{ $data->KURIKULUM }}</td>
                                                <td>{{ $data->keterangan }}</td>
                                            </tr>
                                            @php
                                                $totalSks += $data->SKS;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="10" class="text-end"><strong>Total SKS</strong></td>
                                            <td><strong>{{ $totalSks }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Tombol untuk Unduh PDF -->
        <button onclick="downloadPDF()" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">Unduh PDF
            <i class="btn-icon-prepend" data-feather="printer"></i>
        </button>
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
    // Fungsi untuk memperbarui Fakultas berdasarkan Prodi
    function updateFakultas() {
        var selectedProdi = $('#prodi').val();
        if (selectedProdi) {
            $.ajax({
                url: '{{ route("fetchFakultas") }}',
                method: 'GET',
                data: { prodi: selectedProdi },
                success: function (response) {
                    if (response.no_data) {
                        Swal.fire('Data tidak ditemukan', 'Silakan pilih Prodi yang berbeda.', 'warning');
                    } else {
                        $('#id_fakultas').val(response.idfakultas);
                        $('#fakultas').val(response.fakultas);
                    }
                },
                error: function (error) {
                    console.error('Terjadi kesalahan saat mengambil data:', error);
                }
            });
        }
    }

    // Fungsi yang dijalankan saat dokumen siap
    $(document).ready(function () {
        // Event handler untuk pemilihan lokasi kampus
        $('#idkampus').change(function () {
            var idKampus = $(this).val();
            var lokasi = $(this).find(':selected').data('lokasi');
            $("#lokasi").val(lokasi);
        });
    });

    @if(isset($results) && count($results) > 0)
        window.jsPDF = window.jspdf.jsPDF;
        window.autoTable = window.jspdf.autoTable;

        function downloadPDF() {
            var doc = new jsPDF('l', 'pt', 'a4');  // Landscape orientation and A4 size
            var ta = "{{ $ta }}";
            var semester = "{{ $semester }}";
            var idkampus = "{{ $idkampus }}";
            var lokasi = "{{ $lokasi }}";
            var prodi = "{{ $prodi }}";

            var universityName = '';
            if (idkampus == 02 || idkampus == 04) {
                universityName = 'Universitas Quality';
            } else if (idkampus == 11 || idkampus == 16) {
                universityName = 'Universitas Quality Berastagi';
            }

            // Set font size and add text at the top
            doc.setFontSize(20);
            doc.text(`Rincian SKS Dosen ${universityName}`, 40, 30);

            doc.setFontSize(15);
            doc.text(`TA/Semester: ${ta}/${semester}`, 40, 50);
            doc.text(`Prodi: ${prodi}`, 40, 70);
            // Add a margin before the table starts
            var margin = 40;
            var pageHeight = doc.internal.pageSize.height;
            var currentPageHeight = 90; // Starting point after the text

            @foreach($dosenGroups as $dosenId => $dosenData)
                if (currentPageHeight + 70 > pageHeight) {
                    doc.addPage();
                    currentPageHeight = margin; // Reset for new page
                }

                doc.setFontSize(12);
                doc.text(`Nama Dosen: {{ $dosenData->first()->NAMA }}`, margin, currentPageHeight);
                currentPageHeight += 10; // Add space before the table

                var data = [];
                var totalSks = 0;
                @foreach($dosenData as $data)
                    data.push([
                        '{{ $data->harijadwal }}',
                        '{{ $data->IDMK }}',
                        '{{ $data->MATAKULIAH }}',
                        '{{ $data->SKS }}',
                        '{{ $data->kelas }}',
                        '{{ $data->IDRUANG }}',
                        '{{ $data->JAMMASUK }}',
                        '{{ $data->JAMKELUAR }}',
                        '{{ $data->KURIKULUM }}',
                        '{{ $data->keterangan }}'
                    ]);
                    totalSks += parseInt('{{ $data->SKS }}');
                @endforeach

                data.push(['', '', 'Total SKS',  totalSks, '', '', '', '', '', ]);

                doc.autoTable({
                    head: [[
                        'Hari Jadwal', 'ID Mata Kuliah','Mata Kuliah','SKS','Kelas', 'Ruang',
                         'Jam Masuk', 'Jam Keluar', 'Kurikulum', 'Keterangan'
                    ]],
                    body: data,
                    startY: currentPageHeight,
                    theme: 'grid',
                    styles: { fontSize: 8, cellPadding: 3 },
                    headStyles: { fillColor: [100, 100, 100], textColor: [255, 255, 255] },
                    margin: { top: margin, bottom: margin },
                    pageBreak: 'auto'
                });

                currentPageHeight = doc.autoTable.previous.finalY + 20; // Update height for next page
            @endforeach
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US');
            const formattedTime = currentDate.toLocaleTimeString('en-US');
            const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;

            // Add text at the bottom of the page
            const bottomYPosition = doc.internal.pageSize.height - 20;
            doc.text(printDateTime, 50, bottomYPosition);
            doc.text('Downloaded from Q-Enterprise', 50, bottomYPosition + 15);

            const fileName = `Rincian-SKS-Dosen${formattedDate}.pdf`;
            doc.save(fileName);
        }
    @endif
</script>
@endsection
