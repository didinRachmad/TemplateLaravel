<?php

namespace App\Http\Controllers;

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
        return view('users.index', compact('users', 'menu'));
    }

    public function create()
    {
        // Ambil semua role (Anda bisa menyaring role yang diperbolehkan untuk assignment)
        $roles = Role::all();
        $produksiList = Produksi::all();
        return view('users.create', compact('roles', 'produksiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'produksi' => 'nullable|exists:master_produksi,id',
            'contact'  => 'nullable|regex:/^\+[1-9]\d{1,14}$/',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'        => $validated['name'],
                'email'       => $validated['email'],
                'produksi_id' => $validated['produksi'] ?? null,
                'contact'     => $validated['contact'] ?? null,
                'password'    => bcrypt($validated['password']),
            ]);

            DB::commit();

            // Assign role ke user baru
            $role = Role::find($validated['role_id']);
            $user->assignRole($role);

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
        return view('users.edit', compact('user', 'produksiList'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email',
            'produksi' => 'nullable|exists:master_produksi,id',
            'contact'  => 'nullable|regex:/^\+[1-9]\d{1,14}$/',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name'        => $validated['name'],
                'email'       => $validated['email'],
                'produksi_id' => $validated['produksi'] ?? null,
                'contact'     => $validated['contact'] ?? null,
            ]);

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

            Log::info("Password untuk user {$user->email} telah direset oleh " . auth()->user()->email);
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
