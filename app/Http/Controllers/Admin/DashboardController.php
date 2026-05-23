<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\Lokasi;
use App\Models\Pompa;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',

            // Data statistik nyata
            'totalLaporan'    => LaporanHarian::count(),
            'laporanPending'  => LaporanHarian::where('status_code', 1)->count(),
            'laporanApproved' => LaporanHarian::where('status_code', 3)->count(),
            'laporanRejected' => LaporanHarian::where('status_code', 4)->count(),
            'laporanRevisi'   => LaporanHarian::where('status_code', 2)->count(),
            'laporanHariIni'  => LaporanHarian::whereDate('tanggal', today())->count(),

            'totalUser'   => User::where('role', 'user')->count(),
            'totalPompa'  => Pompa::where('is_deleted', 0)->count(),
            'totalLokasi' => Lokasi::where('is_deleted', 0)->count(),

            // Laporan terbaru untuk tabel
            'recentLaporan' => LaporanHarian::with('pompa.lokasi', 'user')
                                    ->latest()
                                    ->take(10)
                                    ->get(),

            // Data chart: laporan per hari 7 hari terakhir
            'chartLabels' => collect(range(6, 0))->map(fn($i) =>
                now()->subDays($i)->format('d M')
            )->toJson(),

            'chartData' => collect(range(6, 0))->map(fn($i) =>
                LaporanHarian::whereDate('tanggal', now()->subDays($i))->count()
            )->toJson(),
        ];

        return view('admin.pages.dashboard', $data);
    }
}