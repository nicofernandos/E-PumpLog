<?php

namespace App\Http\Controllers\Admin\Datmas;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::select('id', 'kodesp', 'namasp', 'keterangan')
            ->where('is_deleted', 0)
            ->paginate(10);

        return view('admin.pages.datmas.lokasi.index', compact('lokasi'));
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $validateData = $request->validate([
                'kodesp' => 'required|string|max:50|unique:lokasisp,kodesp',
                'namasp' => 'required|string|max:100',
                'keterangan' => 'nullable|string',
            ]); 
            Lokasi::create($validateData);

            DB::commit();
            Alert::success('Success', 'Berhasil Menambahkan Lokasi SP: ' . $validateData['namasp']);
            return redirect()->route('admin.lokasi.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); 
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal menambahkan lokasi SP: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'namasp' => 'required|string|max:100',
                'keterangan' => 'nullable|string',
            ];
            if ($request->kodesp !== $lokasi->kodesp) {
                $rules['kodesp'] = 'required|string|max:10|unique:lokasisp,kodesp';
            } else {
                $rules['kodesp'] = 'required|string|max:10';
            }

            $validateData = $request->validate($rules);

            $lokasi->update($validateData);
            DB::commit();
            Alert::success('Success', 'Berhasil Memperbarui Lokasi SP: ' . $lokasi->namasp);
            return redirect()->route('admin.lokasi');

        } catch(\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch(\Throwable $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal memperbarui lokasi SP: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Lokasi $lokasi)
    {
        try {
            DB::beginTransaction();
            if ($lokasi->pompas()->where('is_deleted', 0)->exists()) {
                Alert::warning(
                    'Gagal!',
                    'Lokasi SP tidak dapat dihapus karena masih memiliki pompa aktif.'
                );
                return redirect()->back();
            }

            // Soft delete
            $lokasi->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            Alert::success(
                'Success',
                'Berhasil menghapus Lokasi SP: ' . $lokasi->namasp
            );

            return redirect()->route('admin.lokasi.index');

        } catch (\Throwable $e) {
            DB::rollBack();

            Alert::error(
                'Error',
                'Gagal menghapus lokasi SP: ' . $e->getMessage()
            );

            return redirect()->back();
        }
    }
}