@extends('admin.dashboard')

@section('admin')
<div class="page-content">
    <div class="row">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle>
                        <i data-feather="calendar" class="text-primary"></i>
                    </span>
                    <input class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Mahasiswa Aktif</h5>
                    <h3>{{ $countDistinctNpm }}</h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Mahasiswa Lulus 2023</h5>
                    <h3>{{ $countNpm }}</h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Mahasiswa Baru</h5>
                    <h3>{{ $countNpm2024 }}</h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Dosen Aktif</h5>
                    <h3>{{ $dosen }}</h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
        <!-- Grafik Donat Mahasiswa Aktif per Prodi -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Grafik Mahasiswa Aktif per Prodi</h5>
                    <canvas id="prodiChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- Grafik Donat Mahasiswa Lulus per Prodi -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Grafik Mahasiswa Lulus 2023 per Prodi</h5>
                    <canvas id="lulusanProdiChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div> <!-- row -->
</div>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Grafik Mahasiswa Aktif per Prodi
        var ctx1 = document.getElementById('prodiChart').getContext('2d');
        var prodiChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: @json($prodiLabels),
                datasets: [{
                    data: @json($prodiCounts),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Mahasiswa Aktif per Prodi'
                    }
                }
            }
        });

        // Grafik Mahasiswa Lulus 2023 per Prodi
        var ctx2 = document.getElementById('lulusanProdiChart').getContext('2d');
        var lulusanProdiChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: @json($lulusanProdiLabels),
                datasets: [{
                    data: @json($lulusanProdiCounts),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Mahasiswa Lulus 2023 per Prodi'
                    }
                }
            }
        });
    });
</script>
@endsection
