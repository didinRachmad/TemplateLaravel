<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    // Menampilkan daftar permissions
    public function index()
    {
        $menu = Menu::where('route', 'permissions')->first();
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions', 'menu'));
    }

    // Menampilkan form untuk membuat permission baru
    public function create()
    {
        return view('permissions.create');
    }

    // Menyimpan permission baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            Permission::create([
                'name' => $request->name,
            ]);
            DB::commit();
            session()->flash('success', 'Data permission berhasil dibuat.');
            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data.');
            return redirect()->back()->withInput();
        }
    }

    // Menampilkan form untuk mengedit permission
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    // Memperbarui permission
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $request->name,
            ]);
            DB::commit();
            session()->flash('success', 'Data permission berhasil diperbarui.');
            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data.');
            return redirect()->back()->withInput();
        }
    }

    // Menghapus permission
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $permission->delete();
            DB::commit();
            session()->flash('success', 'Data permission berhasil dihapus.');
            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menghapus data.');
            return redirect()->back();
        }
    }
}
