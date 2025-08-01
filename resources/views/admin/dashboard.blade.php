@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }}!</h4>
                    <p class="text-muted">Kelola sistem akademik universitas</p>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h3>{{ $totalMahasiswa }}</h3>
                                    <small>Total Mahasiswa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                    <h3>{{ $totalDosen }}</h3>
                                    <small>Total Dosen</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <h3>{{ $totalMatakuliah }}</h3>
                                    <small>Total Mata Kuliah</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                                    <small>Total Revenue SKS</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SKS Transaction Summary -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Ringkasan Transaksi SKS</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                                    <h4 class="text-warning">{{ $pendingTransactions }}</h4>
                                                    <small>Menunggu Persetujuan</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-success">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                                    <h4 class="text-success">{{ $approvedTransactions }}</h4>
                                                    <small>Transaksi Disetujui</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <a href="{{ route('admin.sks.index') }}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-cog"></i> Kelola Transaksi SKS
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    @if($recentTransactions->count() > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Transaksi Terbaru</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Mahasiswa</th>
                                                        <th>SKS</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentTransactions as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction->tanggal_transaksi->format('d-m-Y') }}</td>
                                                            <td>{{ $transaction->mahasiswa->nama }}</td>
                                                            <td>{{ $transaction->jumlah_sks }} SKS</td>
                                                            <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                                            <td>{!! $transaction->getStatusBadgeAttribute() !!}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.sks.index') }}" class="btn btn-sm btn-outline-primary">
                                                Lihat Semua Transaksi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Aksi Cepat</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mb-2">
                                                <i class="fas fa-user-plus"></i> Tambah User Baru
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('admin.sks.index') }}" class="btn btn-outline-success w-100 mb-2">
                                                <i class="fas fa-money-bill-wave"></i> Kelola Transaksi SKS
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-outline-info w-100 mb-2" disabled>
                                                <i class="fas fa-chart-bar"></i> Laporan (Coming Soon)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection