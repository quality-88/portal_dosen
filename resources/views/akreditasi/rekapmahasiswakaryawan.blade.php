@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Rekap Mahasiswa Karyawan Aktif /Prodi</h4>
                    <form class="row g-5" action="{{ route('view.karyawan') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose Prodi...</option>
                                @foreach($prodis as $pilihanProdi)
                                    <option value="{{ $pilihanProdi->prodi }}" {{ old('prodi', isset($prodi) ? $prodi : '') == $pilihanProdi->prodi ? 'selected' : '' }}>{{ $pilihanProdi->prodi }}</option>
                                @endforeach
                            </select>                            
                        </div>
                        <div class="col-md-4">
                            <label for="ta_mulai" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta_mulai" name="ta_mulai" value="{{ old('ta_mulai', isset($ta_start) ? $ta_start : '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($results))
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="myExportableTable" class="table">
                            <thead>
                                <tr>
                                    <th>Angkatan</th>
                                    <th>Jumlah Mahasiswa Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->Angkatan }}</td>
                                    <td>{{ $result->JumlahMahasiswa }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td>{{ $results->sum('JumlahMahasiswa') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

</div>
<style>
    /* Tambahkan gaya kursor pointer saat mengarahkan kursor ke baris tabel */
    #myExportableTable tbody tr:hover {
        cursor: pointer;
    }
</style>
<!-- Include the necessary scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script>
     document.addEventListener('DOMContentLoaded', function () {
        // Add click event listener to each table row
        document.querySelectorAll('#myExportableTable tbody tr').forEach(function(row) {
            row.addEventListener('click', function() {
            
                var prodi = "{{ session('prodi') }}";
                
                var ta = "{{ session('ta_start') }}";
                // Convert dd/mm/yyyy to yyyy-mm-dd
                //var tglUAS = convertDateFormat(row.cells[2].textContent.trim());

                // Redirect to nilaikelas.blade.php with parameters
                var url = "{{ route('detail.karyawan') }}";
                
// Assuming 'idDosen', 'idMK', 'kelas', 'idKampus', 'prodi', and 'tglUAS' are your parameters
var parameters = {

    prodi: prodi,
    ta : ta
};

// Redirect to the route with parameters
window.location.href = url + '?' + new URLSearchParams(parameters).toString();

            });
        });
    });
</script>

@endsection
