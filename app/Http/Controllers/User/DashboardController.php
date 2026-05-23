<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\Lokasi;
use App\Models\Pompa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← ini wajib ditambah

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $data = [
            'title' => 'Dashboard E-PumpLog',

            'totalPompa'    => Pompa::where('is_deleted', 0)->count(),
            'pompaAktif'    => Pompa::where('is_deleted', 0)->where('status', 'aktif')->count(),
            'pompaNonAktif' => Pompa::where('is_deleted', 0)->where('status', '!=', 'aktif')->count(),
            'totalLokasi'   => Lokasi::where('is_deleted', 0)->count(),

            'logHariIni'     => LaporanHarian::where('user_id', $userId)
                                    ->whereDate('tanggal', today())->count(),
            'totalLog'       => LaporanHarian::where('user_id', $userId)->count(),
            'totalSubmitted' => LaporanHarian::where('user_id', $userId)
                                    ->where('status_code', 1)->count(),
            'totalApproved'  => LaporanHarian::where('user_id', $userId)
                                    ->where('status_code', 3)->count(),

            'recentLogs' => LaporanHarian::with('pompa.lokasi')
                                ->where('user_id', $userId)
                                ->latest()
                                ->take(5)
                                ->get(),
        ];

        return view('user.pages.dashboard', $data);
    }
}