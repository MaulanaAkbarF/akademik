@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Mahasiswa Dashboard') }}</div>

                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }}!</h4>
                    <p>NIM: {{ Auth::user()->nim }}</p>
                    
                    @if(Auth::user()->mahasiswa)
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Profile Information</h5>
                                <p><strong>Nama:</strong> {{ Auth::user()->mahasiswa->nama }}</p>
                                <p><strong>Alamat:</strong> {{ Auth::user()->mahasiswa->alamat }}</p>
                                <p><strong>No HP:</strong> {{ Auth::user()->mahasiswa->nohp }}</p>
                                <p><strong>Semester:</strong> {{ Auth::user()->mahasiswa->semester }}</p>
                                <p><strong>Golongan:</strong> {{ Auth::user()->mahasiswa->golongan->nama_gol ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>KRS (Kartu Rencana Studi)</h5>
                                @if(Auth::user()->mahasiswa->krs->count() > 0)
                                    <ul class="list-group">
                                        @foreach(Auth::user()->mahasiswa->krs as $krs)
                                            <li class="list-group-item">
                                                {{ $krs->matakuliah->kode_mk }} - {{ $krs->matakuliah->nama_mk }} ({{ $krs->matakuliah->sks }} SKS)
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Belum mengambil mata kuliah.</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Jadwal Hari Ini -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Jadwal Kuliah Hari Ini ({{ $hariIni }})</h5>
                                @if($jadwalHariIni->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Jam</th>
                                                    <th>Mata Kuliah</th>
                                                    <th>Ruang</th>
                                                    <th>Status</th>
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
                                                        <td>
                                                            @if($jadwal->isCurrentTime())
                                                                <span class="badge bg-success">Sedang Berlangsung</span>
                                                            @elseif(now()->format('H:i:s') < $jadwal->jam_mulai)
                                                                <span class="badge bg-warning">Akan Datang</span>
                                                            @else
                                                                <span class="badge bg-secondary">Selesai</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('mahasiswa.presensi.index') }}" class="btn btn-primary">
                                            <i class="fas fa-clipboard-check"></i> Presensi Hari Ini
                                        </a>
                                        <a href="{{ route('mahasiswa.jadwal') }}" class="btn btn-info">
                                            <i class="fas fa-calendar"></i> Lihat Jadwal Lengkap
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Tidak ada jadwal kuliah hari ini ({{ $hariIni }}).
                                        <br>
                                        <a href="{{ route('mahasiswa.jadwal') }}" class="btn btn-sm btn-outline-info mt-2">
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