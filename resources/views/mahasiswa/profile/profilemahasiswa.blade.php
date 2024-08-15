@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    
      <!-- Main Form-->
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="mb-0">Profile Mahasiswa</h4>
                                <hr class="my-4">
                                <form class="row g-3" action="{{ route ('viewProfileMahasiswa')}}" method="POST">
                                    @csrf
                                    <div class="form-group row mb-3">
                                        <div class ="mb-4"></div>
                                        <label class="col-sm-1 col-form-label">NPM</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="int" class="form-control" id="npm" name="npm" placeholder="Masukkan Nama atau NPM" 
                                                value="{{ old('npm', isset($npm) ? $npm : session('npm')) }}" required>
                                                <ul id="resultList" style="display: none;"></ul>
                                            </div>
                                        </div>
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
            </div>
        </div>
        @if(isset($profile))
        <div class ="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id ="formSimpan" action="{{ route('updateProfileMahasiswa') }}" class="forms-sample">
                            @csrf
                        <!-- Data Diri -->
                        <div class="row">
                            <hr class="my-3">
                            <div class="label-container">
                                <h6 class="label" onclick="toggleContent('datadiri')"> <span class="toggle-symbol" id="datadiriToggle">+</span> Data Diri </h6>
                                    <div class="mb-3"></div>
                                <div class="content" id="datadiriContent">
                            <div class ="row">
                                <input type="hidden" name="npm" value="{{ $profile->npm }}"> 
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">NIK Mahasiswa</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nik" value="{{ $profile->nik ?? '' }}" >
                                        </div>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="namalengkap" value="{{ $profile->namalengkap ?? '' }}" >
                                        </div>
                                    </div>
                                </div><!-- Col -->
                                
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Output</label>
                                            <input type="text" class="form-control" name="nama" value="{{ $profile->nama ?? '' }}"  >
                                        </div>
                                    </div><!-- Col -->
                                         </div>
                                      
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tempat Lahir</label>
                                                    <input type="text" class="form-control" value="{{ $profile->tempatlahir ?? '' }}" name ="tempatlahir" >
                                                </div>
                                            </div><!-- Col -->
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Lahir</label>
                                                    <input type="text" class="form-control" id="tanggallahir" name="tanggallahir" value="{{ $profile->tanggallahir }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Masuk</label>
                                                    <input type="text" class="form-control" id="tglmasuk" name="tglmasuk" value="{{ $profile->tglmasuk }}">
                                                </div>
                                            </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="email" id="email" value="{{ $profile->email ?? '' }}" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Hobi</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="hobi" value="{{ $profile->hobi ?? '' }}" >
                                                        </div>       
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Kelamin</label>
                                                    <select class="form-control" name="jeniskelamin" required>
                                                        <option value="Lk" {{ $profile->jeniskelamin == 'Lk' ? 'selected' : '' }}>Laki-Laki</option>
                                                        <option value="PR" {{ $profile->jeniskelamin == 'PR' ? 'selected' : '' }}>Perempuan</option>
                                                    </select>
                                                </div>
                                                    </div>
                                                
                                            
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status </label>
                                                        <select class="form-control" name="status" required>
                                                            <option value="M" {{ $profile->status == 'M' ? 'selected' : '' }}>Menikah</option>
                                                            <option value="S" {{ $profile->status == 'S' ? 'selected' : '' }}>Lajang</option>
                                                            <option value="N" {{ $profile->status == 'N' ? 'selected' : '' }}>Duda/Janda</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Agama </label>
                                                        <select class="form-select" id="agama" name="agama" aria-label="Default select example">
                                                            <option value="" disabled selected>Choose Agama......</option>
                                                            @foreach($allAgama as $agama)
                                                                <option value="{{ $agama->agama }}" 
                                                                    {{ $agama->agama == $profile->agama ? 'selected' : '' }}>
                                                                    {{ $agama->agama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                                                                 
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="col-mb-3">
                                                            <label class="form-label">Telepon</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="notelp" name="notelp" value="{{ $profile->notelp ?? '' }}" >      
                                                            </div>
            
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nomor HP</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="hp" name="hp" value="{{ $profile->hp ?? '' }}" >            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            
                                          <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kewarganegaraan</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="warga" name="warga" value="{{ $profile->warga ?? '' }}" >         
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label">Tipe Kelas</label>
                                                <select class="form-control" id="tipekelas" name="tipekelas" required>
                                                    <option value="PINDAHAN" {{ $profile->tipekelas == 'PINDAHAN' ? 'selected' : '' }}>PINDAHAN</option>
                                                    <option value="BARU KHUSUS" {{ $profile->tipekelas == 'BARU KHUSUS' ? 'selected' : '' }}>BARU KHUSUS</option>
                                                    <option value="PINDAHAN KHUSUS" {{ $profile->tipekelas == 'PINDAHAN KHUSUS' ? 'selected' : '' }}>PINDAHAN KHUSUS</option>
                                                    <option value="BARU REGULER" {{ $profile->tipekelas == 'BARU REGULER' ? 'selected' : '' }}>BARU REGULER</option>
                                                    <option value="BARU SEMENTARA" {{ $profile->tipekelas == 'BARU SEMENTARA' ? 'selected' : '' }}>BARU SEMENTARA</option>
                                                    <option value="PINDAHAN REGULER" {{ $profile->tipekelas == 'PINDAHAN REGULER' ? 'selected' : '' }}>PINDAHAN REGULER</option>
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Sumber Biaya</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="sumber" name="sumber" value="{{ $profile->sumber ?? '' }}" >            
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Bekerja</label>
                                                    <select class="form-control" name="bekerja" id="bekerja" required>
                                                        <option value="T" {{ $profile->bekerja == 'T' ? 'selected' : '' }}>Tidak</option>
                                                        <option value="Y" {{ $profile->bekerja == 'Y' ? 'selected' : '' }}>Iya</option>            
                                                    </select>
                                                </div>
                                                
                                            </div>
                                           
                                            </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Status Mahasiswa</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="statusmhs" name="statusmhs" value="{{ $profile->statusmhs ?? '' }}" >            
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Dosen Pembimbing</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="dosenpembimbing" name="dosenpembimbing" 
                                                        value="{{ $profile->dosenpembimbing ?? '' }}">
                                                        <!-- Input field tersembunyi untuk menyimpan namadosen -->
                                                        
                                                        <ul id="resultList1" style="display: none;"></ul>
                            
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Dosen Pembimbing</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="namadosen"  id="namadosen" value="{{ $profile->namadosen ?? '' }}" >            
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            </div>
                                           
                                            </div>
                                            <button class="btn btn-primary" id="updateProfile">Ubah Profil</button>
                                                </div>
                                                
                                            </div> 
                                            
                                            </div> 
                                        </div>
                                        </div>
                                    </div> 
                                </div>
                        
                               <!-- Akademik -->
                               <div class="row mt-4">
           
                                   <div class="col-md-12 grid-margin stretch-card">
                                       <div class="card">
                                           <div class="card-body">
                               
                               <div class="mb-3">
                               <div class="label-container">
                                   <h6 class="label" onclick="toggleContent('akademik')">
                                       <span class="toggle-symbol" id="akademikToggle">+</span>
                                       Data Kampus 
                                   </h6>
                                   <div class="mb-3"></div>
                                   <div class="content" id="akademikContent">
                                       <div class ="row">
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">ID Kampus</label>
                                           <input type="text" class="form-control" name ="idkampus" value="{{ $profile->idkampus ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                                   
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Lokasi</label>
                                           <input type="text" class="form-control" id="lokasi" name ="lokasi" value="{{ $profile->lokasi ?? '' }}" >
                                       </div>
                                   </div>
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Universitas</label>
                                           <input type="text" class="form-control" id="universitas" name ="universitas" value="{{ $profile->universitas ?? '' }}" >
                                       </div>
                                   </div>
                                   </div><!-- Col -->
                                   <div class ="row">
                                   <div class="col-sm-3">
                                       <div class="col-mb-3">
                                       <label class="form-label">ID Fakultas</label>
                                       <input type="text" class="form-control" id="idfakultas" name="idfakultas" value="{{ $profile->idfakultas ?? '' }}" >
                                       </div>
                                   </div>
                                   <div class="col-sm-3">
                                       <div class="col-mb-3">
                                       <label class="form-label">Fakultas</label>
                                       <input type="text" class="form-control" id="fakultas"  name="fakultas" value="{{ $profile->fakultas ?? '' }}" >
                                       </div>
                                   </div>
                                                             
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Prodi</label>
                                           <input type="text" class="form-control" id="prodi" name ="prodi" value="{{ $profile->prodi ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                               </div><!-- Row -->
                               <div class="row">                                 
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Angkatan</label>
                                           <input type="text" class="form-control" id="ta" name ="ta" value="{{ $profile->ta ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Semester</label>
                                           <input type="text" class="form-control" id="semester" name ="semester" value="{{ $profile->semester ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                               </div><!-- Row -->
                               <div class="row">                                 
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Username</label>
                                           <input type="text" class="form-control" id="username" name ="username" value="{{ $profile->username ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Password</label>
                                           <input type="text" class="form-control" id="passwrd" name ="passwrd" value="{{ $profile->passwrd ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                               </div><!-- Row -->
                               <button class="btn btn-primary" id="updateProfile">Ubah Profil</button>
                           </div>
                       </div>
                          </div>
                               </div>
                                   </div>        
                           </div>
                       </div> 
                           <!-- Pendidikan -->
                           <div class="row">
           
                               <div class="col-md-12 grid-margin stretch-card">
                                   <div class="card">
                                       <div class="card-body">
                           <div class="mb-3">
                           <div class="label-container">                                   
                               
                               <h6 class="label" onclick="toggleContent('pendidikan')">
                                   <span class="toggle-symbol" id="pendidikanToggle">+</span>
                                   Data Sekolah</h6>
                               <div class="content" id="pendidikanContent">
                                   <div class="mb-3"></div>
                                   <div class="row"> 
                               <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Jenis SLTU</label>
                                           <input type="text" class="form-control" id="sekolah" name ="sekolah"  value="{{ $profile->sekolah ?? '' }}">
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">ID SLTU</label>
                                           <input type="text" class="form-control" id="idsekolah" name ="idsekolah"  value="{{ $profile->idsekolah ?? '' }}">
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Nama Sekolah</label>
                                           <input type="text" class="form-control" id="namasekolah" name ="namasekolah"  value="{{ $profile->namasekolah ?? '' }}">
                                       </div>
                                   </div><!-- Col -->
                               </div>
                                   <div class="row">                                 
                                       <div class="col-sm-3">
                                           <div class="mb-3">
                                               <label class="form-label">Alamat</label>
                                               <input type="text" class="form-control" id="alamatsekolah" name ="alamatsekolah" value="{{ $profile->alamatsekolah ?? '' }}" >
                                           </div>
                                       </div><!-- Col -->
                                       <div class="col-sm-3">
                                           <div class="mb-3">
                                               <label class="form-label">Kecamatan</label>
                                               <input type="text" class="form-control" id="kecamatan" name ="kecamatan" value="{{ $profile->kecamatan ?? '' }}" >
                                           </div>
                                       </div><!-- Col -->
                                       <div class="col-sm-3">
                                           <div class="mb-3">
                                               <label class="form-label">Kabupaten</label>
                                               <input type="text" class="form-control" id="kabupaten" name ="kabupaten" value="{{ $profile->kabupaten ?? '' }}" >
                                           </div>
                                       </div><!-- Col -->
                                       <div class="col-sm-3">
                                           <div class="mb-3">
                                               <label class="form-label">Provinsi</label>
                                               <input type="text" class="form-control" id="provinsi" name ="provinsi" value="{{ $profile->provinsi ?? '' }}" >
                                           </div>
                                       </div><!-- Col -->
                                   </div><!-- Row -->
                                   <div class="row">                                 
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Jurusan</label>
                                           <input type="text" class="form-control" id="jurusan" name ="jurusan" value="{{ $profile->jurusan ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-3">
                                       <div class="mb-3">
                                           <label class="form-label">Nomor Ijazah</label>
                                           <input type="text" class="form-control" id="ijazah" name ="ijazah" value="{{ $profile->ijazah ?? '' }}" >
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-3">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Ijazah</label>
                                        <input type="text" class="form-control" id="tglijazah" name="tglijazah" 
                                        value="{{ isset($profile->tglijazah) ? \Carbon\Carbon::parse($profile->tglijazah)->format('d-m-Y') : '' }}" >
                                    </div>
                                </div><!-- Col -->
                                
                               </div><!-- Row -->
                               <button class="btn btn-primary" id="updateProfile">Ubah Profil</button>
                               </div><!-- Row -->
                               
                           </div>
                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                               
                           <!-- Kepangkatan -->
                           <div class="row">
           
                               <div class="col-md-12 grid-margin stretch-card">
                                   <div class="card">
                                       <div class="card-body">
                           <div class="mb-3"></div>
                           <div class="label-container">
                               <h6 class="label" onclick="toggleContent('kepangkatan')">
                                   <span class="toggle-symbol" id="kepangkatanToggle">+</span> 
                                   Data Orang Tua</h6>
                               <div class="content" id="kepangkatanContent">
                                   <div class="row">
                                       <div class="mb-3"></div>
                                  <div class="col-sm-4">
                                       <div class="mb-3">
                                           <label class="form-label">Nama Ayah</label>
                                           <input type="text" class="form-control" id="ayah" name ="ayah" 
                                           value="{{ $profile->ayah ?? '' }}"
                                          >
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-4">
                                       <div class="mb-3">
                                           <label class="form-label">Nama Ibu</label>
                                           <input type="text" class="form-control" id="ibu" name ="ibu" 
                                           value="{{ $profile->ibu ?? '' }}"
                                           >
                                       </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-4">
                                       <div class="mb-3">
                                           <label class="form-label">HP Orang Tua</label>
                                           <input type="text" class="form-control" id="hportu" name ="hportu" 
                                           value="{{ $profile->hportu ?? '' }}"
                                           >
                                       </div>
                                   </div><!-- Col -->
                               </div><!-- Row -->
                                      <div class="row">
                                   <div class="col-sm-4">
                                       <div class="mb-3">
                                           <label class="form-label">Alamat Orang Tua</label>
                                       <input type="text" class="form-control" id ="alamtortu" name="alamatortu"
                                       value="{{ $profile->alamatortu ?? '' }}" >
                                   </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-4">
                                       <div class="mb-3">
                                           <label class="form-label">Kecamatan</label>
                                       <input type="text" class="form-control" id="kecamatanortu" name="kecamatanortu" 
                                       value="{{ $profile->kecamatanortu ?? '' }}">
                                   </div>
                                   </div><!-- Col -->
                                   <div class="col-sm-4">
                                       <div class="mb-3">
                                           <label class="form-label">Kabupaten</label>
                                       <input type="text" class="form-control" id="kabupatenortu" name="kabupatenortu"
                                       value="{{ $profile->kabupatenortu ?? '' }}" >
                                   </div>
                                   </div><!-- Col -->
                               </div><!-- Row -->
                               <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">Pekerjaan </label>
                                        <select class="form-select" id="pekerjaan" name="pekerjaan" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Pekerjaan......</option>
                                            @foreach($allPekerjaan as $nama)
                                                <option value="{{ $nama->nama }}" 
                                                    {{ $nama->nama == $profile->pekerjaan ? 'selected' : '' }}>
                                                    {{ $nama->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">Penghasilan </label>
                                        <select class="form-select" id="penghasilan" name="penghasilan" aria-label="Default select example">
                                            <option value="" disabled selected>Choose Penghasilan......</option>
                                            @foreach($allPenghasilan as $nama)
                                                <option value="{{ $nama->nama }}" 
                                                    {{ $nama->nama == $profile->penghasilan ? 'selected' : '' }}>
                                                    {{ $nama->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div><!-- Row -->
                               <button class="btn btn-primary" id="updateProfile">Ubah Profil</button>
                           </div>
                                </div>                                                 
                    </div> 
                </div>
            </div>
        </div>
                                <!-- datalainlain -->
                                <div class="row">
                                    <div class="col-md-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                <div class="mb-3"></div>
                                <div class="label-container">
                                    <h6 class="label" onclick="toggleContent('datalainlain')">
                                        <span class="toggle-symbol" id="datalainlainToggle">+</span> 
                                        Data Lain-Lain</h6>
                                    <div class="content" id="datalainlainContent">
                                        <div class="row">
                                            <div class="mb-3"></div>
                                       <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Alamat Asal</label>
                                                <input type="text" class="form-control" id="alamatasal" name ="alamatasal" 
                                                value="{{ $profile->alamatasal ?? '' }}"
                                               >
                                            </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kecamatan Asal</label>
                                                <input type="text" class="form-control" id="kecamatanasal" name ="kecamatanasal" 
                                                value="{{ $profile->kecamatanasal ?? '' }}"
                                                >
                                            </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kabupaten Asal</label>
                                                <input type="text" class="form-control" name ="kabupatenasal" id="kabupatenasal"
                                                value="{{ $profile->kabupatenasal ?? '' }}"
                                                >
                                            </div>
                                        </div><!-- Col -->
                                    </div><!-- Row -->
                                        <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Alamat Domisili</label>
                                            <input type="text" class="form-control" name="alamatdomisili" id="alamatdomisili"
                                            value="{{ $profile->alamatasal ?? '' }}" >
                                        </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kecamatan Domisili</label>
                                            <input type="text" class="form-control" name="kecamatanasal" id="kecamatanasal"
                                            value="{{ $profile->kecamatanasal ?? '' }}">
                                        </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kabupaten Domisili</label>
                                            <input type="text" class="form-control" name="kabupatenasal" id="kabupatenasal"
                                            value="{{ $profile->kabupatenasal ?? '' }}" >
                                        </div>
                                        </div><!-- Col -->
                                    </div><!-- Row -->
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Alamat Kerja</label>
                                            <input type="text" class="form-control" name="alamatkerja" id="alamatkerja"
                                            value="{{ $profile->alamatkerja ?? '' }}" >
                                        </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kecamatan Kerja</label>
                                            <input type="text" class="form-control" name="kecamatankerja" id="kecamatankerja"
                                            value="{{ $profile->kecamatankerja ?? '' }}">
                                        </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Kabupaten Kerja</label>
                                            <input type="text" class="form-control" name="kabupatenkerja" id="kabupatenkerja"
                                            value="{{ $profile->kabupatenkerja ?? '' }}" >
                                        </div>
                                        </div><!-- Col -->
                                    </div><!-- Row -->
                                    <button class="btn btn-primary" id="updateProfile">Ubah Profil</button>
                                </div>
                                </div>                                                 
                            </div>
                           
                        </div>
                    </div>
                </div>
        @endif
    </div>
<style>

 .table {
    width: 100%; /* Menjadikan tabel 100% lebar dari card-body */
}

.card-body {
    overflow-x: auto; /* Mengaktifkan overflow horizontal untuk card-body jika konten melebihi lebar */
}
#resultList {
  max-height: 200px; /* Set a maximum height for the list */
  overflow-y: auto; /* Add a scrollbar when the list overflows */
  position: absolute;
  width: 100%; /* Make the list full-width */
  z-index: 1000; /* Adjust the z-index to make sure the list appears above other elements */
  background-color: #ffffff; /* Set a background color */
  border-radius: 5px; /* Optional: Add border-radius for rounded corners */
  top: calc(100% + 10px); /* Position the resultList below the input field */
  left: 0; /* Align the left edge of resultList with the left edge of its containing block */
  padding: 0; /* Remove padding to align with the input field */
  margin: 0; /* Remove margin to align with the input field */
}

#resultList li {
  padding: 8px; /* Add padding to each list item */
  cursor: pointer; /* Change the cursor to a pointer for better user experience */
  list-style: none; /* Remove default list styling */
  border-bottom: 1px solid #ccc; /* Add a border between list items */
}

#resultList li:last-child {
  border-bottom: none; /* Remove border from the last list item */
}
#resultList1 {
        list-style-type: none;
        padding: 0;
        margin: 0;
        border: 1px solid #ddd;
        background-color: #fff;
        position: absolute;
        max-height: 150px;
        overflow-y: auto;
        z-index: 1000;
        width: 100%; /* Pastikan lebar sama dengan input */
    }
    #resultList1 li {
        padding: 8px;
        cursor: pointer;
    }
    #resultList1 li:hover {
        background-color: #f0f0f0;
    }
 </style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<!-- Include Flatpickr styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<!-- Include Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script>
   
        function toggleContent(sectionId) {
            console.log('Toggle content for section:', sectionId);
            var content = document.getElementById(sectionId + 'Content');
            var toggleSymbol = document.getElementById(sectionId + 'Toggle');
    
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                toggleSymbol.textContent = '-';
            } else {
                content.style.display = 'none';
                toggleSymbol.textContent = '+';
            }
        }
        $(document).ready(function () {
            $('#myTabs a').on('click', function (e) {
                e.preventDefault()
                $(this).tab('show')
            })
        });
    @if(isset($profile))
    jQuery(document).ready(function ($) {
    // Initialize flatpickr untuk kolom tanggal lahir
    flatpickr('#tanggallahir', {
        dateFormat: 'd/m/Y',
        defaultDate: '{{ $profile->tanggallahir }}',
        minDate: '01/01/1900',
        maxDate: 'today',
        yearRange: '1900:' + new Date().getFullYear(),
    });

    // Initialize flatpickr untuk kolom tanggal masuk
    flatpickr('#tglmasuk', {
        dateFormat: 'd/m/Y',
        defaultDate: '{{ $profile->tglmasuk }}',
        minDate: '01/01/1900',
        maxDate: 'today',
        yearRange: '1900:' + new Date().getFullYear(),
    });
});

@endif
$(document).ready(function () {
    $('#dosenpembimbing').on('input', function () {
        var searchQuery = $(this).val();

        if (searchQuery.length >= 4) {
            $.ajax({
                url: '{{ route("searchDosen") }}',
                method: 'GET',
                data: { term: searchQuery },
                success: function (data) {
                    var resultList1 = $('#resultList1');
                    resultList1.empty();

                    console.log('Server Response:', data);

                    if (data.length > 0) {
                        resultList1.show();
                        data.forEach(function (result) {
                            resultList1.append('<li data-id="' + result.iddosen + '">' + result.iddosen + ' - ' + result.nama + '</li>');
                        });
                    } else {
                        resultList1.hide();
                    }
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        } else {
            $('#resultList1').hide();
        }
    });

    $(document).on('click', '#resultList1 li', function () {
        var fullName = $(this).text();
        var splitResult = fullName.split(' - ');

        var idDosen = splitResult[0] || '';
        var namaDosen = splitResult[1] || '';

        console.log('ID Dosen:', idDosen);
        console.log('Nama:', namaDosen);

        $('#dosenpembimbing').val(idDosen);
        $('input[name="namadosen"]').val(namaDosen);

        $('#resultList1').hide();
    });
});


 $('#npm').on('input', function () {
    var searchQuery = $(this).val();

    if (searchQuery.length >= 4) {
        // Lakukan permintaan AJAX ke server untuk mencari Mahasiswa
        $.ajax({
            url: '{{ route("findMahasiswa") }}',
            method: 'GET',
            data: { term: searchQuery },
            success: function (data) {
                var resultList = $('#resultList');
                resultList.empty();

                console.log('Server Response:', data);

                resultList.show();

                data.forEach(function (result) {
                    resultList.append('<li data-id="' + result.npm + '">' + result.npm + ' - ' + result.nama + '</li>');
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

    var npm = splitResult.length > 1 ? splitResult[0] : '';
    var nama = splitResult.length > 1 ? splitResult[1] : '';

    console.log('id dosen:', npm);
    console.log('Nama:', nama);

    $('#npm').val(npm);
    $('#nama').val(nama);

    if (!npm) {
        $('#nama').val('');
    }

    $('#resultList').hide();
});
$('#agama').change(function () {
    var agama = $(this).find(':selected').val();
    
});
$(document).ready(function() {
    $('#formSimpan').submit(function(event) {
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
                        window.location.href = "{{ route('showProfileMahasiswa') }}";
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
