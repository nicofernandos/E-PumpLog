<?php

namespace App\Http\Controllers\User; 

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\LaporanDetilJam;
use App\Models\Lokasi;
use App\Models\Pompa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanHarian::query();

        // hanya laporan milik user login
        $query->where('user_id', auth()->id());

        // filter status
        if ($request->status == 'approved') {
            $query->where('status_code', 3);
        }

        if ($request->status == 'revision') {
            $query->where('status_code', 2);
        }

        if ($request->status == 'submitted') {
            $query->where('status_code', 1);
        }

        $reports = $query->latest()->paginate(10);

        $data = [
            'title' => 'E-PumpLog | Report',
            'subtitle' => 'E-PumpLog | Report',

            'reports' => $reports,

            'lokasi' => Lokasi::where('is_deleted',0)->get(),

            'pompa' => Pompa::with('lokasi')
                ->where('is_deleted',0)
                ->get()
        ];

        return view('user.pages.report.index', $data);
    }

    public function list(Request $request)
    {
        $userId = auth()->id();

        // Base query hanya milik user login
        $query = LaporanHarian::where('user_id', $userId);

        // Filter status
        $statusFilter = [
            'draft'     => 0,
            'submitted' => 1,
            'revision'  => 2,
            'approved'  => 3,
            'rejected'  => 4,
        ];

        if ($request->status && isset($statusFilter[$request->status])) {
            $query->where('status_code', $statusFilter[$request->status]);
        }

        $reports = $query->with(['lokasi', 'pompa'])->latest()->paginate(10);

        // Statistik — tidak terpengaruh filter aktif
        $base = LaporanHarian::where('user_id', $userId);

        $data = [
            'title'    => 'E-PumpLog | Report',
            'subtitle' => 'E-PumpLog | Report',

            'reports'        => $reports,
            'totalAll'       => (clone $base)->count(),
            'totalDraft'     => (clone $base)->where('status_code', 0)->count(),
            'totalSubmitted' => (clone $base)->where('status_code', 1)->count(),
            'totalRevision'  => (clone $base)->where('status_code', 2)->count(),
            'totalApproved'  => (clone $base)->where('status_code', 3)->count(),
            'totalRejected'  => (clone $base)->where('status_code', 4)->count(),

            'lokasi' => Lokasi::where('is_deleted', 0)->get(),
            'pompa'  => Pompa::with('lokasi')->where('is_deleted', 0)->get(),
        ];

        return view('user.pages.report.list', $data);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'lokasi_id' => 'required|exists:lokasisp,id',
                'pompa_id' => 'required|exists:pompa,id',
                'tanggal' => 'required|date',
                'injector_well' => 'required|string|max:50',
                'flow' => 'nullable|array', 
                'flow.*.total_bbls' => 'nullable|numeric',
                'flow.*.rate_jam' => 'nullable|numeric',
                'flow.*.cumm' => 'nullable|numeric',
                'flow.*.rate_hari' => 'nullable|numeric',
                'flow.*.injeksi_psi' => 'nullable|numeric',
                'flow.*.rpm' => 'nullable|numeric',
                'flow.*.oil_cf' => 'nullable|string', 
                'flow.*.press_cf' => 'nullable|string',
                'flow.*.water_cf' => 'nullable|string',
                'flow.*.freq_hz' => 'nullable|numeric',
                'keterangan' => 'nullable|array',
                'keterangan.*.dari_jam' => 'nullable|string',
                'keterangan.*.sd_jam' => 'nullable|string',
                'keterangan.*.keterangan' => 'nullable|string',
                'keterangan.*.col4' => 'nullable|string',
                'keterangan.*.dt_jam' => 'nullable|string',
            ]);

            // Hitung total cumulative
            $totalCumulative = 0;
            if (!empty($validatedData['flow'])) {
                foreach ($validatedData['flow'] as $flowData) {
                    if (isset($flowData['cumm'])) {
                        $totalCumulative += $flowData['cumm'];
                    }
                }
            }

            $keteranganText = '';
            if (!empty($validatedData['keterangan'])) {
                $keteranganArray = [];
                foreach ($validatedData['keterangan'] as $ket) {
                    if (!empty($ket['keterangan'])) {
                        $keteranganArray[] = sprintf(
                            "%s - %s: %s %s %s",
                            $ket['dari_jam'] ?? '',
                            $ket['sd_jam'] ?? '',
                            $ket['keterangan'] ?? '',
                            $ket['col4'] ?? '',
                            $ket['dt_jam'] ?? ''
                        );
                    }
                }
                $keteranganText = implode("\n", $keteranganArray);
            }

            // Buat laporan header
            $laporan = LaporanHarian::create([
                'user_id' => $user->id,
                'lokasisp_id' => $validatedData['lokasi_id'],
                'pompa_id' => $validatedData['pompa_id'],
                'tanggal' => $validatedData['tanggal'],
                'injeksi_ke' => $validatedData['injector_well'],
                'facility' => 'injeksi',
                'total_cumulative' => $totalCumulative,
                'operator_siang_id' => $user->id,
                'operator_malam_id' => null,
                'keterangan' => $keteranganText, 
                'status' => 'draft', 
            ]);

            // Simpan detail per jam
            if (!empty($validatedData['flow'])) {
                foreach ($validatedData['flow'] as $jamKe => $flowData) {
                    // Skip jika semua field kosong
                    if (empty(array_filter($flowData))) continue;

                    LaporanDetilJam::create([
                        'laporan_id' => $laporan->id, 
                        'jam_ke' => $jamKe,
                        'total_bbls' => $flowData['total_bbls'] ?? null,
                        'rate_jam' => $flowData['rate_jam'] ?? null,
                        'cumm_bbls' => $flowData['cumm'] ?? null,
                        'rate_hari' => $flowData['rate_hari'] ?? null,
                        'inj_psi' => $flowData['injeksi_psi'] ?? null, 
                        'inj_rpm' => $flowData['rpm'] ?? null, 
                        'oil_cf' => $flowData['oil_cf'] ?? null,
                        'press_cf' => $flowData['press_cf'] ?? null,
                        'water_cf' => $flowData['water_cf'] ?? null,
                        'freq_hz' => $flowData['freq_hz'] ?? null,
                    ]);
                }
            }

            DB::commit();

            Alert::success('Success', 'Laporan Harian Berhasil Ditambahkan');
            return redirect()->route('user.dailyreport.index'); 

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Alert::error('Error', 'Validasi gagal');
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Throwable $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal menambahkan laporan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}