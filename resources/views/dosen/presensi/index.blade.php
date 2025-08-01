@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelola Presensi Mata Kuliah</h5>
                    <a href="{{ route('dosen.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if($matakuliahDiampu->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Informasi:</strong> Pilih mata kuliah untuk melihat daftar presensi mahasiswa.
                        </div>

                        <div class="row">
                            @foreach($matakuliahDiampu as $pengampu)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">{{ $pengampu->matakuliah->kode_mk }}</h6>
                                            <p class="card-text">{{ $pengampu->matakuliah->nama_mk }}</p>
                                            <div class="small text-muted mb-3">
                                                <i class="fas fa-graduation-cap"></i> {{ $pengampu->matakuliah->sks }} SKS<br>
                                                <i class="fas fa-calendar"></i> Semester {{ $pengampu->matakuliah->semester }}
                                            </div>
                                            <a href="{{ route('dosen.presensi.show', $pengampu->matakuliah->kode_mk) }}" 
                                               class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-list"></i> Lihat Presensi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h5>Belum Ada Mata Kuliah</h5>
                            <p>Anda belum mengampu mata kuliah apapun.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection