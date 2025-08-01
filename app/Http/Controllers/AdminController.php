<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SksTransaction;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Matakuliah;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Get statistics
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalMatakuliah = Matakuliah::count();
        
        // SKS Transaction statistics
        $pendingTransactions = SksTransaction::where('status', 'pending')->count();
        $approvedTransactions = SksTransaction::where('status', 'approved')->count();
        $totalRevenue = SksTransaction::where('status', 'approved')->sum('total_harga');
        
        // Recent transactions
        $recentTransactions = SksTransaction::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa', 
            'totalDosen', 
            'totalMatakuliah',
            'pendingTransactions',
            'approvedTransactions', 
            'totalRevenue',
            'recentTransactions'
        ));
    }
}