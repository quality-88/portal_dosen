@extends('admin.dashboard')

@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profilInput') }}">Edit Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">EDIT PENELITIAN</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">EDIT PENELITIAN </h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('editPenelitian') }}" class="forms-sample">
                        @csrf
                      <!-- Input tersembunyi untuk menangkap idDosen dari URL -->
                        <input type="hidden" name="idDosen" value="{{ request()->query('idDosen') }}">
                        <input type="hidden" name="idPrimary" value="{{ request()->query('idPrimary') }}">
                        <div class="row">
                            <div class="mb-3 col-md-7">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" id="judul" placeholder="judul" value="{{ $penelitian->JUDUL }}" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="bidang" class="form-label">Bidang</label>
                                <input type="text" name="bidang" class="form-control" id="bidang" placeholder="bidang" value="{{ $penelitian->BIDANG }}" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" id="lokasi" placeholder="lokasi" value="{{ $penelitian->LOKASI }}" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="text" name="tahun" class="form-control" id="tahun" placeholder="tahun" value="{{ $penelitian->TAHUN }}" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="publikasi" class="form-label">Publikasi</label>
                                <input type="text" name="publikasi" class="form-control" id="publikasi" placeholder="publikasi" value="{{ $penelitian->PUBLIKASI }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
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
                        window.location.href = "{{ route('profilInput') }}";
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
