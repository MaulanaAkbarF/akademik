@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dosen Dashboard') }}</div>

                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }}!</h4>
                    <p>NIP: {{ Auth::user()->nip }}</p>
                    
                    @if(Auth::user()->dosen)
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Profile Information</h5>
                                <p><strong>Nama:</strong> {{ Auth::user()->dosen->nama }}</p>
                                <p><strong>Alamat:</strong> {{ Auth::user()->dosen->alamat }}</p>
                                <p><strong>No HP:</strong> {{ Auth::user()->dosen->nohp }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Mata Kuliah Diampu</h5>
                                @if(Auth::user()->dosen->pengampu->count() > 0)
                                    <ul class="list-group">
                                        @foreach(Auth::user()->dosen->pengampu as $pengampu)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $pengampu->matakuliah->kode_mk }}</strong><br>
                                                    {{ $pengampu->matakuliah->nama_mk }}
                                                </div>
                                                <a href="{{ route('dosen.presensi.show', $pengampu->matakuliah->kode_mk) }}" class="btn btn-sm btn-outline-primary">
                                                    Lihat Presensi
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Belum ada mata kuliah yang diampu.</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Jadwal Mengajar Hari Ini -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Jadwal Mengajar Hari Ini ({{ $hariIni }})</h5>
                                @if($jadwalHariIni->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Jam</th>
                                                    <th>Mata Kuliah</th>
                                                    <th>Ruang</th>
                                                    <th>Golongan</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($jadwalHariIni as $jadwal)
                                                    <tr>
                                                        <td>{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</td>
                                                        <td>
                                                            <strong>{{ $jadwal->matakuliah->kode_mk }}</strong><br>
                                                            {{ $jadwal->matakuliah->nama_mk }}
                                                        </td>
                                                        <td>{{ $jadwal->ruang->nama_ruang }}</td>
                                                        <td>{{ $jadwal->golongan->nama_gol }}</td>
                                                        <td>
                                                            @if($jadwal->isCurrentTime())
                                                                <span class="badge bg-success">Sedang Berlangsung</span>
                                                            @elseif(now()->format('H:i:s') < $jadwal->jam_mulai)
                                                                <span class="badge bg-warning">Akan Datang</span>
                                                            @else
                                                                <span class="badge bg-secondary">Selesai</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('dosen.presensi.show', $jadwal->matakuliah->kode_mk) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-list"></i> Presensi
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('dosen.presensi.index') }}" class="btn btn-primary">
                                            <i class="fas fa-clipboard-list"></i> Kelola Presensi
                                        </a>
                                        <a href="{{ route('dosen.jadwal') }}" class="btn btn-info">
                                            <i class="fas fa-calendar"></i> Lihat Jadwal Lengkap
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Tidak ada jadwal mengajar hari ini ({{ $hariIni }}).
                                        <br>
                                        <a href="{{ route('dosen.jadwal') }}" class="btn btn-sm btn-outline-info mt-2">
                                            Lihat Jadwal Minggu Ini
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection