<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruang;

class RuangSeeder extends Seeder
{
    public function run(): void
    {
        $ruang = [
            ['nama_ruang' => 'Ruang A101'],
            ['nama_ruang' => 'Ruang A102'],
            ['nama_ruang' => 'Ruang A103'],
            ['nama_ruang' => 'Ruang A104'],
            ['nama_ruang' => 'Ruang A105'],
            ['nama_ruang' => 'Lab Komputer 1'],
            ['nama_ruang' => 'Lab Komputer 2'],
            ['nama_ruang' => 'Ruang Multimedia'],
        ];

        foreach ($ruang as $r) {
            Ruang::create($r);
        }
    }
}
