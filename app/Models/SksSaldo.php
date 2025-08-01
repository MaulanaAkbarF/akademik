<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SksSaldo extends Model
{
    use HasFactory;

    protected $table = 'sks_saldo';

    protected $fillable = [
        'nim',
        'sks_tersedia',
        'sks_terpakai',
        'total_sks_dibeli',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Helper methods
    public function getSksRemainingAttribute()
    {
        return $this->sks_tersedia - $this->sks_terpakai;
    }

    public function canTakeSks($jumlah_sks)
    {
        return $this->getSksRemainingAttribute() >= $jumlah_sks;
    }
}