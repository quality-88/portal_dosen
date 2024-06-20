@extends('admin.dashboard')

@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profilInput') }}">Edit Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Tambah Tugas Belajar</h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('tambahTugasBelajar') }}" class="forms-sample">
                        @csrf
                      <!-- Input tersembunyi untuk menangkap idDosen dari URL -->
                        <input type="hidden" name="idDosen" value="{{ request()->query('idDosen') }}">
                        <input type="hidden" name="idPrimary" value="{{ request()->query('idPrimary') }}">
                        <div class="row">
                            <div class="mb-3 col-md-7">
                                <label for="nidn" class="form-label">NIDN </label>
                                <input type="text" name="nidn" class="form-control" id="nidn" placeholder="nidn" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="tugas" class="form-label">Tugas </label>
                                <input type="text" name="tugas" class="form-control" id="tugas" placeholder="tugas" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="nomorsk" class="form-label">No SK</label>
                                <input type="text" name="nomorsk" class="form-control" id="nomorsk" placeholder="nomorsk" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="tahunmulai" class="form-label">Tahun Mulai</label>
                                <input type="text" name="tahunmulai" class="form-control" id="tahunmulai" placeholder="tahunmulai" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="namapt" class="form-label">Nama PT</label>
                                <input type="text" name="namapt" class="form-control" id="namapt" placeholder="namapt" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="kota" class="form-label">Kota</label>
                                <input type="text" name="kota" class="form-control" id="kota" placeholder="kota" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" id="keterangan" placeholder="keterangan" required>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="sumberdana" class="form-label">Sumber Dana</label>
                                <input type="text" name="sumberdana" class="form-control" id="sumberdana" placeholder="sumberdana" required>
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
    var jabatan = $(this).find(':selected').val();
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
