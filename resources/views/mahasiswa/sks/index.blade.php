@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- SKS Saldo Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-wallet"></i> Saldo SKS</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $sksSaldo->sks_tersedia }}</h3>
                                    <small>SKS Tersedia</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $sksSaldo->sks_terpakai }}</h3>
                                    <small>SKS Terpakai</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $sksSaldo->getSksRemainingAttribute() }}</h3>
                                    <small>SKS Sisa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $sksSaldo->total_sks_dibeli }}</h3>
                                    <small>Total SKS Dibeli</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('mahasiswa.sks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Beli SKS
                        </a>
                        <a href="{{ route('mahasiswa.krs.index') }}" class="btn btn-success">
                            <i class="fas fa-book"></i> Ambil Mata Kuliah
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Transaksi SKS</h5>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
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

                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah SKS</th>
                                        <th>Harga per SKS</th>
                                        <th>Total Harga</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->tanggal_transaksi->format('d-m-Y H:i') }}</td>
                                            <td>{{ $transaction->jumlah_sks }} SKS</td>
                                            <td>Rp {{ number_format($transaction->harga_per_sks, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                            <td>
                                                @switch($transaction->metode_pembayaran)
                                                    @case('transfer_bank')
                                                        <span class="badge bg-primary">Transfer Bank</span>
                                                        @break
                                                    @case('e_wallet')
                                                        <span class="badge bg-info">E-Wallet</span>
                                                        @break
                                                    @case('cash')
                                                        <span class="badge bg-success">Cash</span>
                                                        @break
                                                    @case('va_bni')
                                                        <span class="badge bg-warning">VA BNI</span>
                                                        @break
                                                    @case('va_bca')
                                                        <span class="badge bg-primary">VA BCA</span>
                                                        @break
                                                    @case('va_mandiri')
                                                        <span class="badge bg-danger">VA Mandiri</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{!! $transaction->getStatusBadgeAttribute() !!}</td>
                                            <td>
                                                @if($transaction->bukti_pembayaran)
                                                    <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Lihat Bukti
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $transactions->links() }}
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>Belum Ada Transaksi</h5>
                            <p>Anda belum pernah melakukan pembelian SKS.</p>
                            <a href="{{ route('mahasiswa.sks.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Beli SKS Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection