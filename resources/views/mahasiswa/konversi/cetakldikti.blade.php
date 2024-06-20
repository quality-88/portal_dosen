@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Cetak LLDikti</h4>
                    <form class="row g-5" action="{{ route('cetakLDIKTI') }}" method="POST">
                        @csrf
                        <input type="hidden" name="universitas" value="{{ isset($universitas) ? $universitas : '' }}">
                        <input type="hidden" name="ta" value="{{ isset($ta) ? $ta : '' }}">

                        <div class="col-md-4">
                            <label for="universitas" class="form-label">Universitas</label>
                            <select class="form-select" id="universitas" name="universitas" required>
                                <option value="">Choose .....</option>
                                <option value="UQB" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQB' ? 'selected' : '' }}>UQB</option>
                                <option value="UQM" {{ old('universitas', isset($universitas) ? $universitas : '') == 'UQM' ? 'selected' : '' }}>UQM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ta">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" value="{{ old('ta', isset($ta) ? $ta : '') }}" required>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <div class="col-md-3">
                            <label for="search">Search:</label>
                            <input type="text" id="search" class="form-control mb-3" placeholder="Search by name">
                        </div>
                        
                        <table id="myExportableTable" class="table" font-size="10">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPM</th>
                                    <th>NAMA</th>
                                    <th>NPM ASAL</th>
                                    <th>TA</th>
                                    <th>KURIKULUM</th>
                                    <th>ID KAMPUS</th>
                                    <th>KAMPUS</th>
                                    <th>FAKULTAS</th>
                                    <th>PRODI</th>
                                    <th>ID UNIVERSITAS ASAL</th>
                                    <th>UNIVERSITAS ASAL</th>
                                    <th>ID PRODI ASAL</th>
                                    <th>Prodi ASAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>{{ $result->NoUrut }}</td>
                                    <td>{{ $result->NPM }}</td>
                                    <td>{{ $result->Nama }}</td>
                                    <td>{{ $result->NPMASAL }}</td>
                                    <td>{{ $result->TA }}</td>
                                    <td>{{ $result->kurikulum }}</td>
                                    <td>{{ $result->IDKAMPUS }}</td>
                                    <td>{{ $result->LOKASI }}</td>
                                    <td>{{ $result->fakultas }}</td>
                                    <td>{{ $result->PRODI }}</td>
                                    <td>{{ $result->KDPTASAL }}</td>
                                    <td>{{ $result->UNIVERSITAS }}</td>
                                    <td>{{ $result->IDPRODIASAL }}</td>
                                    <td>{{ $result->PRODIASAL }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $results->appends(['universitas' => $universitas, 'ta' => $ta])->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Your custom JavaScript to load and display PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<!-- Add these lines to the head section of your HTML document -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.0/sweetalert2.js" 
integrity="sha512-n+FwLK5s6dd4XL68lrwGn1j9TSCTFA15TgF7KbcShrGV7Ma761MniYPUAz0PPipTi18IXLbr+Ag9cxrEvIeASw=="
 crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 
<!-- Script untuk QRCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
<style>
    /* Tambahkan gaya kursor pointer saat mengarahkan kursor ke baris tabel */
    #myExportableTable tbody tr:hover {
        cursor: pointer;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure universitas and ta are defined in JS even if not set in Blade
        const universitas = '{{ isset($universitas) ? $universitas : '' }}';
        const ta = '{{ isset($ta) ? $ta : '' }}';
        
        // Update event listener for search input
        document.getElementById('search').addEventListener('input', function () {
            const searchText = this.value.trim();
            
            $.ajax({
                url: "{{ route('searchLDIKTI') }}",
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'universitas': universitas,
                    'ta': ta,
                    'search': searchText
                },
                success: function (response) {
                    updateTable(response);
                    addRowClickListener();
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });
        
        // Function to update table with search results
        function updateTable(results) {
            const tbody = document.querySelector('#myExportableTable tbody');
            tbody.innerHTML = ''; 
            
            results.forEach(result => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${result.NoUrut}</td>
                    <td>${result.NPM}</td>
                    <td>${result.Nama}</td>
                    <td>${result.NPMASAL}</td>
                    <td>${result.TA}</td>
                    <td>${result.kurikulum}</td>
                    <td>${result.IDKAMPUS}</td>
                    <td>${result.LOKASI}</td>
                    <td>${result.fakultas}</td>
                    <td>${result.PRODI}</td>
                    <td>${result.KDPTASAL}</td>
                    <td>${result.UNIVERSITAS}</td>
                    <td>${result.IDPRODIASAL}</td>
                    <td>${result.PRODIASAL}</td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Add row click listeners
        function addRowClickListener() {
            const tableRows = document.querySelectorAll('#myExportableTable tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function () {
                    const npm = row.cells[1].innerText;
                    const nama = row.cells[2].innerText;
                    const npmasal = row.cells[3].innerText;
                    const ta = row.cells[4].innerText;
                    const idKampus = row.cells[6].innerText;
                    const fakultas = row.cells[8].innerText;
                    const prodi = row.cells[9].innerText;
                    const universitas = row.cells[11].innerText;
                    const prodiasal = row.cells[13].innerText;
                    const kurikulum = row.cells[5].innerText;
                    const lokasi = row.cells[7].innerText;
                    
                    var url = "{{ route('detailLDIKTI') }}";
                    var parameters = {
                        npm: npm,
                        nama: nama,
                        npmasal: npmasal,
                        fakultas: fakultas,
                        prodi: prodi,
                        universitas: universitas,
                        prodiasal: prodiasal,
                        idKampus: idKampus,
                        kurikulum: kurikulum,
                        lokasi: lokasi,
                        ta: ta
                    };
                    window.location.href = url + '?' + new URLSearchParams(parameters).toString();
                });
            });
        }
        addRowClickListener();
        const pageLinks = document.querySelectorAll('.page-link');

pageLinks.forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();

        const page = this.getAttribute('href').split('page=')[1];
        const form = document.querySelector('form');

        const universitas = document.querySelector('input[name="universitas"]').value;
        const ta = document.querySelector('input[name="ta"]').value;

        form.action = '{{ route("cetakLDIKTI") }}?page=' + page + '&universitas=' + universitas + '&ta=' + ta;
        form.submit();
    });
});
    });
</script>

@endsection
