<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRoute;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        // Ambil data menu berdasarkan route 'items'
        $menu = Menu::where('route', 'items')->first();

        // Ambil nilai produksi dari user yang sedang login
        $allowedProduksi = Auth::user()->produksi;
        $items = $allowedProduksi ? Item::byProduksi($allowedProduksi)->get() : Item::all();

        // Ambil role user saat ini (asumsi user hanya punya satu role)
        $currentRoleName = auth()->user()->getRoleNames()->first();
        $role = Role::where('name', $currentRoleName)->first();
        // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
        $approvalRoute = ApprovalRoute::where('module', 'items')
            ->where('role_id', $role->id)
            ->first();

        // Kirim data ke view
        return view('items.index', compact('menu', 'items', 'approvalRoute'));
    }


    public function show($id)
    {
        // Ambil role user saat ini (asumsi user hanya punya satu role)
        $currentRoleName = auth()->user()->getRoleNames()->first();
        $role = Role::where('name', $currentRoleName)->first();
        // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
        $approvalRoute = ApprovalRoute::where('module', 'items')
            ->where('role_id', $role->id)
            ->first();

        $item = Item::findOrFail($id);
        return view('items.show', compact('item', 'approvalRoute'));
    }

    public function approve($id, Request $request)
    {
        // Ambil data menu 'items'
        $menu = Menu::where('route', 'items')->first();

        // Ambil role user saat ini
        $currentRoleName = auth()->user()->getRoleNames()->first();
        $role = \Spatie\Permission\Models\Role::where('name', $currentRoleName)->first();

        // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
        $approvalRoute = ApprovalRoute::where('module', 'items')
            ->where('role_id', $role->id)
            ->first();

        if (!$approvalRoute) {
            abort(403, 'Anda tidak memiliki hak untuk melakukan approval.');
        }

        // Ambil item yang akan di-approve
        $item = Item::findOrFail($id);

        // Pastikan item dalam status pending untuk approval user ini:
        if ($item->approval_level !== ($approvalRoute->sequence - 1)) {
            abort(403, 'Item tidak dalam status pending untuk approval Anda.');
        }

        // Update approval_level menjadi nilai konfigurasi (misal, jika sequence = 1, maka item approval_level menjadi 1)
        $item->approval_level = $approvalRoute->sequence;

        // Update status: jika ada konfigurasi approval berikutnya, tampilkan keterangan pending untuk role berikutnya
        $nextApprovalRoute = ApprovalRoute::where('module', 'items')
            ->where('sequence', '>', $approvalRoute->sequence)
            ->orderBy('sequence', 'asc')
            ->first();

        if ($nextApprovalRoute) {
            $item->status = "Approved by {$role->name}. Pending approval for {$nextApprovalRoute->role->name}";
        } else {
            $item->status = "Final approved";
        }

        $item->save();

        session()->flash('success', 'Item berhasil di-approve.');
        return redirect()->route('items.index');
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'produksi' => 'required|string|max:255',
            'kode_item' => 'required|string|max:255',
            'nama_item' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
            'kode_lokasi' => 'required|string|max:255',
            'nama_lokasi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $gambarPath = null;
        DB::beginTransaction();

        try {
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('uploads', 'public');
            }

            Item::create([
                'produksi' => $validatedData['produksi'],
                'kode_item' => $validatedData['kode_item'],
                'nama_item' => $validatedData['nama_item'],
                'jenis' => $validatedData['jenis'],
                'kondisi' => $validatedData['kondisi'],
                'kode_lokasi' => $validatedData['kode_lokasi'],
                'nama_lokasi' => $validatedData['nama_lokasi'],
                'jumlah' => $validatedData['jumlah'],
                'gambar' => $gambarPath,
            ]);

            DB::commit();
            session()->flash('success', 'Data berhasil ditambahkan.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambahkan item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menambahkan data. Silakan coba lagi.');
            // session()->flash('error', $e->getMessage());
            return redirect()->route('items.index');
        }
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'produksi' => 'required|string|max:255',
            'kode_item' => 'required|string|max:255',
            'nama_item' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
            'kode_lokasi' => 'required|string|max:255',
            'nama_lokasi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            $item->produksi = $validatedData['produksi'];
            $item->kode_item = $validatedData['kode_item'];
            $item->nama_item = $validatedData['nama_item'];
            $item->jenis = $validatedData['jenis'];
            $item->kondisi = $validatedData['kondisi'];
            $item->kode_lokasi = $validatedData['kode_lokasi'];
            $item->nama_lokasi = $validatedData['nama_lokasi'];
            $item->jumlah = $validatedData['jumlah'];

            if ($request->hasFile('gambar')) {
                if ($item->gambar && Storage::exists('public/' . $item->gambar)) {
                    Storage::delete('public/' . $item->gambar);
                }
                $gambarPath = $request->file('gambar')->store('uploads', 'public');
                $item->gambar = $gambarPath;
            }

            $item->save();
            DB::commit();
            session()->flash('success', 'Data item berhasil diperbarui.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
            return redirect()->route('items.index');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            if ($item->gambar && Storage::exists('public/' . $item->gambar)) {
                Storage::delete('public/' . $item->gambar);
            }
            $item->delete();
            DB::commit();
            session()->flash('success', 'Data item berhasil dihapus.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            return redirect()->route('items.index');
        }
    }
}
