@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Edit Kurikulum</h4>
                    <form action="{{ route('updateKurikulum', ['id' => $kurikulum->idPrimary]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="idPrimary" value="{{ $kurikulum->idPrimary }}">       
                        <div class="form-group row mb-3">
                            <label class="col-sm-1 col-form-label">Kurikulum</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="kurikulum" name="kurikulum" 
                                    value="{{ $kurikulum->kurikulum }}" required>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row mb-3">
                            <label class="col-sm-1 col-form-label">Semester</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="semester" name="semester" 
                                    value="{{ $kurikulum->SEMESTER }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-1 col-form-label">SKS</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="sks" name="sks" 
                                    value="{{ $kurikulum->SKS }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-1 col-form-label">IDMK</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="idmk" name="idmk" placeholder="Mata Kuliah"
                                    value="{{ $kurikulum->IDMK }}" required>
                                    <ul id="resultList" style="display: none;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-1 col-form-label">Mata Kuliah</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="matakuliah" name="matakuliah" placeholder="matakuliah"
                                    value="{{ $kurikulum->matakuliah }}" required readonly>
                                    <ul id="resultList" style="display: none; overflow-y: auto;"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary float-end">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Include Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />                    
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $('#idmk').on('input', function () {
        var searchQuery = $(this).val();

        if (searchQuery.length >= 4) {
            var prodi = "{{ session('prodi') }}";

            $.ajax({ 
                url: '{{ route("searchIdmk") }}',
                method: 'GET',
                data: { term: searchQuery},
                success: function (data) {
                    var resultList = $('#resultList');
                    resultList.empty();

                    console.log('Server Response:', data);

                    resultList.show();

                    data.forEach(function (result) {
                        resultList.append('<li data-id="' + result.IDMK + '">' + result.IDMK + ' - ' + result.MATAKULIAH + '</li>');
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

        console.log('idmk:', idmk);
        console.log('Matakuliah:', matakuliah);

        $('#idmk').val(idmk);
        $('#matakuliah').val(matakuliah);

        if (!idmk) {
            $('#matakuliah').val('');
        }

        $('#resultList').hide();
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
                        window.location.href = "{{ route('showKurikulum') }}";
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
