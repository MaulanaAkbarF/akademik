<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Krs;

class KrsSeeder extends Seeder
{
    public function run(): void
    {
        $krsData = [
            // Mahasiswa 1 mengambil mata kuliah Jumat
            ['nim' => 'M001', 'kode_mk' => 'IF501'],
            ['nim' => 'M001', 'kode_mk' => 'IF502'],
            // Tambah beberapa mata kuliah lain
            ['nim' => 'M001', 'kode_mk' => 'IF101'],
            ['nim' => 'M001', 'kode_mk' => 'IF201'],
            // Mahasiswa 2 mengambil mata kuliah Jumat
            ['nim' => 'M002', 'kode_mk' => 'IF501'],
            ['nim' => 'M002', 'kode_mk' => 'IF502'],
            ['nim' => 'M002', 'kode_mk' => 'IF102'],
            ['nim' => 'M002', 'kode_mk' => 'IF202'],
        ];

        foreach ($krsData as $krs) {
            Krs::create($krs);
        }
    }
}