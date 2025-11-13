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
        $user = User::select('id', 'name', 'email', 'role', 'is_active')
             ->paginate(10);
        return view('admin.pages.users.index', compact('user'));
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
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:supervisor,manager,operator',
                'is_active' => 'nullable|boolean',
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

    public function update(Request $request, User $user)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'name' => 'required|string|max:255',
                'role' => 'required|string|in:supervisor,manager,operator',
                'is_active' => 'nullable|boolean',
            ];

            if ($request->email !== $user->email) {
                $rules['email'] = 'required|string|email|max:255|unique:users,email';
            }

            if ($request->password){
                $rules['password'] = 'nullable|string|min:8|confirmed';
            }

            $validateData = $request->validate($rules);

            $dataToUpdate = [
                'name' => $validateData['name'],
                'email' => $validateData['email'] ?? $user->email,
                'role' => $validateData['role'],
                'is_active' => $validateData['is_active'],
            ];

            if ($request->password) {
                $dataToUpdate['password'] = bcrypt($validateData['password']);
            }

            $user->update($dataToUpdate);

            DB::commit();
            Alert::success('Success', 'Berhasil Memperbarui Akun: ' . $user->name);
            return redirect()->route('admin.users');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal memperbarui akun: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $userName = $user->name;
            $user->delete();

            Alert::success('Success', 'Berhasil Menghapus Akun: ' . $userName);
            return redirect()->route('admin.users');

        } catch (\Throwable $e) {
            Alert::error('Error', 'Gagal menghapus akun: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    

}