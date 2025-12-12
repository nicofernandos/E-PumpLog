<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanDetilJam;
use App\Models\LaporanHarian;
use App\Models\Lokasi;
use app\Models\User;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter inputs
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $lokasiId = $request->input('lokasi_id');
        $status = $request->input('status');

        // Query builder with eager loading
        $query = LaporanHarian::with([
            'user:id,name,email',
            'pompa:id,kodepompa,jenispompa,lokasi_id',
            'pompa.lokasi:id,kodesp,namasp',
            'detilJam'
        ])
        ->whereIn('status', ['submitted', 'approved', 'rejected']); // Exclude drafts

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('injeksi_ke', 'like', "%{$search}%")
                ->orWhereHas('pompa', function($q2) use ($search) {
                    $q2->where('kodepompa', 'like', "%{$search}%");
                })
                ->orWhereHas('pompa.lokasi', function($q3) use ($search) {
                    $q3->where('namasp', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($q4) use ($search) {
                    $q4->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Date range
        if ($dateFrom && $dateTo) {
            $query->whereBetween('tanggal', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->whereDate('tanggal', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('tanggal', '<=', $dateTo);
        }

        // Lokasi filter
        if ($lokasiId) {
            $query->where('lokasisp_id', $lokasiId);
        }

        // Status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Order by latest
        $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Pagination
        $reports = $query->paginate(15)->withQueryString();

        // Hourly entries count
        $reports->getCollection()->transform(function ($report) {
            $report->hourly_entries_count = $report->detilJam->count();
            return $report;
        });

        // Lokasi list
        $lokasi = Lokasi::select('id', 'kodesp', 'namasp')
                        ->orderBy('namasp')
                        ->get();

        // Statistics
        $totalReports = LaporanHarian::whereIn('status', ['submitted', 'approved', 'rejected'])->count();
        $reportsToday = LaporanHarian::whereIn('status', ['submitted', 'approved', 'rejected'])
                                    ->whereDate('tanggal', Carbon::today())
                                    ->count();
        $pendingApproval = LaporanHarian::whereIn('status', ['submitted', 'pending'])->count();
        $reportsThisMonth = LaporanHarian::whereIn('status', ['submitted', 'approved', 'rejected'])
                                        ->whereYear('tanggal', Carbon::now()->year)
                                        ->whereMonth('tanggal', Carbon::now()->month)
                                        ->count();

        // Title + Subtitle
        $title = 'E-PumpLog | Laporan Harian Injeksi Pompa';
        $subtitle = 'Laporan Harian Injeksi Pompa Injeksi dan Engine Pompa';

        return view('admin.pages.report.index', compact(
            'reports',
            'lokasi',
            'totalReports',
            'reportsToday',
            'pendingApproval',
            'reportsThisMonth',
            'title',
            'subtitle'
        ));
    }

    public function show($id) 
    {
        $report = LaporanHarian::with([
            'user:id,name,email',
            'pompa:id,kodepompa,jenispompa,lokasi_id',
            'pompa.lokasi:id,kodesp,namasp',
            'detilJam' => function($query) {
                $query->orderBy('jam_ke', 'asc');
            },
            'runningHours',
            'operatorSiang:id,name',
            'operatorMalam:id,name'
        ])->findOrFail($id);

        $data = [
            'id' => $report->id,
            'tanggal' => Carbon::parse($report->tanggal)->format('d/m/Y'),
            'kodepompa' => $report->pompa->kodepompa ?? '-',
            'jenispompa' => $report->pompa->jenispompa ?? '-',
            'injeksi_ke' => $report->injeksi_ke ?? '-',
            'lokasi' => $report->pompa->lokasi->namasp ?? '-',
            'kodesp' => $report->pompa->lokasi->kodesp ?? '-',
            'user' => $report->user->name ?? '-',
            'status' => $report->status,
            'status_label' => $this->getStatusLabel($report->status),
            'total_cumulative' => number_format($report->total_cumulative ?? 0, 2),
            'entries_count' => $report->detilJam->count(),
            'created_at' => Carbon::parse($report->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($report->updated_at)->format('d/m/Y H:i'),
            'hourly_data' => $report->detilJam->map(function($detil) {
                return [
                    'jam_ke' => sprintf('%02d:00', $detil->jam_ke),
                    'total_bbls' => $detil->total_bbls ?? '-',
                    'rate_jam' => $detil->rate_jam ?? '-',
                    'cumm_bbls' => $detil->cumm_bbls ?? '-',
                    'rate_hari' => $detil->rate_hari ?? '-',
                    'inj_psi' => $detil->inj_psi ?? '-',
                    'inj_rpm' => $detil->inj_rpm ?? '-',
                    'oil_cf' => $detil->oil_cf ?? '-',
                    'press_cf' => $detil->press_cf ?? '-',
                    'water_cf' => $detil->water_cf ?? '-',
                    'freq_hz' => $detil->freq_hz ?? '-',
                ];
            }),
            'keterangan' => $this->parseKeterangan($report->keterangan),
            'running_hours' => $report->runningHours ?? []
        ];

        return response()->json($data);
    }

    public function approve(Request $request, $id)
    {
        try{
            $report = LaporanHarian::findOrFail($id);

            if ($report->status === 'Approved') {
                return redirect()->back()->with('warning','Laporan sudah di approve sebelumnya.');
            }

            $report->update([
                'status' => 'Approved',
                'approved_by' => auth()->user()->id,
                'approved_at' => now(),
            ]);

             Alert::success('Success', 'Berhasil Memperbarui Lokasi SP: ' . $lokasi->status);
             return redirect()->route('admin.reports.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengapprove laporan: ' . $e->getMessage());
            return redirect()->route('admin.lokasi.index');

            }

    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $report = LaporanHarian::findOrFail($id);

            if ($report->status === 'Rejected') {
                Alert::warning('Warning', 'Laporan sudah di reject sebelumnya.');
                return redirect()->route('admin.reports.index');
            }

            $report->update([
                'status' => 'Rejected',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            Alert::success('Success', 'Laporan berhasil di reject.');
            return redirect()->route('admin.reports.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mereject laporan: ' . $e->getMessage());
            return redirect()->route('admin.reports.index');
        }



    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $report = LaporanHarian::findOrFail($id);
            $report->detilJam()->delete();
            
            if(method_exists($report, 'runningHours')) {
                $report->runningHours()->delete();
            }

            $report->delete();

            DB::commit();
            Alert::success('Success', 'Laporan berhasil dihapus.');
            return redirect()->route('admin.reports.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal menghapus laporan: ' . $e->getMessage());
            return redirect()->route('admin.reports.index');
        }
    }

    public function exportExcel(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur ekspor Excel sedang dalam pengembangan.');
    }

    public function exportPdf(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur ekspor PDF sedang dalam pengembangan.');
    }

    public function print($id)
    {
        return redirect()->back()->with('info', 'Fitur cetak laporan sedang dalam pengembangan.');
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'submitted' => 'Pending',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return $labels[$status] ?? 'Unknown';
    }


    private function parseKeterangan($keterangan)
    {
        if (empty($keterangan)) {
            return [];
        }

        // If it's already an array
        if (is_array($keterangan)) {
            return $keterangan;
        }

        // Try to decode JSON
        $decoded = json_decode($keterangan, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Return empty array if parsing fails
        return [];
    }

}