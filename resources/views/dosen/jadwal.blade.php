@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Jadwal Mengajar Mingguan</h5>
                    <a href="{{ route('dosen.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if($jadwalMingguan->count() > 0)
                        <div class="row">
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 @if($hari === now()->format('l')) border-primary @endif">
                                        <div class="card-header @if($hari === now()->format('l')) bg-primary text-white @else bg-light @endif">
                                            <h6 class="mb-0">
                                                {{ $hari }}
                                                @if($hari === now()->format('l'))
                                                    <span class="badge bg-warning text-dark ms-2">Hari Ini</span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($jadwalMingguan[$hari]) && $jadwalMingguan[$hari]->count() > 0)
                                                @foreach($jadwalMingguan[$hari] as $jadwal)
                                                    <div class="border rounded p-2 mb-2 @if($jadwal->isToday()) bg-light-primary @endif">
                                                        <div class="fw-bold text-primary">{{ $jadwal->matakuliah->kode_mk }}</div>
                                                        <div class="small">{{ $jadwal->matakuliah->nama_mk }}</div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-clock"></i> {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-map-marker-alt"></i> {{ $jadwal->ruang->nama_ruang }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-users"></i> {{ $jadwal->golongan->nama_gol }}
                                                        </div>
                                                        <div class="small">
                                                            <span class="badge bg-info">{{ $jadwal->matakuliah->sks }} SKS</span>
                                                        </div>
                                                        <div class="mt-1">
                                                            <a href="{{ route('dosen.presensi.show', $jadwal->matakuliah->kode_mk) }}" 
                                                               class="btn btn-xs btn-outline-primary">
                                                                <i class="fas fa-list"></i> Presensi
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-calendar-times"></i><br>
                                                    <small>Tidak ada jadwal</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                            <h5>Belum Ada Jadwal</h5>
                            <p>Anda belum memiliki jadwal mengajar. Silakan hubungi admin untuk pengaturan jadwal.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection