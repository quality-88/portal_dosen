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
                            <input type="text" class="form-control" id="ta_awal" name="ta_awal" value="{{ old('ta_awal', $ta_awal ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ta_akhir" class="form-label">Sampai TA</label>
                            <input type="text" class="form-control" id="ta_akhir" name="ta_akhir" value="{{ old('ta_akhir', $ta_akhir ?? '') }}" required>
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
                    @foreach($data as $year => $details)
                        <h5 class="card-title">Year: {{ $year }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Week Number</th>
                                        <th>Daftar</th>
                                        <th>Daftar Ulang</th>
                                        <th>Tidak Daftar Ulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details['daftar'] as $index => $weekData)
                                        <tr>
                                            <td>{{ $weekData->WeekNumber ?? '-' }}</td>
                                            <td>{{ $weekData->daftar ?? 0 }}</td>
                                            <td>{{ $details['daftarulang'][$index]->daftarulang ?? 0 }}</td>
                                            <td>{{ $details['tidakdaftarulang'][$index]['tidakdaftarulang'] ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td>{{ $details['totaldaftar'] }}</td>
                                        <td>{{ $details['totaldaftarulang'] }}</td>
                                        <td>{{ $details['totaltidakdaftarulang'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif


    @if(isset($resultArray) && count($resultArray) > 0)
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Grafik Pendaftaran Mahasiswa</h4>
                        <canvas id="calonChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Grafik Pendaftaran Ulang Mahasiswa</h4>
                        <canvas id="pmbChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <button id="saveBothChartsBtn" class="btn btn-primary mt-3">Save Both Charts as PDF</button>

    @endif

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.card-body {
    overflow-x: auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(isset($resultArray) && count($resultArray) > 0)
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        const calonData = @json($calonArray);
        const pendaftarData = @json($resultArray);
        const distinctTA = [...new Set([...calonData.map(item => item.ta), ...pendaftarData.map(item => item.ta)])];

        const calonChartDatasets = distinctTA.map(ta => {
            const data = monthNames.map((month, idx) => {
                const monthNumber = idx + 1;
                const calon = calonData.find(item => item.ta === ta && item.bulan === monthNumber);
                return calon ? calon.calon : 0;
            });

            return {
                label: `${ta}`,
                data: data,
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.5)`,
                borderColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`,
                borderWidth: 1
            };
        });

        const pendaftarChartDatasets = distinctTA.map(ta => {
            const data = monthNames.map((month, idx) => {
                const monthNumber = idx + 1;
                const pendaftar = pendaftarData.find(item => item.ta === ta && item.bulan === monthNumber);
                return pendaftar ? pendaftar.pendaftar : 0;
            });

            return {
                label: `${ta}`,
                data: data,
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.5)`,
                borderColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`,
                borderWidth: 1
            };
        });

        const calonChartConfig = {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: calonChartDatasets
            },
            options: {
                responsive: true
            }
        };

        const pendaftarChartConfig = {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: pendaftarChartDatasets
            },
            options: {
                responsive: true
            }
        };

        const calonChart = new Chart(document.getElementById('calonChart').getContext('2d'), calonChartConfig);
        const pendaftarChart = new Chart(document.getElementById('pmbChart').getContext('2d'), pendaftarChartConfig);
        window.jsPDF = window.jspdf.jsPDF;
        document.getElementById('saveBothChartsBtn').addEventListener('click', function() {
            const pdf = new jsPDF();

            // Fungsi untuk menyimpan kedua grafik ke dalam file PDF
            function saveChartsToPDF() {
                pdf.addPage();

                // Simpan grafik calon ke dalam file PDF
                const canvas1 = document.getElementById('calonChart');
                const imageData1 = canvas1.toDataURL('image/png'); // Menggunakan format png
                pdf.text(20, 20, 'Grafik Monitoring Calon PMB');
                pdf.addImage(imageData1, 'png', 10, 30, 180, 100);
                
                // Simpan grafik pendaftar ke dalam file PDF
                const canvas2 = document.getElementById('pmbChart');
                const imageData2 = canvas2.toDataURL('image/png'); // Menggunakan format png
                pdf.addPage();
                pdf.text(20, 20, 'Grafik Monitoring Pendaftar PMB');
                pdf.addImage(imageData2, 'png', 10, 30, 180, 100);

                // Simpan file PDF
                pdf.save('both_charts_and_table.pdf');
            }

            // Panggil fungsi untuk menyimpan kedua grafik ke dalam file PDF
            saveChartsToPDF();
        });
    @endif
});

</script>
@endsection
