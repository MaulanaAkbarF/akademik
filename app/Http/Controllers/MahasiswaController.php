<?php
// app/Http/Controllers/MahasiswaController.php - Updated

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalAkademik;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'mahasiswa') {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Get today's schedule
        $today = now()->format('l');
        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        $hariIni = $hariIndonesia[$today];

        $jadwalHariIni = [];
        if ($mahasiswa && $mahasiswa->id_golongan) {
            $jadwalHariIni = JadwalAkademik::with(['matakuliah', 'ruang'])
                ->where('hari', $hariIni)
                ->where('id_gol', $mahasiswa->id_golongan)
                ->whereHas('matakuliah.krs', function($query) use ($mahasiswa) {
                    $query->where('nim', $mahasiswa->nim);
                })
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('mahasiswa.dashboard', compact('jadwalHariIni', 'hariIni'));
    }

    public function jadwal()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get all schedules for student's mata kuliah
        $jadwalMingguan = JadwalAkademik::with(['matakuliah', 'ruang'])
            ->where('id_gol', $mahasiswa->id_golongan)
            ->whereHas('matakuliah.krs', function($query) use ($mahasiswa) {
                $query->where('nim', $mahasiswa->nim);
            })
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('mahasiswa.jadwal', compact('jadwalMingguan'));
    }
}