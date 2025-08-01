<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAkademik extends Model
{
    use HasFactory;

    protected $table = 'jadwal_akademik';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'hari',
        'kode_mk',
        'id_ruang',
        'id_gol',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_gol');
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_mk', 'kode_mk');
    }

    // Helper methods
    public function isToday()
    {
        $today = now()->format('l'); // Get current day name in English
        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $this->hari === $hariIndonesia[$today];
    }

    public function isCurrentTime()
    {
        if (!$this->isToday()) {
            return false;
        }
        
        $now = now()->format('H:i:s');
        return $now >= $this->jam_mulai && $now <= $this->jam_selesai;
    }
}