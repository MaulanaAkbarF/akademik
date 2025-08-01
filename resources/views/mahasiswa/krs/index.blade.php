@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- SKS Info -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Informasi SKS</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>SKS Tersedia:</strong> {{ $sksSaldo->sks_tersedia }} SKS
                        </div>
                        <div class="col-md-4">
                            <strong>SKS Terpakai:</strong> {{ $sksSaldo->sks_terpakai }} SKS
                        </div>
                        <div class="col-md-4">
                            <strong>SKS Sisa:</strong> {{ $sksSaldo->getSksRemainingAttribute() }} SKS
                        </div>
                    </div>
                    @if($sksSaldo->getSksRemainingAttribute() <= 0)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            SKS Anda tidak mencukupi. <a href="{{ route('mahasiswa.sks.create') }}">Beli SKS</a> terlebih dahulu.
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Current KRS -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">KRS Saat Ini</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($currentKrs->count() > 0)
                                @foreach($currentKrs as $krs)
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $krs->matakuliah->kode_mk }}</strong><br>
                                                    <small>{{ $krs->matakuliah->nama_mk }}</small><br>
                                                    <span class="badge bg-info">{{ $krs->matakuliah->sks }} SKS</span>
                                                </div>
                                                <form action="{{ route('mahasiswa.krs.drop') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="kode_mk" value="{{ $krs->matakuliah->kode_mk }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Yakin ingin membatalkan mata kuliah ini?')">
                                                        <i class="fas fa-times"></i> Drop
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="mt-2">
                                    <strong>Total SKS Diambil: {{ $currentKrs->sum(function($krs) { return $krs->matakuliah->sks; }) }} SKS</strong>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum mengambil mata kuliah apapun.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Available Mata Kuliah -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Mata Kuliah Tersedia</h5>
                            <a href="{{ route('mahasiswa.sks.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            @if($matakuliahTersedia->count() > 0)
                                @foreach($matakuliahTersedia as $mk)
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $mk->kode_mk }}</strong><br>
                                                    <small>{{ $mk->nama_mk }}</small><br>
                                                    <span class="badge bg-info">{{ $mk->sks }} SKS</span>
                                                    <span class="badge bg-secondary">Semester {{ $mk->semester }}</span>
                                                </div>
                                                <form action="{{ route('mahasiswa.krs.store') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="kode_mk" value="{{ $mk->kode_mk }}">
                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                            @if($sksSaldo->getSksRemainingAttribute() < $mk->sks) disabled @endif
                                                            onclick="return confirm('Yakin ingin mengambil mata kuliah {{ $mk->nama_mk }}?')">
                                                        <i class="fas fa-plus"></i> Ambil
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada mata kuliah yang tersedia untuk diambil.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection