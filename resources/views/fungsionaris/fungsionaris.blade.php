@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <h4 class="mb-0">Fungsionaris </h4>
                    <hr class="my-4">
                    <form class="row g-5" action="{{ route('insertJabatan') }}" method="POST" id="kaprodi" >

                        @csrf
                        
                        <div class="col-md-6">
                            <label for="idkampus" class="form-label">ID Kampus </label>
                            <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                <option value="" disabled selected>Choose ID Kampus...</option>
                                @foreach($allIdKampus as $data)
                                    <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}">{{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" onchange="updateFakultas()">
                                <option value="" disabled selected>Choose Prodi...</option>
                                
                                @foreach($allProdi as $data)
                                    <option value="{{ $data->prodi }}"data-idprodi="{{$data->idprodi}}">{{ $data->prodi }}-{{$data->idprodi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="idprodi" class="form-label">ID Prodi</label>
                            <input type="text" class="form-control" id="idprodi" name="idprodi" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="id_fakultas" class="form-label">ID Fakultas</label>
                            <input type="text" class="form-control" id="id_fakultas" name="id_fakultas" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="id" class="form-label">ID Dosen</label>
                            <input type="text" class="form-control" id="id" name="id" >
                            <!-- Input field tersembunyi untuk menyimpan namadosen -->
                            
                            <ul id="resultList" style="display: none;"></ul>

                        </div>
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="nidn" class="form-label">NIDN</label>
                            <input type="text" class="form-control" id="nidn" name="nidn" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" aria-label="Default select example">
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="jabatanfungsi" class="form-label">Jabatan Fungsionaris</label>
                            <select class="form-select" id="jabatanfungsi" name="jabatanfungsi" aria-label="Default select example">
                                <option value="" disabled selected>Pilih Jabatan...</option>
                                
                                <!-- Loop through allJabatan to display options -->
                                @foreach($allJabatan as $jabatan)
                                    <option value="{{ $jabatan->jabatanfungsi }}">{{ $jabatan->jabatanfungsi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Tanggal Mulai</label>
                            <input type="text" class="form-control" id="date" name="startDate" placeholder="Start Date" data-date-format="Y-m-d" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">Tanggal Selesai</label>
                            <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" data-date-format="Y-m-d" required>
                        </div>
                        <div class="col-md-12">
                            <!--<button type="button" onclick="submitForm()" class="btn btn-success">Submit</button>-->
                            <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>   
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="row">
        <div class="col-md-12" id="resultContainer">
        </div>
        </div>-->
</div>
        
                    <!-- Include Bootstrap and jQuery -->
                    <!-- Tambahkan di dalam tag <head> -->
                    <!-- Include jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                    <!-- Include Select2 -->
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                        <!-- Add these lines to the head section of your HTML document -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>

                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />                    
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                    <!-- Add this script at the bottom of your <head> section -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
                    <!-- Manually initialize the datepicker -->
                    <style>
                   #resultList {
  max-height: 200px; /* Set a maximum height for the list */
  overflow-y: auto; /* Add a scrollbar when the list overflows */
  position: absolute;
  width: 40%; /* Make the list full-width */
  z-index: 1000; /* Adjust the z-index to make sure the list appears above other elements */
  background-color: #ffff; /* Set a background color */
  border-radius: 5px; /* Optional: Add border-radius for rounded corners */
}

#resultList li {
  padding: 8px; /* Add padding to each list item */
  cursor: pointer; /* Change the cursor to a pointer for better user experience */
}
                    </style>
                    <script>
                        
         window.jsPDF = window.jspdf.jsPDF;                                   
        // Function to update Fakultas based on selected Prodi
        function updateFakultas() {
            var selectedProdi = $('#prodi').val();
                    
            if (selectedProdi) {
                $.ajax({
                    url: 'formkaprodi/fetchFakultas',
                    method: 'GET',
                    data: { prodi: selectedProdi },
                    success: function (response) {
                        if (response.no_data) {
                            alert('No data found for the selected Prodi. Please choose a different Prodi.');
                        } else {
                            $('#id_fakultas').val(response.idfakultas);
                            $('#fakultas').val(response.fakultas);
                        }
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }    $('#iddosen').val('');
                $('#nama').val('');
        }
        
                        jQuery(document).ready(function ($) {
                            // Initialize flatpickr for date fields
                            flatpickr('#date', { dateFormat: 'Y-m-d' });
                            flatpickr('#endDate', { dateFormat: 'Y-m-d' });
                    
                            // Event handlers
                            $('#idkampus').change(function () {
                                var idKampus = $(this).find(':selected').val();
                                var lokasi = $(this).find(':selected').data('lokasi');
                    
                                console.log('ID Kampus:', idKampus);
                                console.log('Lokasi:', lokasi);
                    
                                $("#lokasi").val(lokasi);
                            });
                    
                            $('#prodi').change(function () {
                                var idprodi = $(this).find(':selected').data('idprodi');
                                $("#idprodi").val(idprodi);
                                updateFakultas();
                            });
                            $('#jabatanfungsi').change(function () {
                            var jabatanfungsi = $(this).find(':selected').val(); // Mengambil nilai value dari opsi yang dipilih
                            console.log('Jabatan Fungsionaris:', jabatanfungsi);
                            $("#jabatanfungsi").val(jabatanfungsi); // Menetapkan nilai opsi yang dipilih ke input field
                        });

                            // Ganti event handler untuk input
                            // Ganti event handler untuk input
                    $('#id').on('input', function () {
                        var searchQuery = $(this).val();
                    
                        if (searchQuery.length >= 4) {
                            // Lakukan permintaan AJAX ke server untuk mencari Dosen
                            $.ajax({
                                url: 'formkaprodi/findDosen',
                                method: 'GET',
                                data: { term: searchQuery },
                                success: function (data) {
                                    // Bersihkan elemen daftar hasil pencarian sebelum menambahkan yang baru
                                    var resultList = $('#resultList');
                                    resultList.empty();
                                
                                    // Tampilkan hasil langsung sebagai JSON untuk debug
                                    console.log('Server Response:', data);
                                
                                    // Tampilkan daftar hasil
                                    resultList.show();
                                
                                    // Tambahkan setiap hasil ke dalam daftar
                                    data.forEach(function(result) {
                                    resultList.append('<li data-id="' + result.id + '" data-nidn="' + result.nidnntbdos + 
                                    '" data-nip="' + result.nip + '">' 
                                    + result.id + ' - ' + result.nama + '</li>');
                                });
                                },
                                error: function (error) {
                                    console.error('Error fetching data:', error);
                                }
                            });
                        } else {
                            // Sembunyikan daftar jika input kurang dari 2 karakter
                            $('#resultList').hide();
                        }
                    });
                    $(document).on('click', '#resultList li', function() {
                    var fullName = $(this).text();
                    var splitResult = fullName.split(' - ');

                    var id = splitResult.length > 1 ? splitResult[0] : '';
                    var nama = splitResult.length > 1 ? splitResult[1] : '';
                    var nidn = $(this).data('nidn');
                    var nip = $(this).data('nip'); // Retrieve 'nip' from data attribute

                    $('#id').val(id);
                    $('#nama').val(nama);
                    $('#nidn').val(nidn);
                    $('#nip').val(nip); // Set 'nip' value to input field

                    if (!id) {
                        $('#nama').val('');
                    }
                
                    $('#resultList').hide();
                });
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
                        window.location.href = "{{ route('showViewJabatan') }}";
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
