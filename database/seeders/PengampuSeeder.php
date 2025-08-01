<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengampu;

class PengampuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengampuData = [
            ['kode_mk' => 'IF101', 'nip' => 'D001'],
            ['kode_mk' => 'IF102', 'nip' => 'D002'],
            ['kode_mk' => 'IF201', 'nip' => 'D001'],
            ['kode_mk' => 'IF202', 'nip' => 'D002'],
            ['kode_mk' => 'IF301', 'nip' => 'D001'],
            ['kode_mk' => 'IF302', 'nip' => 'D002'],
            ['kode_mk' => 'IF401', 'nip' => 'D001'],
            ['kode_mk' => 'IF402', 'nip' => 'D002'],
            ['kode_mk' => 'IF501', 'nip' => 'D001'],
            ['kode_mk' => 'IF502', 'nip' => 'D002'],
        ];

        foreach ($pengampuData as $p) {
            Pengampu::create($p);
        }
    }
}
