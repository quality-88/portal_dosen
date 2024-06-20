@extends('admin.dashboard')

@section('admin')

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profilInput') }}">Edit Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mata Kuliah</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Penambahan Mata Kuliah </h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('tambahKurikulum') }}" class="forms-sample">
                        @csrf
                      <!-- Input tersembunyi untuk menangkap idDosen dari URL -->
                        <input type="hidden" name="kurikulum" value="{{ request()->query('kurikulum') }}">
                        <input type="hidden" name="idKampus" value="{{ request()->query('idKampus') }}">
                        <input type="hidden" name="idFakultas" value="{{ request()->query('idFakultas') }}">
                        <input type="hidden" name="prodi" value="{{ request()->query('prodi') }}">
                            <div class="mb-3 col-md-7">
                                <label for="idmk" class="form-label">Mata Kuliah</label>
                                <input type="text" class="form-control" id="idmk" name="idmk" placeholder="Mata Kuliah">
                                
                            
                                <ul id="resultList" style="display: none;"></ul>
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="matakuliah" class="form-label">Mata Kuliah</label>
                                <input type="text" class="form-control" id="matakuliah" name="matakuliah" placeholder="matakuliah" readonly>
                                <ul id="resultList" style="display: none; overflow-y: auto;"></ul>

                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="sks" class="form-label">SKS</label>
                                <input type="text" class="form-control" id="sks" name="sks" placeholder="SKS" >
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="semester" class="form-label">Semester</label>
                                <input type="text" class="form-control" id="semester" name="semester" placeholder="Semester" >
                            </div>
                            <div class="mb-3 col-md-7">
                                <label for="pilihan" class="form-label">Mata Kuliah Pilihan</label>
                                <select class="form-select" id="pilihan" name="pilihan" required>
                                    <option value="F">Tidak</option>
                                    <option value="T">Ya</option>
                                </select>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.0/sweetalert2.js" integrity="sha512-n+FwLK5s6dd4XL68lrwGn1j9TSCTFA15TgF7KbcShrGV7Ma761MniYPUAz0PPipTi18IXLbr+Ag9cxrEvIeASw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<style>
    #resultList {
    max-height: 200px; /* Tentukan ketinggian maksimum */
    overflow-y: auto; /* Tambahkan scrollbar jika terlalu banyak konten */
    position: absolute;
    width: 100%;
    z-index: 1000;
    background-color: #ffff;
    border-radius: 5px;
    padding: 8px;
    cursor: pointer;
}

</style>
<script>
    jQuery(document).ready(function ($) {
        // Initialize flatpickr for date fields
        flatpickr('#tanggal', { dateFormat: 'Y-m-d' });
    });

    $(document).ready(function() {
    // Event handler for form submission
    $('form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        var formData = $(this).serialize(); // Serialize form data

        // Make AJAX request
        $.ajax({
            url: $(this).attr('action'), // URL from the form's action attribute
            method: $(this).attr('method'), // Method from the form's method attribute
            data: formData, // Form data
            success: function(response) {
                // Check if response contains success message
                if (response && response.success) {
                    // Show success message using SweetAlert2
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Redirect user to kurikulum.blade.php and pass added data as query parameters
                        var addedData = response.data; // Assuming the added data is sent back in the response
                        var queryParams = $.param(addedData); // Serialize added data as query parameters
                        window.location.href = "{{ route('viewKurikulum') }}" + queryParams;
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error); // Log AJAX error
            }
        });
    });
});


    // Event handlers
    $('#idkampus').change(function () {
        var idKampus = $(this).find(':selected').val();
        var lokasi = $(this).find(':selected').data('lokasi');
        console.log('ID Kampus:', idKampus);
        console.log('Lokasi:', lokasi);
        $("#lokasi").val(lokasi);
    });

    function updateFakultas() {
        var selectedProdi = $('#prodi').val();

        if (selectedProdi) {
            $.ajax({
                url: 'tambahkurikulum/fetchFakultas',
                method: 'GET',
                data: { prodi: selectedProdi },
                success: function (response) {
                    if (response.no_data) {
                        alert('No data found for the selected Prodi. Please choose a different Prodi.');
                    } else {
                        $('#id_fakultas').val(response.idfakultas);
                        $('#fakultas').val(response.fakultas);

                        // Add this line to trigger searchIdmk when prodi is selected
                        $('#idmk').trigger('input');
                    }
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    }

    $('#idmk').on('input', function () {
        var searchQuery = $(this).val();

        if (searchQuery.length >= 4) {
            // Lakukan permintaan AJAX ke server untuk mencari matakuliah
            var prodi = "{{ session('prodi') }}";

            $.ajax({
                url: 'tambahkurikulum/searchIdmk',
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

</script>

@endsection
