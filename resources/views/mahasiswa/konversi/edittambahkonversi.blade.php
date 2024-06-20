@extends('admin.dashboard')

@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('changeView') }}">Edit Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Edit Konversi Nilai</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Edit Konversi Nilai </h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('sendEdit') }}" class="forms-sample">
                        @csrf
                        <input type="hidden" name="idPrimary" value="{{ request()->query('idPrimary') }}">
                        <input type="hidden" name="npm" value="{{ request()->query('npm') }}">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="idmkasal">IDMK Asal</label>
                                <input type="text" class="form-control" id="idmkasal" name="idmkasal" value="{{ $konversi->IDMKASAL }}" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="matkulasal">Mata Kuliah Asal</label>
                                <input type="text" class="form-control" id="matkulasal" name="matkulasal" value="{{ $konversi->MATAKULIAHASAL }}" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="sksasal">SKS Asal</label>
                                <input type="text" class="form-control" id="sksasal" name="sksasal" value="{{ $konversi->SKSASAL }}" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="idmk">IDMK</label>
                                <input type="text" class="form-control" id="idmk" name="idmk" placeholder="Mata Kuliah" value="{{ $konversi->IDMK }}" required>
                                <ul id="resultList" style="display: none;"></ul>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="matakuliah">Mata Kuliah</label>
                                <input type="text" class="form-control" id="matakuliah" name="matakuliah" placeholder="matakuliah" 
                                value="{{ $konversi->MATAKULIAH }}" readonly>
                                <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="sks">SKS</label>
                                <input type="text" class="form-control" id="sks" name="sks" value="{{ $konversi->SKS }}" readonly>
                                <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="semester">Semester</label>
                                <input type="text" class="form-control" id="semester" name="semester" value="{{ $konversi->SEMESTER }}" readonly>
                                <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="nilai">Nilai</label>
                                <select class="form-select" id="nilai" name="nilai" required>
                                    <option value="">-</option>
                                    <option value="A" {{ $konversi->NILAIAWAL == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="A-" {{ $konversi->NILAIAWAL == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ $konversi->NILAIAWAL == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B" {{ $konversi->NILAIAWAL == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="B-" {{ $konversi->NILAIAWAL == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="C+" {{ $konversi->NILAIAWAL == 'C+' ? 'selected' : '' }}>C+</option>
                                    <option value="C" {{ $konversi->NILAIAWAL == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="C-" {{ $konversi->NILAIAWAL == 'C-' ? 'selected' : '' }}>C-</option>
                                    <option value="D" {{ $konversi->NILAIAWAL == 'D' ? 'selected' : '' }}>D</option>
                                    <option value="E" {{ $konversi->NILAIAWAL == 'E' ? 'selected' : '' }}>E</option>
                                </select>
                            </div>
                            
                            <div class="mb-3 col-md-6">
                                <label for="hasil">Hasil Pengakuan</label>
                                <select class="form-select" id="hasil" name="hasil" required>
                                    <option value="">-</option>
                                    <option value="Di Akui Dengan Syarat Tertentu" {{ $konversi->HasilPengakuan == 'Di Akui Dengan Syarat Tertentu' ? 'selected' : '' }}>Di Akui Dengan Syarat Tertentu</option>
                                    <option value="Di Akui Lansung" {{ $konversi->HasilPengakuan == 'Di Akui Lansung' ? 'selected' : '' }}>Di Akui Lansung</option>
                                    <option value="Wajib" {{ $konversi->HasilPengakuan == 'Wajib' ? 'selected' : '' }}>Wajib</option>
                                </select>
                            </div>
                            
                            <div class="mb-3 col-md-6">
                                <label for="status">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">-</option>
                                    <option value="F" {{ $konversi->StatusKonversi == 'F' ? 'selected' : '' }}>Pasangan</option>
                                    <option value="T" {{ $konversi->StatusKonversi == 'T' ? 'selected' : '' }}>Tidak ada Pasangan</option>
                                </select>
                            </div>
                        </div>   
                        <button type="submit" class="btn btn-primary me-2 btn-lg float-end">Submit</button> 
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Include Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    jQuery(document).ready(function ($) {
        // Initialize flatpickr for date fields
        flatpickr('#tanggal', { dateFormat: 'Y-m-d' });
    });
    $(document).ready(function() {
    $('form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        var formData = $(this).serialize(); // Serialize form data
        console.log('Form data:', formData); // Log form data
        
        // Make AJAX request
        $.ajax({
            url: $(this).attr('action'), // URL from the form's action attribute
            method: $(this).attr('method'), // Method from the form's method attribute
            data: formData, // Form data
            success: function(response) {
                console.log('Success response:', response); // Log success response
                
                // Check if response contains success message
                if (response && response.success) {
                    // Show SweetAlert notification
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.success,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Redirect user to inputprofile page
                        window.location.href = "{{ route('changeView') }}?npm=" + response.npm;
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error); // Log AJAX error
            }
        });
    });
});

</script>

@endsection
