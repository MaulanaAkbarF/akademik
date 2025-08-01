<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Golongan;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $golongan = Golongan::where('nama_gol', 'A')->first();

        $dosen = Dosen::create([
            'nip' => 'D001',
            'nama' => 'Dr. John Doe',
            'alamat' => 'Jl. Universitas No. 1',
            'nohp' => '081234567890',
        ]);

        $dosenUser = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'john.doe@university.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => 'D001',
        ]);

        $dosen2 = Dosen::create([
            'nip' => 'D002',
            'nama' => 'Dr. Jane Smith',
            'alamat' => 'Jl. Universitas No. 2',
            'nohp' => '081234567891',
        ]);

        $dosenUser2 = User::create([
            'name' => 'Dr. Jane Smith',
            'email' => 'jane.smith@university.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => 'D002',
        ]);

        $adminUser = User::create([
            'name' => 'Admin System',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $mahasiswaUser = User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@student.university.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $mahasiswa = Mahasiswa::create([
            'nim' => 'M001',
            'nama' => 'Alice Johnson',
            'alamat' => 'Jl. Mahasiswa No. 1',
            'nohp' => '082345678901',
            'semester' => 5,
            'id_golongan' => $golongan->id_golongan,
            'id_user_mahasiswa' => $mahasiswaUser->id,
        ]);

        $mahasiswaUser->update(['nim' => 'M001']);

        $mahasiswaUser2 = User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@student.university.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $mahasiswa2 = Mahasiswa::create([
            'nim' => 'M002',
            'nama' => 'Bob Wilson',
            'alamat' => 'Jl. Mahasiswa No. 2',
            'nohp' => '082345678902',
            'semester' => 5,
            'id_golongan' => $golongan->id_golongan,
            'id_user_mahasiswa' => $mahasiswaUser2->id,
        ]);

        $mahasiswaUser2->update(['nim' => 'M002']);
    }
}