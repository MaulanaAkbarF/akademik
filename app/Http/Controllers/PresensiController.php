<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalAkademik;
use App\Models\PresensiAkademik;
use App\Models\Krs;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mahasiswa - Tampilkan mata kuliah hari ini
    public function indexMahasiswa()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get today's day name in Indonesian
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

        // Get mata kuliah yang diambil mahasiswa dan ada jadwal hari ini
        $jadwalHariIni = JadwalAkademik::with(['matakuliah', 'ruang'])
            ->where('hari', $hariIni)
            ->where('id_gol', $mahasiswa->id_golongan)
            ->whereHas('matakuliah.krs', function($query) use ($mahasiswa) {
                $query->where('nim', $mahasiswa->nim);
            })
            ->orderBy('jam_mulai')
            ->get();

        // Check presensi yang sudah dilakukan hari ini
        $presensiHariIni = PresensiAkademik::where('nim', $mahasiswa->nim)
            ->where('tanggal', now()->format('Y-m-d'))
            ->pluck('kode_mk')
            ->toArray();

        return view('mahasiswa.presensi.index', compact('jadwalHariIni', 'presensiHariIni', 'hariIni'));
    }

    // Mahasiswa - Lakukan presensi
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk'
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        // Check if already present today for this subject
        $existingPresensi = PresensiAkademik::where('nim', $mahasiswa->nim)
            ->where('kode_mk', $request->kode_mk)
            ->where('tanggal', $today)
            ->first();

        if ($existingPresensi) {
            return redirect()->back()->with('error', 'Anda sudah melakukan presensi untuk mata kuliah ini hari ini.');
        }

        // Check if the subject has schedule today and is within time range
        $todayName = now()->format('l');
        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        $hariIni = $hariIndonesia[$todayName];

        $jadwal = JadwalAkademik::where('hari', $hariIni)
            ->where('kode_mk', $request->kode_mk)
            ->where('id_gol', $mahasiswa->id_golongan)
            ->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Mata kuliah ini tidak ada jadwal hari ini.');
        }

        // Check if current time is within schedule
        if ($currentTime < $jadwal->jam_mulai || $currentTime > $jadwal->jam_selesai) {
            return redirect()->back()->with('error', 'Presensi hanya dapat dilakukan selama jam kuliah berlangsung.');
        }

        // Check if student is enrolled in this subject
        $krs = Krs::where('nim', $mahasiswa->nim)
            ->where('kode_mk', $request->kode_mk)
            ->first();

        if (!$krs) {
            return redirect()->back()->with('error', 'Anda belum mengambil mata kuliah ini.');
        }

        // Create presensi record
        PresensiAkademik::create([
            'hari' => $hariIni,
            'tanggal' => $today,
            'jam_presensi' => $currentTime,
            'status_kehadiran' => 'hadir',
            'nim' => $mahasiswa->nim,
            'kode_mk' => $request->kode_mk,
        ]);

        return redirect()->back()->with('success', 'Presensi berhasil dicatat.');
    }

    // Dosen - Tampilkan daftar mata kuliah yang diampu
    public function indexDosen()
    {
        $dosen = Auth::user()->dosen;
        
        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Get mata kuliah yang diampu dosen
        $matakuliahDiampu = $dosen->pengampu()->with('matakuliah')->get();

        return view('dosen.presensi.index', compact('matakuliahDiampu'));
    }

    // Dosen - Lihat detail presensi mata kuliah
    public function showDosen($kodeMk)
    {
        $dosen = Auth::user()->dosen;
        
        // Verify dosen mengampu mata kuliah ini
        $pengampu = $dosen->pengampu()->where('kode_mk', $kodeMk)->first();
        
        if (!$pengampu) {
            return redirect()->back()->with('error', 'Anda tidak mengampu mata kuliah ini.');
        }

        $matakuliah = $pengampu->matakuliah;
        
        // Get presensi untuk mata kuliah ini (7 hari terakhir)
        $presensi = PresensiAkademik::with(['mahasiswa', 'matakuliah'])
            ->where('kode_mk', $kodeMk)
            ->where('tanggal', '>=', now()->subDays(7))
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_presensi', 'desc')
            ->get();

        // Get jadwal mata kuliah
        $jadwal = JadwalAkademik::with(['ruang', 'golongan'])
            ->where('kode_mk', $kodeMk)
            ->get();

        return view('dosen.presensi.show', compact('matakuliah', 'presensi', 'jadwal'));
    }
}