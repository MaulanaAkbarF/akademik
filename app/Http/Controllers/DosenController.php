<?php
// app/Http/Controllers/DosenController.php - Updated

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalAkademik;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'dosen') {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $dosen = Auth::user()->dosen;
        
        // Get today's schedule for dosen
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
        if ($dosen) {
            // Get today's schedule based on mata kuliah yang diampu
            $kodeMkDiampu = $dosen->pengampu->pluck('kode_mk');
            
            $jadwalHariIni = JadwalAkademik::with(['matakuliah', 'ruang', 'golongan'])
                ->where('hari', $hariIni)
                ->whereIn('kode_mk', $kodeMkDiampu)
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('dosen.dashboard', compact('jadwalHariIni', 'hariIni'));
    }

    public function jadwal()
    {
        $dosen = Auth::user()->dosen;
        
        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Get all schedules for dosen's mata kuliah
        $kodeMkDiampu = $dosen->pengampu->pluck('kode_mk');
        
        $jadwalMingguan = JadwalAkademik::with(['matakuliah', 'ruang', 'golongan'])
            ->whereIn('kode_mk', $kodeMkDiampu)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('dosen.jadwal', compact('jadwalMingguan'));
    }
}