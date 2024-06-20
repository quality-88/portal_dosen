@extends('admin.dashboard')

@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profilInput') }}">Edit Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Edit Jabatan</h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('ubahJabatan') }}" class="forms-sample">
                        @csrf
                      <!-- Input tersembunyi untuk menangkap idDosen dari URL -->
                        
                        <input type="hidden" name="idPrimary" value="{{ request()->query('idPrimary') }}">
                        <div class="row">
                            <div class="mb-3 col-md-7">
                                <label for="jabatan" class="form-label">Jabatan Dosen</label>
                                <select class="form-select" id="jabatan" name="jabatan" aria-label="Default select example">
                                    <option value="" disabled selected>Choose Jabatan...</option>
                                    @foreach($allJabatan as $jabatanakademik)
                                  <option value="{{ $jabatanakademik->jabatanakademik }}"
                                          {{ $jabatanakademik->jabatanakademik == $jabat->Jabatan ? 'selected' : '' }}>
                                      {{ $jabatanakademik->jabatanakademik }}
                                  </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="kum" class="form-label">KUM </label>
                                <select class="form-select" id="kum" name="kum" aria-label="Default select example" required>
                                    <option value="" disabled selected>KUM...</option>
                                    @foreach($allKum as $kum)
                                        <option value="{{ $kum->kum }}" {{ $kum->kum == $jabat->KUM ? 'selected' : '' }}>{{ $kum->kum }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="nomorsk" class="form-label">No SK</label>
                                <input type="text" name="nomorsk" class="form-control" id="nomorsk" placeholder="nomorsk" 
                                value="{{ $jabat->NOSK }}" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="text" name="tanggal" class="form-control" id="tanggal" placeholder="tanggal" 
                                value="{{ $jabat->Tanggal }}">
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="tmt" class="form-label">TMT</label>
                                <input type="text" name="tmt" class="form-control" id="tmt" placeholder="tmt" 
                                value="{{ $jabat->TMT }}">
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
    $('#jabatan').change(function () {
     var jabatan = $(this).find(':selected').val();
     console.log('ID Kampus:', jabatan);
    });
    $('#kum').change(function () {
    var kum = $(this).find(':selected').val();
    console.log('ID Kampus:', kum);
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
