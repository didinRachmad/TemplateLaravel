<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dibuat.');
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

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    // Menghapus permission
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus.');
    }
}
