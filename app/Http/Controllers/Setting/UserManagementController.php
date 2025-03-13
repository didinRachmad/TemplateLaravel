<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Models\Produksi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index()
    {
        $menu = Menu::where('route', 'users')->first();
        $users = User::all();
        return view('setting.users.index', compact('users', 'menu'));
    }

    public function create()
    {
        // Ambil semua role (Anda bisa menyaring role yang diperbolehkan untuk assignment)
        $roles = Role::all();
        $produksiList = Produksi::all();
        return view('setting.users.create', compact('roles', 'produksiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users',
            // Ubah validasi agar 'produksi' adalah array (jika kosong, tidak masalah)
            'produksi'     => 'nullable|array',
            'produksi.*'   => 'exists:master_produksi,id',
            'contact'      => 'nullable|regex:/^\+[1-9]\d{1,14}$/',
            'password'     => 'required|string|min:8|confirmed',
            'role_id'      => 'required|exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                // Kolom produksi_id tidak lagi digunakan karena relasi dikelola lewat pivot table
                'contact'  => $validated['contact'] ?? null,
                'password' => bcrypt($validated['password']),
            ]);

            // Jika input produksi ada, update pivot table dengan metode sync
            if (isset($validated['produksi']) && is_array($validated['produksi'])) {
                $user->produksis()->sync($validated['produksi']);
            }

            // Assign role ke user baru
            $role = Role::find($validated['role_id']);
            $user->assignRole($role);

            DB::commit();

            session()->flash('success', 'User baru berhasil ditambahkan.');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat membuat users baru: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat membuat user baru. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(User $user)
    {
        // Tampilkan form edit user.
        $produksiList = Produksi::all();
        return view('setting.users.edit', compact('user', 'produksiList'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'email'        => 'required|email',
            // Ubah validasi agar 'produksi' adalah array dan setiap elemen harus ada di master_produksi
            'produksi'     => 'nullable|array',
            'produksi.*'   => 'exists:master_produksi,id',
            'contact'      => 'nullable|regex:/^\+[1-9]\d{1,14}$/',
        ]);

        DB::beginTransaction();
        try {
            // Update data user selain relasi produksi
            $user->update([
                'name'    => $validated['name'],
                'email'   => $validated['email'],
                'contact' => $validated['contact'] ?? null,
            ]);

            // Update relasi many-to-many melalui pivot table
            if (isset($validated['produksi'])) {
                $user->produksis()->sync($validated['produksi']);
            } else {
                // Jika tidak ada produksi yang dipilih, hapus semua relasi
                $user->produksis()->detach();
            }

            DB::commit();
            session()->flash('success', 'Data users berhasil diperbarui.');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui users: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function resetPassword(User $user)
    {
        // Untuk keamanan, Anda dapat menambahkan konfirmasi lebih lanjut (misalnya, modal konfirmasi di view)
        $defaultPassword = '12345678';

        DB::beginTransaction();
        try {
            // Update password menggunakan Hash (bcrypt)
            $user->update([
                'password' => Hash::make($defaultPassword),
            ]);

            Log::info("Password untuk user {$user->email} telah direset oleh " . Auth::user()->email);
            DB::commit();
            session()->flash('success', 'Password user telah direset ke default.');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat reset password users: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat reset password. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(User $user)
    {
        // Cegah super_admin menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            session()->flash('success', 'Data user berhasil dinonaktifkan.');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus user: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }
}
