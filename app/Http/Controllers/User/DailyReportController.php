<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DailyReportController extends Controller
{
    // Halaman utama: List semua draft
    public function index()
    {
        $drafts = LaporanHarian::with(['pompa.lokasi', 'detilJam'])
            ->where('status', 'draft')
            ->where('user_id', Auth::id())
            ->withCount('detilJam as hourly_entries_count')
            ->latest()
            ->paginate(10);

        $totalDraft = LaporanHarian::where('status', 'draft')
            ->where('user_id', Auth::id())
            ->count();

        $draftToday = LaporanHarian::where('status', 'draft')
            ->where('user_id', Auth::id())
            ->whereDate('tanggal', today())
            ->count();

        $totalHourlyEntries = $drafts->sum('hourly_entries_count');

        $lokasi = Lokasi::all();

        $data = [
            'title' => 'E-PumpLog | Draft Harian Injeksi Pompa',
            'drafts' => $drafts,
            'totalDraft' => $totalDraft,
            'draftToday' => $draftToday,
            'totalHourlyEntries' => $totalHourlyEntries,
            'lokasi' => $lokasi
        ];

        return view('user.pages.dailyreport.index', $data);
    }

    // Edit draft - redirect ke form report
    public function edit($id)
    {
        $laporan = LaporanHarian::with('detilJam', 'pompa.lokasi')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Convert detail jam ke format yang bisa digunakan di form
        $flowData = [];
        foreach ($laporan->detilJam as $detail) {
            $flowData[$detail->jam_ke] = [
                'total_bbls' => $detail->total_bbls,
                'rate_jam' => $detail->rate_jam,
                'cumm' => $detail->cumm_bbls,
                'rate_hari' => $detail->rate_hari,
                'injeksi_psi' => $detail->inj_psi,
                'rpm' => $detail->inj_rpm,
                'oil_cf' => $detail->oil_cf,
                'press_cf' => $detail->press_cf,
                'water_cf' => $detail->water_cf,
                'freq_hz' => $detail->freq_hz,
            ];
        }

        $data = [
            'title' => 'Edit Draft Laporan',
            'laporan' => $laporan,
            'flowData' => $flowData,
            'isEdit' => true
        ];

        return view('user.pages.report.edit', $data);
    }

    // Update draft dari form edit
    public function update(Request $request, $id)
    {
        $laporan = LaporanHarian::where('user_id', Auth::id())->findOrFail($id);

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

            // Update header
            $laporan->update([
                'lokasisp_id' => $validatedData['lokasi_id'],
                'pompa_id' => $validatedData['pompa_id'],
                'tanggal' => $validatedData['tanggal'],
                'injeksi_ke' => $validatedData['injector_well'],
                'total_cumulative' => $totalCumulative,
                'keterangan' => $request->input('keterangan', ''),
            ]);

            // Update/Insert detail jam
            if (!empty($validatedData['flow'])) {
                foreach ($validatedData['flow'] as $jamKe => $flowData) {
                    if (empty(array_filter($flowData))) continue;

                    $laporan->detilJam()->updateOrCreate(
                        ['jam_ke' => $jamKe],
                        [
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
                        ]
                    );
                }
            }

            // Cek action: submit atau draft
            if ($request->input('action') === 'submit') {
                $laporan->update(['status' => 'finalized']);
                DB::commit();
                Alert::success('Success', 'Laporan berhasil disubmit!');
                return redirect()->route('user.report.final');
            }

            DB::commit();
            Alert::success('Success', 'Draft berhasil diupdate!');
            return redirect()->route('user.dailyreport.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal update: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // Submit draft menjadi final
    public function submitDraft($id)
    {
        $laporan = LaporanHarian::where('user_id', Auth::id())->findOrFail($id);

        $laporan->update(['status' => 'finalized']);

        Alert::success('Success', 'Laporan berhasil disubmit!');
        return redirect()->route('user.report.final');
    }

    // Hapus draft
    public function destroy($id)
    {
        $laporan = LaporanHarian::where('user_id', Auth::id())->findOrFail($id);
        $laporan->delete();

        Alert::success('Success', 'Draft berhasil dihapus!');
        return redirect()->route('user.dailyreport.index');
    }
}