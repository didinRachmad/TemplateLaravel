<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    // Menampilkan daftar roles
    public function index()
    {
        $menu = Menu::where('route', 'roles')->first();
        $roles = Role::all();
        return view('roles.index', compact('roles', 'menu'));
    }

    // Menampilkan form untuk membuat role baru
    public function create()
    {
        return view('roles.create');
    }

    // Menyimpan role baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        DB::beginTransaction();

        try {
            Role::create([
                'name' => $validatedData['name'],
            ]);

            DB::commit();
            session()->flash('success', 'Role berhasil dibuat.');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat membuat role: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat membuat role. Silakan coba lagi.');
            return redirect()->route('roles.index');
        }
    }

    // Menampilkan form untuk mengedit role
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    // Memperbarui role
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        DB::beginTransaction();

        try {
            $role->update([
                'name' => $validatedData['name'],
            ]);

            DB::commit();
            session()->flash('success', 'Role berhasil diperbarui.');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui role: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui role. Silakan coba lagi.');
            return redirect()->route('roles.index');
        }
    }

    // Menghapus role
    public function destroy(Role $role)
    {
        DB::beginTransaction();

        try {
            $role->delete();

            DB::commit();
            session()->flash('success', 'Role berhasil dihapus.');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus role: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus role. Silakan coba lagi.');
            return redirect()->route('roles.index');
        }
    }

    // Menampilkan halaman pengaturan permissions untuk role tertentu
    public function menuPermissions(Role $role)
    {
        // Ambil semua menu
        $menus = Menu::orderBy('order')->get();

        // Ambil semua permission yang ada untuk role ini, kunci: menu_id
        $roleMenuPermissions = $role->menus->mapToGroups(function ($menu) {
            // Ambil permission_id untuk menu ini, berdasarkan relasi pivot
            return [$menu->id => $menu->pivot->permission_id]; // Mengambil ID permission dari pivot
        })->toArray();

        // Ambil semua permission yang ada di sistem
        $permissions = Permission::all();

        // Kirim data ke view
        return view('roles.menu-permissions', compact('role', 'menus', 'roleMenuPermissions', 'permissions'));
    }

    // Menyimpan konfigurasi menu-permission untuk role
    public function assignMenuPermissions(Request $request, Role $role)
    {
        // Validasi input
        $request->validate([
            'menu_permissions' => 'array',
            'menu_permissions.*' => 'array',
            'menu_permissions.*.*' => 'exists:permissions,id', // Validasi ID permission
        ]);

        // Hapus semua relasi lama dari role ini terlebih dahulu
        $role->menus()->detach();

        // Looping untuk setiap menu dan permission yang dipilih
        $dataToInsert = []; // Simpan data untuk batch insert

        foreach ($request->menu_permissions as $menuId => $permissionIds) {
            foreach ($permissionIds as $permissionId) {
                // Menyusun data untuk batch insert
                $dataToInsert[] = [
                    'role_id' => $role->id,
                    'menu_id' => $menuId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Masukkan semua data dalam satu query agar lebih efisien
        DB::table('menu_role_permission')->insert($dataToInsert);

        // Redirect ke halaman pengaturan dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Permissions menu berhasil diperbarui.');
    }
}
