@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Presensi Mahasiswa') }}</div>
                <div class="card-body">
                    <h4>Presensi Hari Ini ({{ $hariIni }})</h4>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($jadwalHariIni->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Jam</th>
                                        <th>Mata Kuliah</th>
                                        <th>Ruang</th>
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
                                                @if($jadwal->isCurrentTime() && !in_array($jadwal->matakuliah->kode_mk, $presensiHariIni))
                                                    <form action="{{ route('mahasiswa.presensi.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="kode_mk" value="{{ $jadwal->matakuliah->kode_mk }}">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-check"></i> Presensi
                                                        </button>
                                                    </form>
                                                @elseif(in_array($jadwal->matakuliah->kode_mk, $presensiHariIni))
                                                    <span class="badge bg-info">Sudah Presensi</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Tidak ada jadwal kuliah hari ini ({{ $hariIni }}).
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection