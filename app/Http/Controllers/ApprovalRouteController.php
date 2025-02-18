<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRoute;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu = Menu::where('route', 'approval_routes')->first();

        $routes = ApprovalRoute::orderBy('module')->orderBy('sequence')->get();
        return view('approval_routes.index', compact('routes', 'menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar role dan user untuk select box (pastikan model Role dan User sudah ada)
        $menus = Menu::orderBy('order')->get();
        $roles = Role::all();
        $users = auth()->user()->all();
        return view('approval_routes.create', compact('roles', 'users', 'menus'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'module' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'sequence' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        ApprovalRoute::create($validated);

        session()->flash('success', 'Konfigurasi approval berhasil ditambahkan.');
        return redirect()->route('approval_routes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $route = ApprovalRoute::findOrFail($id);
        $menus = Menu::orderBy('order')->get();
        $roles = Role::all();
        $users = auth()->user()->all();
        return view('approval_routes.edit', compact('route', 'roles', 'users', 'menus'));
    }

    public function update(Request $request, $id)
    {
        $route = ApprovalRoute::findOrFail($id);

        $validated = $request->validate([
            'module' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'sequence' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $route->update($validated);

        session()->flash('success', 'Konfigurasi approval berhasil diperbarui.');
        return redirect()->route('approval_routes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $route = ApprovalRoute::findOrFail($id);
        $route->delete();

        session()->flash('success', 'Konfigurasi approval berhasil dihapus.');
        return redirect()->route('approval_routes.index');
    }
}
