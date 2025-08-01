<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SksTransaction;
use App\Models\SksSaldo;
use App\Models\Matakuliah;
use App\Models\Krs;

class SksTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Custom role check method
    protected function checkRole($role)
    {
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized access');
        }
    }

    // Mahasiswa - Lihat saldo SKS dan transaksi
    public function index()
    {
        $this->checkRole('mahasiswa');

        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get or create SKS saldo
        $sksSaldo = SksSaldo::firstOrCreate(
            ['nim' => $mahasiswa->nim],
            ['sks_tersedia' => 0, 'sks_terpakai' => 0, 'total_sks_dibeli' => 0]
        );

        // Get transactions
        $transactions = SksTransaction::where('nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate current SKS usage
        $currentSksUsage = $mahasiswa->getTotalSksAmbil();

        // Update sks_terpakai if different
        if ($sksSaldo->sks_terpakai != $currentSksUsage) {
            $sksSaldo->update(['sks_terpakai' => $currentSksUsage]);
        }

        return view('mahasiswa.sks.index', compact('sksSaldo', 'transactions', 'currentSksUsage'));
    }

    // Mahasiswa - Form beli SKS
    public function create()
    {
        $this->checkRole('mahasiswa');

        $hargaPerSks = 75000; // Rp 75,000 per SKS

        return view('mahasiswa.sks.create', compact('hargaPerSks'));
    }

    // Mahasiswa - Proses pembelian SKS
    public function store(Request $request)
    {
        $this->checkRole('mahasiswa');

        $request->validate([
            'jumlah_sks' => 'required|integer|min:1|max:24',
            'metode_pembayaran' => 'required|in:transfer_bank,e_wallet,cash,va_bni,va_bca,va_mandiri',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $hargaPerSks = 75000;
        $totalHarga = $request->jumlah_sks * $hargaPerSks;

        // Handle file upload
        $buktiPembayaran = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        // Create transaction
        SksTransaction::create([
            'nim' => $mahasiswa->nim,
            'jumlah_sks' => $request->jumlah_sks,
            'harga_per_sks' => $hargaPerSks,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'pending',
            'tanggal_transaksi' => now(),
            'bukti_pembayaran' => $buktiPembayaran,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('mahasiswa.sks.index')
            ->with('success', 'Transaksi pembelian SKS berhasil dibuat. Menunggu konfirmasi admin.');
    }

    // Admin - Lihat semua transaksi
    public function indexAdmin()
    {
        $this->checkRole('admin');

        $transactions = SksTransaction::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.sks.index', compact('transactions'));
    }

    // Admin - Approve/Reject transaksi
    public function updateStatus(Request $request, SksTransaction $transaction)
    {
        $this->checkRole('admin');

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $transaction->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan ?? $transaction->keterangan,
        ]);

        // If approved, update SKS saldo
        if ($request->status === 'approved') {
            $sksSaldo = SksSaldo::firstOrCreate(
                ['nim' => $transaction->nim],
                ['sks_tersedia' => 0, 'sks_terpakai' => 0, 'total_sks_dibeli' => 0]
            );

            $sksSaldo->increment('sks_tersedia', $transaction->jumlah_sks);
            $sksSaldo->increment('total_sks_dibeli', $transaction->jumlah_sks);
        }

        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        return redirect()->back()->with('success', "Transaksi berhasil {$statusText}.");
    }

    // Mahasiswa - Ambil mata kuliah (KRS)
    public function takeMatakuliah()
    {
        $this->checkRole('mahasiswa');

        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get available mata kuliah
        $matakuliahTersedia = Matakuliah::where('is_open', true)
            ->whereDoesntHave('krs', function ($query) use ($mahasiswa) {
                $query->where('nim', $mahasiswa->nim);
            })
            ->get();

        // Get SKS saldo
        $sksSaldo = SksSaldo::firstOrCreate(
            ['nim' => $mahasiswa->nim],
            ['sks_tersedia' => 0, 'sks_terpakai' => 0, 'total_sks_dibeli' => 0]
        );

        // Get current KRS
        $currentKrs = Krs::with('matakuliah')->where('nim', $mahasiswa->nim)->get();

        return view('mahasiswa.krs.index', compact('matakuliahTersedia', 'sksSaldo', 'currentKrs'));
    }

    // Mahasiswa - Ambil mata kuliah
    public function storeMatakuliah(Request $request)
    {
        $this->checkRole('mahasiswa');

        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $matakuliah = Matakuliah::where('kode_mk', $request->kode_mk)->first();

        // Check if already taken
        $existingKrs = Krs::where('nim', $mahasiswa->nim)
            ->where('kode_mk', $request->kode_mk)
            ->first();

        if ($existingKrs) {
            return redirect()->back()->with('error', 'Anda sudah mengambil mata kuliah ini.');
        }

        // Check if course is open
        if (!$matakuliah->is_open) {
            return redirect()->back()->with('error', 'Mata kuliah ini sudah ditutup.');
        }

        // Check if there’s enough stock
        $currentEnrolled = Krs::where('kode_mk', $request->kode_mk)->count();
        if ($currentEnrolled >= $matakuliah->stok) {
            return redirect()->back()->with('error', 'Kuota mata kuliah ini sudah penuh.');
        }

        // Check SKS availability
        $sksSaldo = SksSaldo::firstOrCreate(
            ['nim' => $mahasiswa->nim],
            ['sks_tersedia' => 0, 'sks_terpakai' => 0, 'total_sks_dibeli' => 0]
        );

        if (!$mahasiswa->canTakeMatakuliah($matakuliah)) {
            return redirect()->back()->with('error',
                "SKS tidak mencukupi. Anda membutuhkan {$matakuliah->sks} SKS, tersedia {$mahasiswa->getSksAvailable()} SKS.");
        }

        // Create KRS
        Krs::create([
            'nim' => $mahasiswa->nim,
            'kode_mk' => $request->kode_mk,
        ]);

        // Update SKS usage
        $sksSaldo->increment('sks_terpakai', $matakuliah->sks);

        return redirect()->back()->with('success',
            "Berhasil mengambil mata kuliah {$matakuliah->nama_mk} ({$matakuliah->sks} SKS).");
    }

    // Mahasiswa - Drop mata kuliah
    public function dropMatakuliah(Request $request)
    {
        $this->checkRole('mahasiswa');

        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk'
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $matakuliah = Matakuliah::where('kode_mk', $request->kode_mk)->first();

        $krs = Krs::where('nim', $mahasiswa->nim)
            ->where('kode_mk', $request->kode_mk)
            ->first();

        if (!$krs) {
            return redirect()->back()->with('error', 'Anda tidak mengambil mata kuliah ini.');
        }

        // Delete KRS
        $krs->delete();

        // Update SKS usage
        $sksSaldo = SksSaldo::where('nim', $mahasiswa->nim)->first();
        if ($sksSaldo) {
            $sksSaldo->decrement('sks_terpakai', $matakuliah->sks);
        }

        return redirect()->back()->with('success',
            "Berhasil membatalkan mata kuliah {$matakuliah->nama_mk} ({$matakuliah->sks} SKS).");
    }
}