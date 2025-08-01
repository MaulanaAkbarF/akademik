<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SksTransaction extends Model
{
    use HasFactory;

    protected $table = 'sks_transactions';

    protected $fillable = [
        'nim',
        'jumlah_sks',
        'harga_per_sks',
        'total_harga',
        'metode_pembayaran',
        'status',
        'tanggal_transaksi',
        'bukti_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_harga' => 'decimal:2',
        'harga_per_sks' => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning">Menunggu</span>';
            case 'approved':
                return '<span class="badge bg-success">Disetujui</span>';
            case 'rejected':
                return '<span class="badge bg-danger">Ditolak</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
}