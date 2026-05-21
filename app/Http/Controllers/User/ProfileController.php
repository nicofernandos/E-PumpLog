<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanHarian;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil statistik laporan user (sesuaikan dengan logic app kamu)
        $totalLaporan = LaporanHarian::where('user_id', $user->id)->count();
        $laporanDisetujui = LaporanHarian::where('user_id', $user->id)->where('status', 'disetujui')->count();
        $laporanDraft = LaporanHarian::where('user_id', $user->id)->where('status', 'draft')->count();

        return view('user.pages.profile.index', compact(
            'user', 
            'totalLaporan', 
            'laporanDisetujui', 
            'laporanDraft'  
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|numeric|digits_between:10,15',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->input('remove_picture') == '1' && $user->picture) {
        Storage::disk('public')->delete($user->picture);
        $user->picture = null;
        }

        // Upload foto baru
        if ($request->hasFile('picture')) {
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }
            $user->picture = $request->file('picture')->store('profile_pictures', 'public');
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Password berhasil diubah!');
    }
}