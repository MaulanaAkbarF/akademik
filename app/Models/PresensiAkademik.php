<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiAkademik extends Model
{
    use HasFactory;

    protected $table = 'presensi_akademik';

    protected $fillable = [
        'hari',
        'tanggal',
        'jam_presensi',
        'status_kehadiran',
        'nim',
        'kode_mk',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_presensi' => 'datetime:H:i:s',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_mk', 'kode_mk');
    }
}