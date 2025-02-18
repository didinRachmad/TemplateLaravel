<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRoute;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil data menu berdasarkan route yang sesuai (misal 'Menu')
        $menu_id = Menu::where('route', 'menus')->first();

        // Pengecekan hak akses untuk melihat daftar menu
        if (!auth()->user()->hasMenuPermission($menu_id->id, 'index')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat daftar menus.');
        }

        $menus = Menu::all();
        return view('menus.index', compact('menus', 'menu_id'));
    }

    public function show($id) {}

    public function approve($id, Request $request)
    {
        // Ambil konfigurasi approval untuk module 'Menu'
        $approvalRoutes = ApprovalRoute::where('module', 'Menu')
            ->orderBy('sequence')
            ->get();

        // Tentukan role dan approval step user saat ini
        $currentRole = auth()->user()->getRoleNames()->first();
        $currentApproval = $approvalRoutes->firstWhere('role_id', function ($roleId) use ($currentRole) {
            return $roleId == \App\Models\Role::where('name', $currentRole)->first()->id;
        });

        $expectedSequence = $currentApproval ? $currentApproval->sequence : null;

        if (is_null($expectedSequence)) {
            abort(403, 'Anda tidak memiliki hak untuk approve menus.');
        }

        $menu = Menu::findOrFail($id);

        // Cek apakah menu dalam status pending untuk user ini
        if ($menu->approval_level !== $expectedSequence - 1) {
            abort(403, 'Menu tidak dalam status pending untuk approval Anda.');
        }

        // Update approval_level sesuai dengan sequence approval user saat ini
        $menu->approval_level = $expectedSequence;
        $menu->save();

        session()->flash('success', 'Menu berhasil di-approve.');
        return redirect()->route('menus.approval');
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'required|string|max:255',
            'icon' => 'string|nullable|max:255',
            'order' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            Menu::create([
                'title' => $validatedData['title'],
                'route' => $validatedData['route'],
                'icon' => $validatedData['icon'],
                'order' => $validatedData['order']
            ]);

            DB::commit();
            session()->flash('success', 'Data berhasil ditambahkan.');
            return redirect()->route('menus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambahkan menu: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menambahkan data. Silakan coba lagi.');
            return redirect()->route('menus.index');
        }
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'required|string|max:255',
            'icon' => 'string|nullable|max:255',
            'order' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);
            $menu->title = $validatedData['title'];
            $menu->route = $validatedData['route'];
            $menu->icon = $validatedData['icon'];
            $menu->order = $validatedData['order'];

            $menu->save();
            DB::commit();
            session()->flash('success', 'Data menu berhasil diperbarui.');
            return redirect()->route('menus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui menu: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
            return redirect()->route('menus.index');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            DB::commit();
            session()->flash('success', 'Data menu berhasil dihapus.');
            return redirect()->route('menus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus menu: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            return redirect()->route('menus.index');
        }
    }
}
