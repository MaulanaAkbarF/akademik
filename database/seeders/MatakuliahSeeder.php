<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matakuliah;
use App\Models\JadwalAkademik;
use App\Models\Golongan;

class MatakuliahSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil golongan yang sudah ada
        $golongan = Golongan::where('nama_gol', 'A')->first();
        if (!$golongan) {
            throw new \Exception('Golongan dengan nama_gol "A" tidak ditemukan.');
        }

        // Buat data matakuliah
        $matakuliah = [
            ['kode_mk' => 'IF101', 'nama_mk' => 'Pemrograman Dasar', 'sks' => 3, 'semester' => 1, 'stok' => 40, 'is_open' => true],
            ['kode_mk' => 'IF102', 'nama_mk' => 'Matematika Diskrit', 'sks' => 3, 'semester' => 1, 'stok' => 40, 'is_open' => true],
            ['kode_mk' => 'IF201', 'nama_mk' => 'Struktur Data', 'sks' => 3, 'semester' => 2, 'stok' => 35, 'is_open' => true],
            ['kode_mk' => 'IF202', 'nama_mk' => 'Basis Data', 'sks' => 3, 'semester' => 2, 'stok' => 35, 'is_open' => true],
            ['kode_mk' => 'IF301', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3, 'stok' => 30, 'is_open' => true],
            ['kode_mk' => 'IF302', 'nama_mk' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester' => 3, 'stok' => 30, 'is_open' => true],
            ['kode_mk' => 'IF401', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 4, 'stok' => 25, 'is_open' => true],
            ['kode_mk' => 'IF402', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 4, 'stok' => 25, 'is_open' => true],
            ['kode_mk' => 'IF501', 'nama_mk' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 5, 'stok' => 20, 'is_open' => true],
            ['kode_mk' => 'IF502', 'nama_mk' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 5, 'stok' => 20, 'is_open' => true],
        ];

        foreach ($matakuliah as $mk) {
            Matakuliah::create($mk);
        }

        // Verifikasi data matakuliah sudah dibuat
        if (!Matakuliah::where('kode_mk', 'IF501')->exists()) {
            throw new \Exception('Data matakuliah IF501 gagal dibuat.');
        }

        // Buat data jadwal akademik
        $jadwal = [
            ['hari' => 'Senin', 'kode_mk' => 'IF101', 'id_ruang' => 1, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:00:00'],
            ['hari' => 'Senin', 'kode_mk' => 'IF201', 'id_ruang' => 2, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '10:30:00', 'jam_selesai' => '12:30:00'],
            ['hari' => 'Selasa', 'kode_mk' => 'IF102', 'id_ruang' => 1, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:00:00'],
            ['hari' => 'Selasa', 'kode_mk' => 'IF202', 'id_ruang' => 3, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '10:30:00', 'jam_selesai' => '12:30:00'],
            ['hari' => 'Rabu', 'kode_mk' => 'IF301', 'id_ruang' => 6, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:00:00'],
            ['hari' => 'Rabu', 'kode_mk' => 'IF401', 'id_ruang' => 4, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '10:30:00', 'jam_selesai' => '12:30:00'],
            ['hari' => 'Kamis', 'kode_mk' => 'IF302', 'id_ruang' => 7, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:00:00'],
            ['hari' => 'Kamis', 'kode_mk' => 'IF402', 'id_ruang' => 5, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '10:30:00', 'jam_selesai' => '12:30:00'],
            ['hari' => 'Jumat', 'kode_mk' => 'IF501', 'id_ruang' => 2, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:00:00'],
            ['hari' => 'Jumat', 'kode_mk' => 'IF502', 'id_ruang' => 8, 'id_gol' => $golongan->id_golongan, 'jam_mulai' => '10:30:00', 'jam_selesai' => '12:30:00'],
        ];

        foreach ($jadwal as $j) {
            JadwalAkademik::create($j);
        }
    }
}