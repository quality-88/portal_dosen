@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Honor Pokok Dosen</h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{ route('simpanHonorPokok') }}" method="POST" id="tunjakademik">
                        @csrf
                        <div class="col-md-2">
                            <label for="ta" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" required>
                        </div>
                        <div class="col-md-1">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control" id="semester" name="semester" required>
                        </div>
                        <div class="col-md-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-control select2" id="level" name="level" required>
                                <option value="" disabled selected>Pilih Level</option>
                                @foreach($allLevel as $level)
                                    <option value="{{ $level->leveldosen }}" data-jabatan="{{ $level->jabatanakademik }}">{{ $level->leveldosen }} - {{ $level->jabatanakademik }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="jabatanakademik" class="form-label">Jabatan Akademik</label>
                            <input type="text" class="form-control" id="jabatanakademik" name="jabatanakademik" readonly>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="text" class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>   
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0">Honor Pokok Dosen</h4>
                <hr class="my-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>TA</th>
                                <th>Semester</th>
                                <th>Level Dosen</th>
                                <th>Jabatan Akademik</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leveldosen as $index => $j)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $j->TA }}</td>
                                    <td>{{ $j->semester }}</td>
                                    <td>{{ $j->leveldosen }}</td>
                                    <td>{{ $j->jabatanakademik }}</td>
                                    <td>{{ number_format($j->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-primary activateButton" 
                                                data-ta="{{ $j->TA }}"
                                                data-semester="{{ $j->semester }}"
                                                data-leveldosen="{{ $j->leveldosen }}" 
                                                data-jabatanakademik="{{ $j->jabatanakademik }}">Activate</button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include necessary libraries -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
    $('form').submit(function(event) {
        event.preventDefault(); // Mencegah form untuk disubmit secara normal
        
        var formData = $(this).serialize(); // Meng-serialize data form
        console.log('Data form:', formData); // Mencetak data form
        
        // Melakukan request AJAX
        $.ajax({
            url: $(this).attr('action'), // URL dari atribut action form
            method: $(this).attr('method'), // Metode dari atribut method form
            data: formData, // Data form
            success: function(response) {
                console.log('Respons sukses:', response); // Mencetak respons sukses
                
                // Memeriksa apakah respons mengandung pesan sukses
                if (response && response.success) {
                    // Menampilkan notifikasi SweetAlert untuk pesan sukses
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.success,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Mengarahkan pengguna ke halaman profilInput
                        window.location.href = "{{ route('showHonorPokok') }}";
                    });
                } else if (response && response.error) {
                    // Menampilkan notifikasi SweetAlert untuk pesan error
                    Swal.fire({
                        title: 'Error!',
                        text: response.error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false; // Menghentikan aksi default form submission
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error); // Mencetak error AJAX
            }
        });
    });
});
$(document).ready(function() {
      

        // Ketika pilihan dropdown level berubah
        $('#level').change(function() {
            var selectedOption = $(this).find('option:selected');
            var jabatanakademik = selectedOption.data('jabatan');
            
            // Set nilai jabatanakademik pada field yang sesuai
            $('#jabatanakademik').val(jabatanakademik);
        });
    });
    $(document).ready(function () {
    $('.activateButton').click(function () {
        var ta = $(this).data('ta');
        var semester = $(this).data('semester');
        var leveldosen = $(this).data('leveldosen'); // Pastikan ini adalah data yang benar
        var jabatanakademik = $(this).data('jabatanakademik'); // Pastikan ini adalah data yang benar

        console.log('Data yang dikirim:', { ta, semester, leveldosen, jabatanakademik }); // Debugging output

        $.ajax({
            url: "{{ route('activateHonorPokok') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                ta: ta,
                semester: semester,
                leveldosen: leveldosen,
                jabatanakademik: jabatanakademik
            },
            success: function (response) {
                console.log('Respons sukses:', response); // Debugging output
                if (response && response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.reload(); // Reload page after success
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.error || 'Failed to update Honor Pokok',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while updating Honor Pokok',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

</script>

@endsection
