@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Presensi: {{ $matakuliah->nama_mk }}</h5>
                        <small class="text-muted">{{ $matakuliah->kode_mk }} - {{ $matakuliah->sks }} SKS</small>
                    </div>
                    <a href="{{ route('dosen.presensi.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <!-- Informasi Jadwal -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6>Jadwal Mata Kuliah:</h6>
                            @if($jadwal->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Hari</th>
                                                <th>Jam</th>
                                                <th>Ruang</th>
                                                <th>Golongan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jadwal as $j)
                                                <tr>
                                                    <td>{{ $j->hari }}</td>
                                                    <td>{{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}</td>
                                                    <td>{{ $j->ruang->nama_ruang }}</td>
                                                    <td>{{ $j->golongan->nama_gol }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">Belum ada jadwal untuk mata kuliah ini.</div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Data Presensi 7 Hari Terakhir -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Data Presensi (7 Hari Terakhir):</h6>
                            
                            @if($presensi->count() > 0)
                                <!-- Summary Statistics -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $presensi->count() }}</h4>
                                                <small>Total Presensi</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $presensi->where('status_kehadiran', 'hadir')->count() }}</h4>
                                                <small>Hadir</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $presensi->where('status_kehadiran', 'izin')->count() + $presensi->where('status_kehadiran', 'sakit')->count() }}</h4>
                                                <small>Izin/Sakit</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $presensi->where('status_kehadiran', 'tidak_hadir')->count() }}</h4>
                                                <small>Tidak Hadir</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Presensi -->
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Hari</th>
                                                <th>Jam Presensi</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($presensi as $p)
                                                <tr>
                                                    <td>{{ $p->tanggal->format('d-m-Y') }}</td>
                                                    <td>{{ $p->hari }}</td>
                                                    <td>
                                                        @if($p->jam_presensi)
                                                            {{ date('H:i:s', strtotime($p->jam_presensi)) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $p->mahasiswa->nim }}</td>
                                                    <td>{{ $p->mahasiswa->nama }}</td>
                                                    <td>
                                                        @switch($p->status_kehadiran)
                                                            @case('hadir')
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check"></i> Hadir
                                                                </span>
                                                                @break
                                                            @case('izin')
                                                                <span class="badge bg-warning">
                                                                    <i class="fas fa-clock"></i> Izin
                                                                </span>
                                                                @break
                                                            @case('sakit')
                                                                <span class="badge bg-info">
                                                                    <i class="fas fa-user-injured"></i> Sakit
                                                                </span>
                                                                @break
                                                            @case('tidak_hadir')
                                                                <span class="badge bg-danger">
                                                                    <i class="fas fa-times"></i> Tidak Hadir
                                                                </span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Filter dan Export Options -->
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Data presensi ditampilkan untuk 7 hari terakhir. 
                                        Total {{ $presensi->count() }} record presensi.
                                    </small>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                    <h5>Belum Ada Data Presensi</h5>
                                    <p>Belum ada mahasiswa yang melakukan presensi untuk mata kuliah ini dalam 7 hari terakhir.</p>
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