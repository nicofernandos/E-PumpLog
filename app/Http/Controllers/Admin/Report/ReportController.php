<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanDetilJam;
use App\Models\LaporanHarian;
use App\Models\Lokasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
            'detilJam' => function ($q) {
                $q->where('is_deleted',0);
            }
        ])
        ->where('is_deleted',0)
        ->whereIn('status', ['draft', 'finalized', 'verified','approved']); // Exclude drafts

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('injeksi_ke', 'like', "%{$search}%")
                ->orWhereHas('pompa', function($q2) use ($search) {
                    $q2->where('kodepompa', 'like', "%{$search}%")
                        ->where('is_deleted',0);
                })
                ->orWhereHas('pompa.lokasi', function($q3) use ($search) {
                    $q3->where('namasp', 'like', "%{$search}%")
                        ->where('is_deleted',0);
                })
                ->orWhereHas('user', function($q4) use ($search) {
                    $q4->where('name', 'like', "%{$search}%")
                        ->where('is_deleted',0);
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
                        ->where('is_deleted', 0)
                        ->orderBy('namasp')
                        ->get();

        // Statistics
        $totalReports = LaporanHarian::where('is_deleted',0)
        ->whereIn('status', ['submitted', 'approved', 'rejected'])->count();
        $reportsToday = LaporanHarian::where('is_deleted',0)
                                    ->whereIn('status', ['submitted', 'approved', 'rejected'])
                                    ->whereDate('tanggal', Carbon::today())
                                    ->count();
        $pendingApproval = LaporanHarian::where('is_deleted',0)
                                    ->whereIn('status', ['submitted', 'pending'])->count();
        $reportsThisMonth = LaporanHarian::where('is_deleted',0)
                                        ->whereIn('status', ['submitted', 'approved', 'rejected'])
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

            // soft delete relasi detail jam
            $report->detilJam()->update(['is_deleted' => 1]);
            if (method_exists($report, 'runningHours')) {
                $report->runningHours()->update(['is_deleted' => 1]);
            }
            $report->update([
                'is_deleted' => 1
            ]);

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

    public function approved(Request $request)
    {
        // Get filter inputs
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $lokasiId = $request->input('lokasi_id');
        $approvedBy = $request->input('approved_by');

        // Query builder with eager loading
        $query = LaporanHarian::with([
            'user:id,name,email',
            'pompa:id,kodepompa,jenispompa,lokasi_id',
            'pompa.lokasi:id,kodesp,namasp',
            'detilJam',
            'approvedBy:id,name,email'
        ])
        ->where('is_deleted',0)
        ->whereIn('status', 'approved'); // Only approved reports

        // Apply search filter
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
                  })
                  ->orWhereHas('approvedBy', function($q5) use ($search) {
                      $q5->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply date range filter
        if ($dateFrom && $dateTo) {
            $query->whereBetween('tanggal', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->whereDate('tanggal', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('tanggal', '<=', $dateTo);
        }

        // Apply lokasi filter
        if ($lokasiId) {
            $query->where('lokasisp_id', $lokasiId);
        }

        // Apply approved_by filter
        if ($approvedBy) {
            $query->where('approved_by', $approvedBy);
        }

        // Order by approval date (latest first)
        $query->orderBy('approved_at', 'desc')
              ->orderBy('tanggal', 'desc');

        // Paginate results
        $reports = $query->paginate(15)->withQueryString();

        // Add hourly entries count to each report
        $reports->getCollection()->transform(function ($report) {
            $report->hourly_entries_count = $report->detilJam->count();
            return $report;
        });

        // Get all lokasi for filter dropdown
        $lokasi = Lokasi::select('id', 'kodesp', 'namasp')
                        ->orderBy('namasp')
                        ->get();

        // Get all users who have approved reports (for filter)
        $approvers = User::whereHas('approvedReports')
                         ->select('id', 'name')
                         ->orderBy('name')
                         ->get();

        // Calculate statistics
        $totalApproved = LaporanHarian::where('status', 'approved')->count();
        
        $approvedToday = LaporanHarian::where('status', 'approved')
                                      ->whereDate('approved_at', Carbon::today())
                                      ->count();
        
        $approvedThisMonth = LaporanHarian::where('status', 'approved')
                                          ->whereYear('approved_at', Carbon::now()->year)
                                          ->whereMonth('approved_at', Carbon::now()->month)
                                          ->count();
        
        $totalCumulative = LaporanHarian::where('status', 'approved')
                                        ->sum('total_cumulative');

        // Title + Subtitle
        $title = 'E-PumpLog | Laporan Disetujui';
        $subtitle = 'Laporan Harian yang Telah Disetujui';

        return view('admin.pages.report.approve', compact(
            'reports',
            'lokasi',
            'approvers',
            'totalApproved',
            'approvedToday',
            'approvedThisMonth',
            'totalCumulative',
            'title',
            'subtitle'
        ));
    }

    public function revokeApproval(Request $request, $id)
    {
        try {
            $report = LaporanHarian::findOrFail($id);
            
            // Check if report is approved
            if ($report->status !== 'approved') {
                return redirect()->back()->with('warning', 'Laporan ini belum di-approve.');
            }

            // Revoke approval - back to submitted
            $report->update([
                'status' => 'submitted',
                'approved_by' => null,
                'approved_at' => null
            ]);

            return redirect()->back()->with('success', 'Approval berhasil dibatalkan! Laporan kembali ke status Submitted.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan approval: ' . $e->getMessage());
        }
    }

    public function revisi(Request $request)
    {
        return view('admin.pages.report.revisi');   
    }

}