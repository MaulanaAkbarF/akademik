<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'alamat',
        'nohp',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'nip', 'nip');
    }

    public function pengampu()
    {
        return $this->hasMany(Pengampu::class, 'nip', 'nip');
    }
}
