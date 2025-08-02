{{-- resources/views/admin/sks/index.blade.php - ENHANCED DEBUG VERSION --}}
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Debug Info -->
                    <div id="debug-info" class="alert alert-info" style="display: none;">
                        <strong>Debug Info:</strong>
                        <div id="debug-content"></div>
                    </div>

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
                                                                onclick="updateStatus({{ $transaction->id }}, 'approved')"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-status="approved">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="updateStatus({{ $transaction->id }}, 'rejected')"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-status="rejected">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        
                                                        <!-- FALLBACK: Direct form submission -->
                                                        <div class="d-none">
                                                            <form method="POST" action="{{ route('admin.sks.update-status', $transaction->id) }}" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="approved">
                                                                <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                        onclick="return confirm('Setujui transaksi ini?')">
                                                                    Setujui
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.sks.update-status', $transaction->id) }}" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="rejected">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                        onclick="return confirm('Tolak transaksi ini?')">
                                                                    Tolak
                                                                </button>
                                                            </form>
                                                        </div>
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
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Konfirmasi Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
// Debug function to check what's available
function checkDependencies() {
    const debug = document.getElementById('debug-content');
    let debugInfo = [];
    
    debugInfo.push('jQuery available: ' + (typeof $ !== 'undefined' ? 'Yes' : 'No'));
    debugInfo.push('Bootstrap available: ' + (typeof bootstrap !== 'undefined' ? 'Yes' : 'No'));
    debugInfo.push('Modal element exists: ' + (document.getElementById('statusModal') ? 'Yes' : 'No'));
    debugInfo.push('Form element exists: ' + (document.getElementById('statusForm') ? 'Yes' : 'No'));
    
    if (debug) {
        debug.innerHTML = debugInfo.join('<br>');
        document.getElementById('debug-info').style.display = 'block';
    }
    
    return debugInfo;
}

// Enhanced updateStatus function with multiple fallbacks
function updateStatus(transactionId, status) {
    console.log('=== UPDATE STATUS CALLED ===');
    console.log('Transaction ID:', transactionId);
    console.log('Status:', status);
    
    // Check dependencies first
    const debugInfo = checkDependencies();
    console.log('Debug info:', debugInfo);
    
    try {
        // Method 1: Try Bootstrap 5 Modal
        if (typeof bootstrap !== 'undefined') {
            console.log('Trying Bootstrap 5 Modal...');
            const modalElement = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const statusSelect = document.getElementById('statusSelect');
            
            if (modalElement && form && statusSelect) {
                const modal = new bootstrap.Modal(modalElement);
                form.action = `/admin/sks-transactions/${transactionId}/status`;
                statusSelect.value = status;
                
                console.log('Form action set to:', form.action);
                modal.show();
                return;
            } else {
                throw new Error('Modal elements not found');
            }
        }
        
        // Method 2: Try jQuery + Bootstrap 4
        else if (typeof $ !== 'undefined' && $.fn.modal) {
            console.log('Trying jQuery + Bootstrap 4 Modal...');
            const form = $('#statusForm');
            const statusSelect = $('#statusSelect');
            
            form.attr('action', `/admin/sks-transactions/${transactionId}/status`);
            statusSelect.val(status);
            
            $('#statusModal').modal('show');
            return;
        }
        
        // Method 3: Fallback to confirm dialog
        else {
            console.log('Using fallback confirm dialog...');
            const confirmMessage = status === 'approved' ? 
                'Apakah Anda yakin ingin menyetujui transaksi ini?' : 
                'Apakah Anda yakin ingin menolak transaksi ini?';
            
            if (confirm(confirmMessage)) {
                // Create and submit form programmatically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/sks-transactions/${transactionId}/status`;
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.getAttribute('content');
                    form.appendChild(csrfInput);
                }
                
                // Add method field
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                form.appendChild(methodInput);
                
                // Add status field
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                form.appendChild(statusInput);
                
                document.body.appendChild(form);
                form.submit();
                return;
            }
        }
        
    } catch (error) {
        console.error('Error in updateStatus:', error);
        
        // Last resort: redirect to fallback URL
        const confirmMessage = 'Terjadi kesalahan dengan modal. Lanjutkan dengan cara sederhana?\n\n' +
            (status === 'approved' ? 'Setujui transaksi ini?' : 'Tolak transaksi ini?');
        
        if (confirm(confirmMessage)) {
            window.location.href = `/admin/sks-transactions/${transactionId}/status?status=${status}&_method=PATCH&_token=${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')}`;
        }
    }
}

// Alternative click handlers using event delegation
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    checkDependencies();
    
    // Event delegation for dynamically added buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-transaction-id]') || e.target.closest('[data-transaction-id]')) {
            const button = e.target.matches('[data-transaction-id]') ? e.target : e.target.closest('[data-transaction-id]');
            const transactionId = button.getAttribute('data-transaction-id');
            const status = button.getAttribute('data-status');
            
            if (transactionId && status) {
                console.log('Event delegation triggered:', transactionId, status);
                updateStatus(transactionId, status);
            }
        }
    });
    
    // Form submission handler
    const statusForm = document.getElementById('statusForm');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            console.log('Form submitted to:', this.action);
            
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';
                submitBtn.disabled = true;
                
                // Re-enable after timeout as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    }
});
</script>

{{-- Add this to check if CSRF token exists --}}
@if(!session()->has('_token'))
    <script>
        console.warn('CSRF token not found in session');
    </script>
@endif
@endsection