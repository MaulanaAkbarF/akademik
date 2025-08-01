<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';

    protected $fillable = [
        'nama_ruang',
    ];

    public function jadwalAkademik()
    {
        return $this->hasMany(JadwalAkademik::class, 'id_ruang');
    }
}
