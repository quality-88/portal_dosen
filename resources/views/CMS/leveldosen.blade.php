@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Level Dosen</h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{ route('simpanLevelDosen') }}" method="POST" id="leveldosen">
                        @csrf
                        <div class="col-md-1">
                            <label for="level" class="form-label">Level</label>
                            <input type="text" class="form-control" id="level" name="level" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jenjangakademik" class="form-label">Pendidikan</label>
                            <select class="form-select" id="jenjangakademik" name="jenjangakademik" aria-label="Default select example" required>
                                <option value="" disabled selected>Pilih Pendidikan...</option>
                                @foreach($allJenjang as $jenjangakademik)
                                    @php
                                        $selected = session('jenjangakademik') == $jenjangakademik->jenjangakademik ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $jenjangakademik->jenjangakademik }}" 
                                        {{ $selected }}>{{ $jenjangakademik->jenjangakademik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="nidn" class="form-label">NIDN</label>
                            <select class="form-select" id="nidn" name="nidn" aria-label="Default select example" required>
                                <option value="" disabled selected>...</option>
                                <option value="Y">Ada</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusdosen" class="form-label">Status Dosen</label>
                            <select class="form-select" id="statusdosen" name="statusdosen" aria-label="Default select example" required>
                                <option value="" disabled selected>Pilih Status...</option>
                                @foreach($allStatus as $statusdosen)
                                    @php
                                        $selected = session('statusdosen') == $statusdosen->statusdosen ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $statusdosen->statusdosen }}" {{ $selected }}>{{ $statusdosen->statusdosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="jabatanakademik" class="form-label">Jabatan Akademik</label>
                            <select class="form-select" id="jabatanakademik" name="jabatanakademik" aria-label="Default select example" required>
                                <option value="" disabled selected>Pilih Jabatan...</option>
                                @foreach($allJabat as $jabatanakademik)
                                    @php
                                        $selected = session('jabatanakademik') == $jabatanakademik->jabatanakademik ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $jabatanakademik->jabatanakademik }}" {{ $selected }}>{{ $jabatanakademik->jabatanakademik }}</option>
                                @endforeach
                            </select>
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
                <h4 class="mb-0">Level Dosen</h4>
                <hr class="my-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Level Dosen</th>
                                <th>Pendidikan</th>
                                <th>NIDN</th>
                                <th>Status Dosen</th>
                                <th>Jabatan Akademik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leveldosen as $index => $j)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $j->leveldosen }}</td>
                                    <td>{{ $j->pendidikan }}</td>
                                    <td>{{ $j->nidn }}</td>
                                    <td>{{ $j->statusdosen }}</td>
                                    <td>{{ $j->jabatanakademik }}</td>
                                    
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
                        window.location.href = "{{ route('showLevelDosen') }}";
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


</script>

@endsection
