<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GolonganSeeder::class,
            AdminSeeder::class,
            RuangSeeder::class,
            MatakuliahSeeder::class,
            KrsSeeder::class,
            PengampuSeeder::class,
        ]);
    }
}