<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProduksiController extends Controller
{
    // Menampilkan daftar produksi
    public function index()
    {
        $menu = Menu::where('route', 'master_produksi')->first();
        $produksis = Produksi::all();
        return view('master.produksi.index', compact('produksis', 'menu'));
    }

    // Menampilkan form untuk membuat produksi baru
    public function create()
    {
        return view('master.produksi.create');
    }

    // Menyimpan produksi baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_produksi' => 'required|unique:master_produksi,nama_produksi',
        ]);

        DB::beginTransaction();
        try {
            Produksi::create([
                'nama_produksi' => $request->nama_produksi,
            ]);
            DB::commit();
            session()->flash('success', 'Data produksi berhasil dibuat.');
            return redirect()->route('master_produksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambah produksi: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data.');
            return redirect()->back()->withInput();
        }
    }

    // Menampilkan form untuk mengedit produksi
    public function edit(Produksi $produksi)
    {
        return view('master.produksi.edit', compact('produksi'));
    }

    // Memperbarui produksi
    public function update(Request $request, Produksi $produksi)
    {
        $request->validate([
            'nama_produksi' => 'required|unique:master_produksi,nama_produksi,' . $produksi->id,
        ]);

        DB::beginTransaction();
        try {
            $produksi->update([
                'nama_produksi' => $request->nama_produksi,
            ]);
            DB::commit();
            session()->flash('success', 'Data produksi berhasil diperbarui.');
            return redirect()->route('master_produksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat update produksi: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data.');
            return redirect()->back()->withInput();
        }
    }

    // Menghapus produksi
    public function destroy(Produksi $produksi)
    {
        DB::beginTransaction();
        try {
            $produksi->delete();
            DB::commit();
            session()->flash('success', 'Data produksi berhasil dihapus.');
            return redirect()->route('master_produksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus produksi: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus data.');
            return redirect()->back();
        }
    }
}
