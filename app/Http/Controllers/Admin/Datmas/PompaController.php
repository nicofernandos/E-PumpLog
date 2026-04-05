<?php

namespace App\Http\Controllers\Admin\Datmas;

use App\Models\Pompa;
use App\Models\Lokasi; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class PompaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pompa = Pompa::with(['lokasi:id,kodesp,namasp']) 
            ->select('id', 'kodepompa', 'jenispompa', 'kapasitas', 'lokasi_id', 'status')
            ->where('is_deleted', 0)
            ->latest()
            ->paginate(10);
            
        $lokasi = Lokasi::select('id', 'kodesp', 'namasp')
            ->where('is_deleted', 0)
            ->orderBy('kodesp', 'asc')
            ->get();
            
        return view('admin.pages.datmas.pompa.index', compact('pompa', 'lokasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validateData = $request->validate([
                'kodepompa' => [
                    'required', Rule::unique('pompa','kodepompa')->where(function($q) {
                        return $q->where('is_deleted', 0);
                    })
                ],
                'jenispompa' => 'required|string|max:100',
                'kapasitas' => 'nullable|string|max:50', 
                'lokasi_id' => 'required|exists:lokasisp,id',
                'status' => 'required|in:aktif,nonaktif', 
            ], [
                'kodepompa.required' => 'Kode pompa wajib diisi.',
                'kodepompa.unique' => 'Kode pompa sudah digunakan.',
                'kodepompa.max' => 'Kode pompa maksimal 50 karakter.',
                'jenispompa.required' => 'Jenis pompa wajib diisi.',
                'jenispompa.max' => 'Jenis pompa maksimal 100 karakter.',
                'kapasitas.max' => 'Kapasitas maksimal 50 karakter.',
                'lokasi_id.required' => 'Lokasi stasiun pompa wajib dipilih.',
                'lokasi_id.exists' => 'Lokasi stasiun pompa tidak valid.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus aktif atau nonaktif.',
            ]);
            
            Pompa::create($validateData);
            
            DB::commit();
            
            $lokasi = Lokasi::find($validateData['lokasi_id']);
            $namaLokasi = $lokasi ? $lokasi->namasp : '-';
         
            Alert::success(
                'Berhasil!', 
                'Pompa <strong>' . htmlspecialchars($validateData['kodepompa'], ENT_QUOTES, 'UTF-8') . '</strong> jenis <strong>' .
                htmlspecialchars($validateData['jenispompa'], ENT_QUOTES, 'UTF-8') . '</strong> pada lokasi <strong>' .
                htmlspecialchars($namaLokasi, ENT_QUOTES, 'UTF-8') . '</strong> berhasil ditambahkan.'
            )
            ->html()
            ->showConfirmButton(false)
            ->autoClose(6000);

            
            return redirect()->route('admin.pompa.index');
            
        } catch(\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch(\Throwable $e) {
            DB::rollBack();
            \Log::error("Gagal Insert Pompa: " . $e->getMessage());
            Alert::error('Gagal!', 'Terjadi kesalahan saat menambahkan pompa: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pompa $pompa)
    {
        try {
            DB::beginTransaction();
            
            $validateData = $request->validate([
                'kodepompa' => 'required|string|max:50|unique:pompa,kodepompa,' . $pompa->id,
                'jenispompa' => 'required|string|max:100',
                'kapasitas' => 'nullable|string|max:50',
                'lokasi_id' => 'required|exists:lokasisp,id',
                'status' => 'required|in:aktif,nonaktif',
            ], [
                'kodepompa.required' => 'Kode pompa wajib diisi.',
                'kodepompa.unique' => 'Kode pompa sudah digunakan.',
                'kodepompa.max' => 'Kode pompa maksimal 50 karakter.',
                'jenispompa.required' => 'Jenis pompa wajib diisi.',
                'jenispompa.max' => 'Jenis pompa maksimal 100 karakter.',
                'kapasitas.max' => 'Kapasitas maksimal 50 karakter.',
                'lokasi_id.required' => 'Lokasi stasiun pompa wajib dipilih.',
                'lokasi_id.exists' => 'Lokasi stasiun pompa tidak valid.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus aktif atau nonaktif.',
            ]);
            
            $pompa->update($validateData);
            
            DB::commit();
            
            $lokasi = Lokasi::find($validateData['lokasi_id']);
            $namaLokasi = $lokasi ? $lokasi->namasp : '-';
            
            Alert::success('Berhasil!', 
                'Data pompa <strong>' . e($pompa->kodepompa) . '</strong> pada lokasi <strong>' . e($namaLokasi) . '</strong> berhasil diperbarui.'
            )->html();
            
            return redirect()->route('admin.pompa.index');
            
        } catch(\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            // Simpan data pompa yang sedang diedit untuk auto-show modal
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with([
                    'edited_pompa_id' => $pompa->id,
                    'edited_pompa' => $pompa // Kirim data lengkap
                ]);
            
        } catch(\Throwable $e) {
            DB::rollBack();
            \Log::error("Gagal Update Pompa: " . $e->getMessage());
            Alert::error('Gagal!', 'Terjadi kesalahan saat memperbarui pompa: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pompa $pompa)
    {
        try {
            DB::beginTransaction();
            
            $kodepompa = $pompa->kodepompa;

            $pompa->update([
                'is_deleted' => 1
            ]);
            
            DB::commit();
            
            Alert::success(
                'Berhasil!',
                'Data pompa ' . e($kodepompa) . 'berhasil dihapus.'
            )
            ->html();


            return redirect()->route('admin.pompa.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error("Gagal Delete Pompa: " . $e->getMessage());
            Alert::error('Gagal!', 'Terjadi kesalahan saat menghapus pompa: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}