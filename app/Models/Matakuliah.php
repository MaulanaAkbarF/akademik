<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'stok',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function pengampu()
    {
        return $this->hasMany(Pengampu::class, 'kode_mk', 'kode_mk');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'kode_mk', 'kode_mk');
    }

    public function presensiAkademik()
    {
        return $this->hasMany(PresensiAkademik::class, 'kode_mk', 'kode_mk');
    }

    public function jadwalAkademik()
    {
        return $this->hasMany(JadwalAkademik::class, 'kode_mk', 'kode_mk');
    }
}