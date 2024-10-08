@extends('admin.dashboard')

@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profilInput') }}">Edit Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pendidikan Dosen</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">PROGRAM PENDIDIKAN </h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('editMataKuliah') }}" class="forms-sample">
                        @csrf
                        <!-- Input tersembunyi untuk menangkap idPrimary -->
                        <input type="hidden" name="idPrimary" value="{{ request()->query('idPrimary') }}">
                        <!--<input type="hidden" name="idDosen" value="">-->
                        <!--<input type="hidden" name="itemNo" value="">-->
                        <div class="row">
                            <div class="mb-3 col-md-7">
                                <label for="idmk" class="form-label">Mata Kuliah</label>
                                <input type="text" class="form-control" id="idmk" name="idmk" placeholder="Mata Kuliah">
                                <!-- Input field tersembunyi untuk menyimpan namadosen -->
                                <input type="hidden" id="matakuliah" name="matakuliah">
                                <ul id="resultList" style="display: none;"></ul>
                            </div>
                            <!--<div class="mb-3 col-md-7">
                                <label for="prodi" class="form-label">Prodi</label>
                                <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()">
                                    <option value="" disabled selected>Choose Prodi...</option>                                                                                                                 
                                </select>
                            </div>-->
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
$('#idmk').on('input', function () {
       var searchQuery = $(this).val();
       if (searchQuery.length >= 4) {
           // Lakukan permintaan AJAX ke server untuk mencari Dosen
           $.ajax({
               url: '/input/ubahmatakuliah/searchMatkul',
               method: 'GET',
               data: { term: searchQuery },
               success: function (data) {
                   var resultList = $('#resultList');
                   resultList.empty();
                   console.log('Server Response:', data);
                   resultList.show();
                  data.forEach(function (result) {
                      resultList.append('<li data-id="' + result.idmk + '">' + result.idmk + ' - ' + result.matakuliah + '</li>');
                  });
               },
               error: function (error) {
                   console.error('Error fetching data:', error);
               }
           });
       } else {
           $('#resultList').hide();
       }
   });
   $(document).on('click', '#resultList li', function () {
       var fullName = $(this).text();
       var splitResult = fullName.split(' - ');
       var idmk = splitResult.length > 1 ? splitResult[0] : '';
       var matakuliah = splitResult.length > 1 ? splitResult[1] : '';
       console.log('id dosen:', idmk);
       console.log('Nama:', matakuliah);
       $('#idmk').val(idmk);
       $('#matakuliah').val(matakuliah);
       if (!idmk) {
           $('#matakuliah').val('');
       }
       $('#resultList').hide();
   });
   
</script>

@endsection
