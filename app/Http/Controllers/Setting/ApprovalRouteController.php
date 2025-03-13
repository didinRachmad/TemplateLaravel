<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use App\Models\ApprovalRoute;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu = Menu::where('route', 'approval_routes')->first();
        $routes = ApprovalRoute::orderBy('module')->orderBy('sequence')->get();
        return view('setting.approval_routes.index', compact('routes', 'menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::whereNotNull('route')->orderBy('order')->get();
        $roles = Role::all();
        $users = auth()->user()->all();
        return view('setting.approval_routes.create', compact('roles', 'users', 'menus'));
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

        try {
            DB::beginTransaction();

            ApprovalRoute::create($validated);

            DB::commit();
            session()->flash('success', 'Konfigurasi approval berhasil ditambahkan.');
            return redirect()->route('approval_routes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
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
        return view('setting.approval_routes.edit', compact('route', 'roles', 'users', 'menus'));
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

        try {
            DB::beginTransaction();

            $route->update($validated);

            DB::commit();
            session()->flash('success', 'Konfigurasi approval berhasil diperbarui.');
            return redirect()->route('approval_routes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $route = ApprovalRoute::findOrFail($id);

        try {
            DB::beginTransaction();

            $route->delete();

            DB::commit();
            session()->flash('success', 'Konfigurasi approval berhasil dihapus.');
            return redirect()->route('approval_routes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
