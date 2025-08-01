<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Golongan;

class GolonganSeeder extends Seeder
{
    public function run(): void
    {
        $golongan = [
            ['nama_gol' => 'A'],
            ['nama_gol' => 'B'],
            ['nama_gol' => 'C'],
            ['nama_gol' => 'D'],
        ];

        foreach ($golongan as $gol) {
            Golongan::create($gol);
        }
    }
}
