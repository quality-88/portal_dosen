@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Grafik Monitoring PMB</h4>
                    <form class="row g-5" action="{{ route('viewGrafikPMB') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', $universitas ?? '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', $universitas ?? '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ta_awal" class="form-label">Mulai dari TA</label>
                            <input type="number" class="form-control" id="ta_awal" name="ta_awal" value="{{ old('ta_awal', $ta_awal ?? '') }}" required min="2020">
                        </div>
                        <div class="col-md-4">
                            <label for="ta_akhir" class="form-label">Sampai TA</label>
                            <input type="number" class="form-control" id="ta_akhir" name="ta_akhir" value="{{ old('ta_akhir', $ta_akhir ?? '') }}" required min="2021">
                        </div>
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success mt-4">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(isset($data) && count($data) > 0)

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Grafik PMB </h4>
                    <button id="generatePDF" class="btn btn-primary mt-3">Generate PDF</button>
                    <div class="table-responsive">
                        <table class="table" id="grafikpmb">
                            <thead>
                                <tr>
                                    <th rowspan="2" >Bulan</th>
                                    <th rowspan="2">Minggu</th>
                                    @foreach (range($ta_awal, $ta_akhir) as $year)
                                        <th colspan="3">{{ $year }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach (range($ta_awal, $ta_akhir) as $year)
                                        <th>Daftar</th>
                                        <th>Daftar Ulang</th>
                                        <th>Tidak Daftar Ulang</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            
                           @foreach ($months as $month => $weeks)
                           @php
                               $firstRow = true;
                               $weekCount = count($weeks); // Mendapatkan jumlah minggu dalam bulan ini
                           @endphp
                           @foreach ($weeks as $index => $week)
                           <tr>
                               @if ($firstRow)
                                   <td rowspan="{{ count($weeks) }}">{{ $month }}</td>
                                   @php
                                       $firstRow = false;
                                   @endphp
                               @endif
                               <td>{{ $index + 1 }}</td>
                               @foreach (range($ta_awal, $ta_akhir) as $year)
                               <td>{{ optional(collect($data[$year]['daftar'])->firstWhere('WeekNumber', $week))->daftar ?? 0 }}</td>
                               <td>{{ optional(collect($data[$year]['daftarulang'])->firstWhere('WeekNumber', $week))->daftarulang ?? 0 }}</td>
                               <td>{{ optional(collect($data[$year]['tidakdaftarulang'])->firstWhere('WeekNumber', $week))['tidakdaftarulang'] ?? 0 }}</td>
                           @endforeach
                           </tr>
                       @endforeach
                       
                            <tr class="bg-primary">
                                <td colspan="2">Total {{ $month }}</td>
                                @foreach (range($ta_awal, $ta_akhir) as $year)
                                <td>{{ $totalDaftar[$year][$month] }}</td>
                                    <td>{{ $totalDaftarUlang[$year][$month] }}</td>
                                    <td>{{ $totalTidakDaftarUlang[$year][$month] }}</td>
                                @endforeach
                            </tr>
                            @if ($month == 'Desember' && $showTotalYear)
                                <tr class="bg-primary">
                                    <td colspan="2">Total</td>
                                    @foreach (range($ta_awal, $ta_akhir) as $year)
                                        <td>{{ array_sum($totalDaftar[$year]) }}</td>
                                        <td>{{ array_sum($totalDaftarUlang[$year]) }}</td>
                                        <td>{{ array_sum($totalTidakDaftarUlang[$year]) }}</td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                        
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (isset($data))
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Grafik PMB</h4>
                        <canvas id="pmbChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Grafik PUMB</h4>
                        <canvas id="pmbChartDaftarUlang"></canvas>
                       
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include necessary scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.22/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src=""></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if (isset($result))
window.jsPDF = window.jspdf.jsPDF;
document.addEventListener('DOMContentLoaded', function() {
    var labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Register datalabels plugin globally
    Chart.register(ChartDataLabels);

    function createCombinedBarCharts(labels, dataDaftar, dataDaftarUlang) {
        var ta_awal = {{$ta_awal}};
        var ta_akhir = {{$ta_akhir}};

        // Filter dataDaftar dan dataDaftarUlang berdasarkan rentang tahun yang dipilih
        var dataYearsDaftar = Object.keys(dataDaftar).filter(year => (parseInt(year) >= ta_awal && parseInt(year) <= ta_akhir));
        var dataYearsDaftarUlang = Object.keys(dataDaftarUlang).filter(year => (parseInt(year) >= ta_awal && parseInt(year) <= ta_akhir));

        // Array warna yang berbeda untuk setiap dataset
        var colorsDaftar = ['rgba(0,255,0, 0.2)', 'rgba(0,0,255, 0.2)', 'rgba(255,165,0, 0.2)', 'rgba(0,255,255, 0.2)',
        'rgba( 255,0,255, 0.2)'];
        var colorsDaftarUlang = ['rgba(0,255,0, 0.2)', 'rgba(0,0,255, 0.2)', 'rgba(255,165,0, 0.2)', 'rgba(0,255,255, 0.2)',
            'rgba(255,0,255,0.2)'
        ];

        var chartDataDaftar = dataYearsDaftar.map((year, index) => ({
            label: year + ' Daftar',
            data: Object.values(totalDaftar[year]),
            backgroundColor: colorsDaftar[index % colorsDaftar.length], // Pilih warna berdasarkan indeks
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }));

        var chartDataDaftarUlang = dataYearsDaftarUlang.map((year, index) => ({
            label: year + ' Daftar Ulang',
            data: Object.values(totalDaftarUlang[year]),
            backgroundColor: colorsDaftarUlang[index % colorsDaftarUlang.length], // Pilih warna berdasarkan indeks
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }));
        var ctxDaftar = document.getElementById('pmbChart').getContext('2d');
        var chartDaftar = new Chart(ctxDaftar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: chartDataDaftar
            },
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        align: 'end',
                        anchor: 'end',
                        color: '#000', // warna teks
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value, context) {
                            return Math.round(value); // menampilkan nilai sebagai angka bulat
                        },
                        padding: {
                        top: -10  // Menggeser teks label ke atas
                    }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxDaftarUlang = document.getElementById('pmbChartDaftarUlang').getContext('2d');
        var chartDaftarUlang = new Chart(ctxDaftarUlang, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: chartDataDaftarUlang
            },
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        align: 'end',
                        anchor: 'end',
                        color: '#000', // warna teks
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value, context) {
                            return Math.round(value); // menampilkan nilai sebagai angka bulat
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    var totalDaftar = {!! json_encode($totalDaftar) !!};
    var totalDaftarUlang = {!! json_encode($totalDaftarUlang) !!};

    createCombinedBarCharts(labels, totalDaftar, totalDaftarUlang);
    function createPDF() {
    var doc = new jsPDF('p', 'pt', 'a4');
    doc.setTextColor(0, 0, 255);

    // Calculate page width
    var pageWidth = doc.internal.pageSize.getWidth();
    var universitas = "{{ session('universitas') }}";
    var lokasiText = "{{ $universitas === 'UQM' ? 'MEDAN' : ($universitas === 'UQB' ? 'BERASTAGI' : '') }}";
    var ta_awal = {{$ta_awal}};
    var ta_akhir = {{$ta_akhir}};

    // Adjust the text and table styling
    doc.setFontSize(8);

    // First, add the main content without page numbers
    doc.setFontSize(15);
    doc.setTextColor(0, 0, 0);
    doc.text(`GRAFIK REKAPITULASI PENERIMAAN MAHASISWA BARU`, 110, 40);
    doc.setFontSize(10);
    doc.text(`UNIVERSITAS QUALITY ${lokasiText}`, 220, 60);

    doc.autoTable({
        html: '#grafikpmb',
        startY: 100,
        theme: 'grid',
        styles: {
            overflow: 'linebreak',
            fontSize: 7,
            cellPadding: 5,
            halign: 'center',
            valign: 'middle',
            fillColor: [100, 149, 237], // Background color for non-header cells
            textColor: [0, 0, 0], // Default text color (black)
        },
        headStyles: {
            fillColor: [100, 149, 237], // Background color for header cells
            textColor: [255, 255, 255], // Text color for header cells (white)
            fontStyle: 'bold', // Bold font for header cells
        },
        tableWidth: 'auto',
        columnWidth: 'auto',
        rowHeight: 20,
        didDrawCell: function (data) {
            var rowsToHighlight = [5, 10, 15, 21, 26, 31, 37, 42, 48, 53, 58, 64, 65];
            var rowIndex = data.row.index; // Index starts at 0, so add 1

            if (data.section === 'head') {
                doc.setFillColor(0, 168, 107); // Set header background color to green
                doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                doc.setTextColor(255); // Set header text color to white
            } else {
                if (rowIndex === 65) {
                    doc.setFillColor(0, 168, 107); // Set background color for row 65 (same as header)
                    doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                    doc.setTextColor(255); // Set text color for row 65
                    doc.setFontSize(10); // Adjust the font size as needed
                } else {
                    if (rowsToHighlight.includes(rowIndex)) {
                        doc.setFillColor(100, 149, 237); // Background color for highlighted rows
                        doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                        doc.setTextColor(255);
                        doc.setFontSize(9); // Set text color to white for highlighted rows
                    } else {
                        doc.setFillColor(255); // Default background color (white)
                        doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
                        doc.setTextColor(0); // Default text color (black)
                    }
                }
            }

            var cellText = data.cell.text ?? '';

            var textPosX = data.cell.x + data.cell.width / 2;
            var textPosY = data.cell.y + data.cell.height / 2;
            doc.text(cellText, textPosX, textPosY, { align: 'center', valign: 'middle' });

            if (rowIndex === 65) {
                doc.setFontSize(10); // Reset to default font size after drawing row 65
            }
        }
    });

    // Add charts
    html2canvas(document.querySelector("#pmbChart"), { dpi: 300, scale: 2 }).then(canvas => {
        var imgData = canvas.toDataURL('image/png');
        doc.addPage();
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(12);
        doc.text(`Grafik Pendaftaran Mahasiswa Baru Universitas Quality ${lokasiText} (${ta_awal} - ${ta_akhir})`, 10, 90);
        doc.addImage(imgData, 'PNG', 10, 140, pageWidth - 20, 500);

        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US');
        const formattedTime = currentDate.toLocaleTimeString('en-US');
        const printDateTime = `Print Date: ${formattedDate} / Print Time: ${formattedTime}`;
        doc.setFontSize(8);
        doc.setTextColor(100, 100, 100);
        doc.text(printDateTime, 10, 720);

        html2canvas(document.querySelector("#pmbChartDaftarUlang"), { dpi: 300, scale: 2 }).then(canvas => {
            var imgData = canvas.toDataURL('image/png');
            doc.addPage();
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(12);
            doc.text(`Grafik Pendaftaran Ulang Mahasiswa Baru Universitas Quality ${lokasiText} (${ta_awal} - ${ta_akhir})`, 10, 90);
            doc.addImage(imgData, 'PNG', 10, 140, pageWidth - 20, 500);

            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(printDateTime, 10, 720);

            // Total number of pages
            const totalPages = doc.internal.getNumberOfPages();

            // Add page numbers to each page
            for (let i = 1; i <= totalPages; i++) {
                doc.setPage(i);
                const pageNumberText = `Page ${i} of ${totalPages}`;
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text(pageNumberText, pageWidth / 2, doc.internal.pageSize.getHeight() - 30, { align: 'center' });
            }

            // Save the PDF
            doc.save(`Grafik_Monitoring_PMB-${lokasiText}_${ta_awal} - ${ta_akhir}_${formattedDate}.pdf`);
        });
    });
}


    document.getElementById('generatePDF').addEventListener('click', function() {
        createPDF();
    });
@endif
});
document.addEventListener('DOMContentLoaded', function() {
        @if (count($errors) > 0)
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}', // Menampilkan pesan error pertama dari Laravel
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endsection
