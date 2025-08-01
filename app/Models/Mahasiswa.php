<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'alamat',
        'nohp',
        'semester',
        'id_golongan',
        'id_user_mahasiswa',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'nim', 'nim');
    }

    public function userMahasiswa()
    {
        return $this->belongsTo(User::class, 'id_user_mahasiswa');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'nim', 'nim');
    }

    public function presensiAkademik()
    {
        return $this->hasMany(PresensiAkademik::class, 'nim', 'nim');
    }

    // New relationships for SKS transactions
    public function sksTransactions()
    {
        return $this->hasMany(SksTransaction::class, 'nim', 'nim');
    }

    public function sksSaldo()
    {
        return $this->hasOne(SksSaldo::class, 'nim', 'nim');
    }

    // Helper methods
    public function getTotalSksAmbil()
    {
        return $this->krs->sum(function($krs) {
            return $krs->matakuliah->sks;
        });
    }

    public function getSksAvailable()
    {
        return $this->sksSaldo ? $this->sksSaldo->getSksRemainingAttribute() : 0;
    }

    public function canTakeMatakuliah($matakuliah)
    {
        return $this->getSksAvailable() >= $matakuliah->sks;
    }
}