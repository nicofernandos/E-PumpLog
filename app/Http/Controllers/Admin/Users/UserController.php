<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pages.users.index');
    }

    public function create()
    {
        return view('admin.pages.users.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:supervisor,manager,operator',
                'is_active' => 'required|boolean',
            ]);

            $user = User::create([
                'name' => $validate['name'],
                'email' => $validate['email'],
                'password' => bcrypt($validate['password']),
                'role' => $validate['role'],
                'is_active' => $validate['is_active'],
            ]);

            DB::commit();

            Alert::success('Success', 'Berhasil Membuat Akun: ' . $user->name);
            return redirect()->route('admin.users');

        } catch (\Throwable $e) {
            DB::rollBack();

            Alert::error('Error', 'Gagal membuat akun: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }


    }
}