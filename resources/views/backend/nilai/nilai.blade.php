@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-0">Analisa Nilai Mahasiswa /Kelas</h4>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('submit.form') }}" class="forms-sample">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                <label for="idkampus" class="form-label">ID Kampus </label>
                                <select class="form-select" id="idkampus" name="idkampus" aria-label="Default select example" required>
                                    <option value="" disabled selected>Choose ID Kampus...</option>
                                    @foreach($IdKampus as $data)
                                        <option value="{{ $data->idkampus }}" data-lokasi="{{ $data->lokasi }}">{{ $data->idkampus }} - {{ $data->lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                <label for="prodi" class="form-label">Prodi</label>
                                <select class="form-select" id="prodi" name="prodi" aria-label="Default select example" required>
                                    <option value="" disabled selected>Choose Prodi...</option>
                                    @foreach($Prodis as $prodi)
                                    <option value="{{ $prodi->prodi }}">{{ $prodi->prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div> 
                        
                        
                            <div class="mb-3 col-md-7">
                                <label for="ta" class="form-label">TA</label>
                                <input type="text" name="ta" class="form-control" id="ta" placeholder="ta" required>
                            </div>
                            
                            <div class="mb-3 col-md-7">
                                <label for="semester" class="form-label">Semester</label>
                                <input type="text" name="semester" class="form-control" id="semester" placeholder="semester" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg float-end">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
