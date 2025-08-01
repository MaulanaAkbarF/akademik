@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Beli SKS</h5>
                    <a href="{{ route('mahasiswa.sks.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informasi:</strong> Harga per SKS adalah Rp {{ number_format($hargaPerSks, 0, ',', '.') }}
                    </div>

                    <form action="{{ route('mahasiswa.sks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="jumlah_sks" class="form-label">Jumlah SKS <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah_sks') is-invalid @enderror" 
                                   id="jumlah_sks" name="jumlah_sks" value="{{ old('jumlah_sks') }}" 
                                   min="1" max="24" required>
                            @error('jumlah_sks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maksimal 24 SKS per transaksi</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" id="total_harga" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_pembayaran') is-invalid @enderror" 
                                    id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer_bank" {{ old('metode_pembayaran') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="va_bca" {{ old('metode_pembayaran') == 'va_bca' ? 'selected' : '' }}>Virtual Account BCA</option>
                                <option value="va_bni" {{ old('metode_pembayaran') == 'va_bni' ? 'selected' : '' }}>Virtual Account BNI</option>
                                <option value="va_mandiri" {{ old('metode_pembayaran') == 'va_mandiri' ? 'selected' : '' }}>Virtual Account Mandiri</option>
                                <option value="e_wallet" {{ old('metode_pembayaran') == 'e_wallet' ? 'selected' : '' }}>E-Wallet (OVO/GoPay/DANA)</option>
                                <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash (Bayar di Kampus)</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (Opsional)</label>
                            <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                                   id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                            @error('bukti_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload bukti pembayaran jika sudah melakukan pembayaran. Format: JPG, PNG. Maksimal 2MB.</div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Buat Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('jumlah_sks').addEventListener('input', function() {
    const jumlahSks = parseInt(this.value) || 0;
    const hargaPerSks = {{ $hargaPerSks }};
    const totalHarga = jumlahSks * hargaPerSks;
    
    document.getElementById('total_harga').value = totalHarga.toLocaleString('id-ID');
});

// Calculate initial total
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('jumlah_sks').dispatchEvent(new Event('input'));
});
</script>
@endsection