<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

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
        if (!$menu_id || !Auth::user()->hasMenuPermission(
            $menu_id->id,
            'index'
        )) {
            abort(403, 'Anda tidak memiliki akses untuk melihat daftar menus.');
        }

        $menus = Menu::all();
        return view('setting.menus.index', compact('menus', 'menu_id'));
    }

    public function show($id) {}

    // Di controller (method create)
    public function create()
    {
        // Misalnya hanya ambil menu utama (tanpa parent)
        $parentMenus = Menu::orderBy('order')->whereNull('route')->get();
        return view('setting.menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        // Validasi input, tambahkan validasi untuk parent_id (nullable dan harus ada di tabel menus jika diisi)
        $validatedData = $request->validate([
            'title'     => 'required|string|max:255',
            'route'     => 'required|string|max:255',
            'icon'      => 'nullable|string|max:255',
            'order'     => 'required|integer',
            'parent_id' => 'nullable|exists:menus,id',
        ]);

        DB::beginTransaction();

        try {
            Menu::create([
                'title'     => $validatedData['title'],
                'route'     => $validatedData['route'],
                'icon'      => $validatedData['icon'] ?? null,
                'order'     => $validatedData['order'],
                'parent_id' => $validatedData['parent_id'] ?? null,
            ]);

            DB::commit();
            session()->flash('success', 'Data berhasil ditambahkan.');
            return redirect()->route('menus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambahkan menu: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menambahkan data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Menu $menu)
    {
        return view('setting.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'required|string|max:255',
            'icon' => 'string|nullable|max:255',
            'order' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $menu->update([
                'title' => $validatedData['title'],
                'route' => $validatedData['route'],
                'icon' => $validatedData['icon'],
                'order' => $validatedData['order'],
            ]);

            DB::commit();
            session()->flash('success', 'Data menu berhasil diperbarui.');
            return redirect()->route('menus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui menu: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Menu $menu)
    {
        DB::beginTransaction();
        try {
            $menu->delete();
            DB::commit();
            session()->flash('success', 'Data menu berhasil dihapus.');
            return redirect()->route('menus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus menu: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }
}
