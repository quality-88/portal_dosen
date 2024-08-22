@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <h4 class="mb-0">Alumni</h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{ route('viewAlumni') }}" method="POST" id="alumni">
                        @csrf
                        
                        <div class="col-md-6">
                            <label for="idkampus" class="form-label">ID Kampus</label>
                            <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                <option value="" disabled {{ old('idkampus', $idkampus ?? '') == '' ? 'selected' : '' }}>Choose ID Kampus...</option>
                                @foreach($allIdKampus as $data)
                                    <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}" {{ old('idkampus', $idkampus ?? '') == $data->idkampus ? 'selected' : '' }}>
                                        {{ $data->idkampus }} - {{ $data->lokasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" value="{{ old('lokasi', $lokasi ?? '') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()">
                                <option value="" disabled {{ old('prodi', $prodi ?? '') == '' ? 'selected' : '' }}>Choose Prodi...</option>
                                @foreach($allProdi as $data)
                                    <option value="{{ $data->prodi }}" {{ old('prodi', $prodi ?? '') == $data->prodi ? 'selected' : '' }}>
                                        {{ $data->prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_fakultas" class="form-label">ID Fakultas</label>
                            <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" value="{{ old('id_fakultas', $idfakultas ?? '') }}" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" value="{{ old('fakultas', $fakultas ?? '') }}" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="lulus" class="form-label">Lulus Tahun</label>
                            <input type="text" class="form-control" id="lulus" name="lulus" value="{{ old('lulus', $lulus ?? '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>   
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($results) && count($results) > 0)
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0">Alumni</h4>
                <hr class="my-4">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success " onclick="downloadPDF()">Download PDF</button>
                    <button type="button" class="btn btn-secondary" onclick="downloadExcel()">Download Excel</button>
                </div>
                <hr class="my-4">
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NPM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>HP</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $alumni)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $alumni->NPM }}</td>
                                    <td>{{ $alumni->NAMA }}</td>
                                    <td>{{ $alumni->PRODI }}</td>
                                    <td>{{ $alumni->hp }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Include jQuery and other necessary scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    window.jsPDF = window.jspdf.jsPDF;

    jQuery(document).ready(function ($) {

        $('#idkampus').change(function () {
            var lokasi = $(this).find(':selected').data('lokasi');
            $("#lokasi").val(lokasi);
        });

        $('#prodi').change(function () {
            updateFakultas();
        });
    });

    function updateFakultas() {
        var selectedProdi = $('#prodi').val();
        if (selectedProdi) {
            $.ajax({
                url: "{{ route('fetchFakultas') }}",
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
    // Function to download PDF
    function downloadPDF() {
        var doc = new jsPDF('p', 'pt', 'a4');

        doc.setFontSize(10);
        doc.setFontSize(20);
        doc.text('ALUMNI UNIVERSITAS QUALITY', 100, 50);
        doc.setFontSize(15);

        // Get table data
        var data = [];
        var headers = ['No', 'NPM', 'Nama', 'Prodi', 'HP'];

        // You need to replace this with your dynamic data fetching or table data
        @if(isset($results))
            @foreach($results as $index => $alumni)
                data.push([{{ $index + 1 }}, '{{ $alumni->NPM }}', '{{ $alumni->NAMA }}', '{{ $alumni->PRODI }}', '{{ $alumni->hp }}']);
            @endforeach
        @endif

        doc.autoTable({
            head: [headers],
            body: data,
            startY: 100,
            theme: 'grid'
        });

        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);

        const fileName = `Almni-    Universitas${formattedDate.replace(/\//g, '-')}.pdf`;
        doc.save(fileName);
    }

    // Function to download Excel
    function downloadExcel() {
        // Create a new workbook and worksheet
        var wb = XLSX.utils.book_new();
        var ws_data = [
            ['No', 'NPM', 'Nama', 'Prodi', 'HP']
        ];

        // Add table data to worksheet
        @if(isset($results))
            @foreach($results as $index => $alumni)
                ws_data.push([{{ $index + 1 }}, '{{ $alumni->NPM }}', '{{ $alumni->NAMA }}', '{{ $alumni->PRODI }}', '{{ $alumni->hp }}']);
            @endforeach
        @endif

        var ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, 'Data');

        // Generate Excel file and trigger download
        var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }
        saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'ALUMNI UNIVERSITAS QUALITY.xlsx');
    }
</script>

@endsection
