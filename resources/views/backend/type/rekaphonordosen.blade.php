@extends('admin.dashboard')
@section('admin')
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Rekap Honor Dosen /Bulan</h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{ route('rekapHonorDosen') }}" method="POST" id="honor">
                        @csrf
                        <div class="mb-3 col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="tahun" name="tahun" value="{{ old('tahun', $tahun ?? '') }}" required>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" class="form-select" id="bulan" required>
                                <option value="" disabled selected>Pilih Bulan</option>
                                <option value="01" {{ old('bulan', $bulan ?? '') == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ old('bulan', $bulan ?? '') == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ old('bulan', $bulan ?? '') == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ old('bulan', $bulan ?? '') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ old('bulan', $bulan ?? '') == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ old('bulan', $bulan ?? '') == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ old('bulan', $bulan ?? '') == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ old('bulan', $bulan ?? '') == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ old('bulan', $bulan ?? '') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('bulan', $bulan ?? '') == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ old('bulan', $bulan ?? '') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('bulan', $bulan ?? '') == '12' ? 'selected' : '' }}>Desember</option>
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
    @if(isset($totalHonor) && $totalHonor->isNotEmpty())
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button onclick="downloadPDF()" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                Download PDF
                                <i class="btn-icon-prepend" data-feather="printer"></i>
                            </button>
                        </div>
                    </div>
                <h4 class="mb-3">Rekap Honor SKS Dosen</h4>
                <p>Tahun {{ $tahun }}, Bulan 
                    @php
                        $namaBulan = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ];
                    @endphp
                    {{ $namaBulan[$bulan] }}
                </p>
                <hr class="my-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Dosen</th>
                                <th>Nama</th>
                                <th>Total Honor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totalHonor as $index => $j)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $j->id_dosen }}</td>
                                    <td>{{ $j->nama_dosen }}</td>
                                    
                                    <td>{{ number_format(intval(floatval($j->TotalHonor)), 3, '.', '.') }}</td>
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


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.jsPDF = window.jspdf.jsPDF;
function downloadPDF() {
    var doc = new jsPDF('l', 'pt', 'a4');

    doc.setFontSize(15);
    var tahun = "{{ session('tahun') }}";
    var bulan = "{{ session('bulan') }}";

    doc.setFontSize(20);
    doc.text('UNIVERSITAS QUALITY', 50, 20);
    doc.text('HONOR DOSEN MENGAJAR', 50, 60);
    doc.setFontSize(15);
    doc.text(`Tahun: ${tahun}/${bulan}`, 50, 100);

    var data = [];
    var headers = ['No', 'ID Dosen', 'Nama', 'Total Honor'];
    @if(isset($totalHonor) && $totalHonor->isNotEmpty())
        @foreach ($totalHonor as $index => $j)
        var TotalHonor = ({{ $j->TotalHonor * 1000 }}).toLocaleString('id-ID');
        data.push([
            {{ $index + 1 }},
            '{{ $j->id_dosen }}',
            '{{ $j->nama_dosen }}',
            TotalHonor
        ]);
        @endforeach
        var total = "{{ $total }}";
        var total = Number("{{ $total * 1000 }}").toLocaleString('id-ID');
        data.push(["", "", "Total", total]);

    @endif
    var startY = 140;
    doc.autoTable({
        head: [headers],
        body: data,
        startY: startY
    });

    // Generate QR Codes
    var qrYPosition = doc.previousAutoTable.finalY + 100;
    var qrSize = 100;
    var qrMargin = 80;
    
    var qrCodes = [
        { id: 'idDavid', name: 'David Purba', color: '#FFFF', label: 'Disetujui Oleh' },
        { id: 'idDedi', name: 'Dedi Simbolon', color: '#FFFF', label: 'Disetujui Oleh' },
        { id: 'idHernyke', name: 'Hernyke', color: '#FFFF', label: 'Disusun Oleh' }
    ];
    
    qrCodes.forEach(function(qr, index) {
        var qrXPosition = 50 + (index * (qrSize + qrMargin));
        QRCode.toDataURL(qr.id, { width: qrSize, height: qrSize }, function (err, url) {
            if (err) {
                console.error(err);
            } else {
                // Add label above the QR code
                doc.setTextColor(qr.color);
                doc.setFontSize(14);
                if (qr.label) {
                    doc.text(qr.label, qrXPosition + qrSize / 2, qrYPosition - 10, null, null, 'center');
                }
                
                doc.addImage(url, 'PNG', qrXPosition, qrYPosition, qrSize, qrSize);
                
                // Add name below the QR code
                doc.setFontSize(12);
                doc.text(qr.name, qrXPosition + qrSize / 2, qrYPosition + qrSize + 15, null, null, 'center');
                
                if (index === qrCodes.length - 1) {
                    const currentDate = new Date();
                    const formattedDate = currentDate.toLocaleDateString('en-US');
                    const formattedTime = currentDate.toLocaleTimeString('en-US');
                    const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
                    doc.text(printDateTime, 50, doc.internal.pageSize.height - 20);
                    const fileName = `Rekap_Honor_Dosen_Tahun${tahun}_${bulan}.pdf`;
                    doc.save(fileName);
                }
            }
        });
    });
}

</script>
@endsection
