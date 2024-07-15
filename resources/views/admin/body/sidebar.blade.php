@php
    $userDivision = session('divisi');
@endphp

<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            Q<span>Enterprise</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <!-- Keuangan Section -->
            @if(in_array($userDivision, ['Biro Keuangan', 'Administrator']))
            <li class="nav-item nav-category">Keuangan</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#keuangan" role="button" aria-expanded="false" aria-controls="keuangan">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Dosen</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="keuangan">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showFormData') }}">Validasi Honor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showRekapHonorDosen') }}">Rekap Honor Dosen / Bulan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showSettingHonor') }}">Set Honor SKS DOSEN S2</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Kurikulum Section -->
            @if(in_array($userDivision, ['Biro Akademik', 'Ka Biro Akademik', 'Administrator','Yayasan']))
            <li class="nav-item nav-category">Kurikulum</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#kurikulum" role="button" aria-expanded="false" aria-controls="kurikulum">
                    <i class="link-icon" data-feather="book"></i>
                    <span class="link-title">Kurikulum Prodi</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="kurikulum">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showKurikulum') }}">Kurikulum</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Dosen Profil Section -->
            @if(in_array($userDivision, ['Biro Akademik', 'Administrator','Yayasan','Sekretariat','Ka Biro Akademik']))
            <li class="nav-item nav-category">Dosen</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#profilDosen" role="button" aria-expanded="false" aria-controls="profilDosen">
                    <i class="link-icon" data-feather="book-open"></i>
                    <span class="link-title">Profil</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="profilDosen">
                    <ul class="nav sub-menu">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profilInput') }}">Edit Profil Dosen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showPPD') }}">Rekap Dosen Aktif</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- CMS Section -->
            @if($userDivision == 'Administrator')
            <li class="nav-item nav-category">CMS</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#cms" role="button" aria-expanded="false" aria-controls="cms">
                    <i class="link-icon" data-feather="file-text"></i>
                    <span class="link-title">CMS</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="cms">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showJabatanFungsi') }}">Jabatan Fungsi</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Tambah Jabatan Section -->
            @if(in_array($userDivision, ['Administrator','Sekretariat']))
            <li class="nav-item nav-category">Tambah Jabatan</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#jabatan" role="button" aria-expanded="false" aria-controls="jabatan">
                    <i class="link-icon" data-feather="user-plus"></i>
                    <span class="link-title">Jabatan Rektorat</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="jabatan">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('formKaprodi') }}">Jabatan Rektorat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showViewJabatan') }}">View Jabatan Rektorat</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- Akreditasi Section -->
            @if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik', 'Kaprodi','Yayasan']))
            <li class="nav-item nav-category">Akreditasi</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#upps" role="button" aria-expanded="false" aria-controls="upps">
                    <i class="link-icon" data-feather="file-text"></i>
                    <span class="link-title">UPPS</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="upps">
                    <ul class="nav sub-menu">
                        @if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showUPPS') }}">UPPS Universitas</a>
                        </li>
                        @endif
                        @if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Kaprodi', 'Ka Biro Akademik']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showUPPSFakultas') }}">UPPS Fakultas</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

         <!-- Rekap Mahasiswa Section -->
@if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik','Fungsionaris UQ','Yayasan']))
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="collapse" href="#rekap" role="button" aria-expanded="false" aria-controls="rekap">
        <i class="link-icon" data-feather="clipboard"></i>
        <span class="link-title">Rekap Mahasiswa</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="collapse" id="rekap">
        <ul class="nav sub-menu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showRekap') }}">Rekap Mahasiswa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showRekapProdi') }}">Rekap Mahasiswa /Prodi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('form.karyawan') }}">Rekap Mahasiswa Karyawan</a>
            </li>
           
        </ul>
    </div>
</li>
@endif
@if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik','Fungsionaris UQ','Yayasan']))
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="collapse" href="#jumlah" role="button" aria-expanded="false" aria-controls="jumlah">
        <i class="link-icon" data-feather="book"></i>
        <span class="link-title">Jumlah Mahasiswa</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="collapse" id="jumlah">
        <ul class="nav sub-menu">
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showjlhMahasiswa') }}">Jumlah Mahasiswa /Lokasi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showjlhMahasiswaProdi') }}">Jumlah Mahasiswa /Prodi</a>
            </li>
           
        </ul>
    </div>
</li>
@endif
@if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik','Fungsionaris UQ','Yayasan']))
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="collapse" href="#lulusan" role="button" aria-expanded="false" aria-controls="lulusan">
        <i class="link-icon" data-feather="archive"></i>
        <span class="link-title">Lulusan</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
    </a>
    <div class="collapse" id="lulusan">
        <ul class="nav sub-menu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showIPKLulusan') }}">IPK Lulusan </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showIPKPPRODI') }}">IPK Lulusan /Prodi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showIPKProdi') }}">Rincian Lulusan /Prodi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showIPKPPRODIRegular') }}">Rincian Regular /Prodi</a>
            </li>
        </ul>
    </div>
</li>
@endif

            <!-- Mahasiswa Section -->
            @if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik','Fungsionaris UQ','Yayasan']))
            <li class="nav-item nav-category">Mahasiswa</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#konversiNilai" role="button" aria-expanded="false" aria-controls="konversiNilai">
                    <i class="link-icon" data-feather="folder-plus"></i>
                    <span class="link-title">Konversi Nilai</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="konversiNilai">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('showTambahKonversi')}}">Input Konversi Nilai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('showLDIKTI')}}">Cetak LLDikti</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <!-- Report Section -->
            @if(in_array($userDivision, ['Biro Akademik', 'Administrator','Ka Biro Akademik','Fungsionaris UQ','Yayasan']))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#report" role="button" aria-expanded="false" aria-controls="report">
                    <i class="link-icon" data-feather="printer"></i>
                    <span class="link-title">Report</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="report">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showSummary') }}">Summary KRS Mahasiswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showRincian') }}">Rincian KRS Mahasiswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showCetakKRS') }}">Cetak KRS Mahasiswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showNilai') }}">Analisa Nilai Mahasiswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showKonversi') }}">Transkrip Nilai Mahasiswa</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Ka Biro Akademik', 'Kaprodi','Marketing']))
            <li class="nav-item nav-category">PMB</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#pmb" role="button" aria-expanded="false" aria-controls="pmb">
                    <i class="link-icon" data-feather="bar-chart"></i>
                    <span class="link-title">PMB</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="pmb">
                    <ul class="nav sub-menu">
                       
                        @if(in_array($userDivision, ['Administrator', 'Biro Akademik', 'Kaprodi', 'Ka Biro Akademik','Marketing']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showMonitoringPMB') }}">PMB Monitoring</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showGrafikPMB') }}">Grafik PMB Monitoring</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showDataCalonMahasiswa') }}">Calon Mahasiswa S1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('showDataCalonMahasiswas2') }}">Calon Mahasiswa S2</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
        </ul>
    </div>

</nav>
