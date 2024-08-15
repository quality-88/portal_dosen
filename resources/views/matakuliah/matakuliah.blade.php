@extends('admin.dashboard')

@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-content">
    <!-- Main Form-->
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-0">Matakuliah</h4>
                            <hr class="my-4">
                            
                            <!-- Add Button -->
                            <div class="mb-4">
                                <a href="{{ route('addMataKuliah') }}" class="btn btn-success btn-lg">Tambah</a>

                            </div>

                            <!-- Form -->
                            <form class="row g-3" action="{{ route('viewMatakuliah') }}" method="POST">
                                @csrf
                                <div class="form-group row mb-3">
                                    <div class ="mb-4"></div>
                                    <label class="col-sm-1 col-form-label">Prodi</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()" required>
                                                <option value="" disabled>Choose Prodi...</option>
                                                @foreach($allProdi as $prodiItem)
                                                    @php
                                                        $selected = (session('prodi') == $prodiItem->prodi) ? 'selected' : '';
                                                    @endphp
                                                    <option value="{{ $prodiItem->prodi }}" {{ $selected }}>{{ $prodiItem->prodi }}</option>
                                                @endforeach
                                            </select>
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
    @if(isset($results) && $results->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">

                        <div class="col-md-3">
                           
                            <input type="text" id="search" class="form-control mb-3" placeholder="Search">
                        </div>
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>IDMK</th>
                                    <th>Mata Kuliah</th>
                                    <th>Semester</th>
                                    <th>SKS</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $matakuliah)
                                <tr>
                                    <td>{{ $matakuliah->IDMK }}</td>
                                    <td>{{ $matakuliah->MATAKULIAH }}</td>
                                    <td>{{ $matakuliah->SEMESTER }}</td>
                                    <td>{{ $matakuliah->SKS }}</td>
                                    <td>{{ $matakuliah->TIPE }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                     <!-- Pagination Links -->
                     {{ $results->appends(['prodi' => $prodi])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    @endif
    
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<!-- Include Flatpickr styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Include Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
    /* Tambahkan gaya kursor pointer saat mengarahkan kursor ke baris tabel */
    #myExportableTable tbody tr:hover {
        cursor: pointer;
    }
</style>
<script>
 document.addEventListener('DOMContentLoaded', function () {
    const prodi = '{{ isset($prodi) ? $prodi : '' }}';

    document.getElementById('search').addEventListener('input', function () {
        const searchText = this.value.trim();

        $.ajax({
            url: "{{ route('searchMatakuliah') }}",
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'prodi': prodi,
                'search': searchText
            },
            success: function (response) {
                // Ensure response.data is available
                if (response.data && Array.isArray(response.data)) {
                    updateTable(response.data);
                    addRowClickListener(); // Add this to ensure the rows are clickable after being updated
                } else {
                    console.error('Invalid response data format:', response);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    function updateTable(results) {
        const tbody = document.querySelector('#myExportableTable tbody');
        tbody.innerHTML = '';

        results.forEach(result => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${result.IDMK}</td>
                <td>${result.MATAKULIAH}</td>
                <td>${result.SEMESTER}</td>
                <td>${result.SKS}</td>
                <td>${result.TIPE}</td>
            `;
            tbody.appendChild(row);
        });

        addRowClickListener(); // Ensure the rows are clickable
    }

    // Function to add click event listener to each row
    function addRowClickListener() {
        const tableRows = document.querySelectorAll('#myExportableTable tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('click', function () {
                const idmk = row.cells[0].innerText;
                const matakuliah = row.cells[1].innerText;
                
                var url = "{{ route('detailMataKuliah') }}";
                var parameters = {
                    idmk: idmk,
                    matakuliah: matakuliah,
                    prodi: prodi
                };
                window.location.href = url + '?' + new URLSearchParams(parameters).toString();
            });
        });
    }

    // Add click event listeners for pagination links
    const pageLinks = document.querySelectorAll('.page-link');
    pageLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const page = this.getAttribute('href').split('page=')[1];
            const form = document.querySelector('form');
            
            form.action = '{{ route("viewMatakuliah") }}?page=' + page + '&prodi=' + prodi;
            form.submit();
        });
    });

    // Initial call to add row click listener for the first render
    addRowClickListener();
});

    </script>
@endsection
