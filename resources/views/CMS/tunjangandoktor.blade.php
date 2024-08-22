@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Tunjangan Doktor Dosen</h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{route('simpanTunganDoktor')}}" method="POST" id="tunjhonor">
                        @csrf
                        <div class="col-md-2">
                            <label for="ta" class="form-label">TA</label>
                            <input type="text" class="form-control" id="ta" name="ta" required>
                        </div>
                        <div class="col-md-1">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" class="form-control" id="semester" name="semester" required>
                        </div>
                        <div class="col-md-2">
                            <label for="statusdosen" class="form-label">Status Dosen</label>
                            <select class="form-select" id="statusdosen" name="statusdosen" aria-label="Default select example" required>
                                <option value="" disabled selected>Pilih Status...</option>
                                @foreach($allJenis as $statusdosen)
                                    @php
                                        $selected = session('statusdosen') == $statusdosen->statusdosen ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $statusdosen->statusdosen }}" {{ $selected }}>{{ $statusdosen->statusdosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="pendidikan" class="form-label">Pendidikan Dosen</label>
                            <select class="form-select" id="pendidikan" name="pendidikan" aria-label="Default select example" required>
                                <option value="" disabled selected>Pilih Status...</option>
                                @foreach($allJenjang as $pendidikan)
                                    @php
                                        $selected = session('pendidikan') == $pendidikan->pendidikan ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $pendidikan->pendidikan }}" {{ $selected }}>{{ $pendidikan->pendidikan }}</option>
                                @endforeach
                            </select>
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
                <h4 class="mb-0">Tunjangan Doktor</h4>
                <hr class="my-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>TA</th>
                                <th>Semester</th>
                                <th>Status Dosen</th>
                                <th>Pendidikan Dosen</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tunjdoktor as $index => $j)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $j->ta }}</td>
                                <td>{{ $j->semester }}</td>
                                <td>{{ $j->statusdosen }}</td>
                                <td>{{ $j->pendidikan }}</td>
                                <td>{{ number_format($j->jumlah, 3, '.', '') }}</td>

                                <td>
                                    <button class="btn btn-primary activateButton" data-ta="{{ $j->ta }}"
                                         data-semester="{{ $j->semester }}" data-statusdosen="{{ $j->statusdosen }}"
                                         data-pendidikan="{{ $j->pendidikan }}">Activate</button>
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
                        window.location.href = "{{ route('showTunjanganDoktor') }}";
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

$(document).ready(function () {
    $('.activateButton').click(function () {
        var ta = $(this).data('ta');
        var semester = $(this).data('semester');
        var statusdosen = $(this).data('statusdosen');
        var pendidikan = $(this).data('pendidikan');

        $.ajax({
            url: "{{ route('activateTunganDoktor') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                ta: ta,
                semester: semester,
                statusdosen: statusdosen,
                pendidikan: pendidikan // Pastikan ini sesuai dengan data yang dikirimkan
            },
            success: function (response) {
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
                        text: response.error || 'Failed to update Tunjangan Doktor',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while updating Tunjangan Doktor',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

</script>

@endsection
