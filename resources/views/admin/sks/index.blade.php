{{-- resources/views/admin/sks/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelola Transaksi SKS</h5>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary">
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

                    @if($transactions->count() > 0)
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $transactions->where('status', 'pending')->count() }}</h4>
                                        <small>Menunggu Persetujuan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $transactions->where('status', 'approved')->count() }}</h4>
                                        <small>Disetujui</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $transactions->where('status', 'rejected')->count() }}</h4>
                                        <small>Ditolak</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4>Rp {{ number_format($transactions->where('status', 'approved')->sum('total_harga'), 0, ',', '.') }}</h4>
                                        <small>Total Revenue</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>SKS</th>
                                        <th>Total Harga</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->tanggal_transaksi->format('d-m-Y H:i') }}</td>
                                            <td>{{ $transaction->mahasiswa->nama }}</td>
                                            <td>{{ $transaction->nim }}</td>
                                            <td>{{ $transaction->jumlah_sks }} SKS</td>
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
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->isPending())
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                                onclick="updateStatus({{ $transaction->id }}, 'approved')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="updateStatus({{ $transaction->id }}, 'rejected')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
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
                            <p>Belum ada transaksi pembelian SKS dari mahasiswa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Status Update -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="statusSelect" required>
                            <option value="approved">Setujui</option>
                            <option value="rejected">Tolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(transactionId, status) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const statusSelect = document.getElementById('statusSelect');
    
    form.action = `/admin/sks-transactions/${transactionId}/status`;
    statusSelect.value = status;
    
    modal.show();
}
</script>
@endsection