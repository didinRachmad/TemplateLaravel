<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\ApprovalRoute;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Produksi;
use App\Models\Role;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    // public function index()
    // {
    //     // Ambil data menu berdasarkan route 'items'
    //     $menu = Menu::where('route', 'items')->first();

    //     $allowedProduksiIds = Auth::user()->produksis->pluck('id');

    //     if ($allowedProduksiIds->isEmpty()) {
    //         // Jika user tidak memiliki produksi, tampilkan semua item
    //         $items = Item::all();
    //     } else {
    //         // Jika user memiliki produksi, tampilkan item sesuai dengan produksi yang dimiliki
    //         $items = Item::whereIn('produksi_id', $allowedProduksiIds)->get();
    //     }

    //     // Ambil role user saat ini
    //     $currentRoleName = Auth::user()->getRoleNames()->first();
    //     $role = Role::where('name', $currentRoleName)->first();

    //     // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
    //     $approvalRoute = ApprovalRoute::where('module', 'items')
    //         ->where('role_id', $role->id)
    //         ->first();

    //     // Filter data berdasarkan approval_level dan status
    //     if ($approvalRoute) {
    //         $items = $items->filter(function ($item) use ($approvalRoute) {
    //             // Jika bukan tahap pertama, sembunyikan approval_level 0
    //             if ($approvalRoute->sequence != 1 && $item->approval_level == 0) {
    //                 return false;
    //             }

    //             // Jika item sudah "Rejected", jangan ditampilkan untuk user di approval level selanjutnya
    //             if ($item->status === 'Rejected' && $approvalRoute->sequence > ($item->approval_level + 1)) {
    //                 // dd($approvalRoute->sequence . ' > ' . $item->approval_level + 1);
    //                 return false;
    //             }

    //             return true;
    //         });
    //     }

    //     // Kirim data ke view
    //     return view('transaksi.items.index', compact('menu', 'items', 'approvalRoute'));
    // }

    public function index()
    {
        // Ambil data menu berdasarkan route 'items'
        $menu = Menu::where('route', 'items')->first();

        $allowedProduksiIds = Auth::user()->produksis->pluck('id');

        if ($allowedProduksiIds->isEmpty()) {
            // Jika user tidak memiliki produksi, tampilkan semua item
            $items = Item::all();
        } else {
            // Jika user memiliki produksi, tampilkan item sesuai dengan produksi yang dimiliki
            $items = Item::whereIn('produksi_id', $allowedProduksiIds)->get();
        }

        // Ambil role user saat ini
        $currentRoleName = Auth::user()->getRoleNames()->first();
        $role = Role::where('name', $currentRoleName)->first();

        // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
        $approvalRoute = ApprovalRoute::where('module', 'items')->where('role_id', $role->id)->first();

        // Jika approvalRoute bukan sequence pertama (admin), lakukan filter data
        if ($approvalRoute && $approvalRoute->sequence != 1) {
            $items = $items->filter(function ($item) use ($approvalRoute) {
                // Untuk approval route selain sequence 1,
                // item harus memiliki approval_level sama dengan (sequence - 1)
                if ($item->approval_level !== $approvalRoute->sequence - 1) {
                    return false;
                }
                // Jika item sudah "Rejected", jangan tampilkan
                if ($item->status === 'Rejected') {
                    return false;
                }
                return true;
            });
        }

        // Kirim data ke view
        return view('transaksi.items.index', compact('menu', 'items', 'approvalRoute'));
    }

    public function show(Item $item)
    {
        // Ambil role user saat ini (asumsi user hanya punya satu role)
        $currentRoleName = Auth::user()->getRoleNames()->first();
        $role = Role::where('name', $currentRoleName)->first();
        // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
        $approvalRoute = ApprovalRoute::where('module', 'items')->where('role_id', $role->id)->first();

        return view('transaksi.items.show', compact('item', 'approvalRoute'));
    }

    public function create()
    {
        $produksiList = Produksi::all();
        return view('transaksi.items.create', compact('produksiList'));
    }

    public function store(Request $request)
    {
        // Validasi input, termasuk cek duplikasi produksi + kode_item
        $validatedData = $request->validate([
            'produksi' => 'required|exists:master_produksi,id',
            'kode_item' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items')->where(function ($query) use ($request) {
                    return $query->where('produksi_id', $request->produksi);
                }),
            ],
            'nama_item' => 'required|string|max:255',
            'satuan' => 'required|string|max:10',
            'jenis' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
            // 'kode_lokasi' => 'nullable|string|max:255',
            'nama_lokasi' => 'nullable|string|max:255',
            'detail_lokasi' => 'nullable|string|max:255',
            'jumlah' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'gambar' => 'nullable',
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:8192',
        ]);

        DB::beginTransaction();

        try {
            $gambarPaths = [];
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $path = $file->store('uploads', 'public');
                    $gambarPaths[] = $path;
                }
            }

            // Cek manual apakah data sudah ada
            $existingItem = Item::where('produksi_id', $validatedData['produksi'])->where('kode_item', $validatedData['kode_item'])->exists();

            if ($existingItem) {
                return redirect()->back()->withInput()->with('error', 'Data dengan produksi dan kode item yang sama sudah ada.');
            }

            // Simpan data jika tidak ada duplikasi
            Item::create([
                'produksi_id' => $validatedData['produksi'],
                'kode_item' => $validatedData['kode_item'],
                'nama_item' => $validatedData['nama_item'],
                'satuan' => $validatedData['satuan'],
                'jenis' => $validatedData['jenis'],
                'kondisi' => $validatedData['kondisi'],
                // 'kode_lokasi' => $validatedData['kode_lokasi'],
                'nama_lokasi' => $validatedData['nama_lokasi'],
                'detail_lokasi' => $validatedData['detail_lokasi'],
                'jumlah' => $validatedData['jumlah'],
                'gambar' => $gambarPaths,
            ]);

            DB::commit();

            session()->flash('success', 'Data berhasil ditambahkan.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambahkan item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menambahkan data. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Item $item)
    {
        $produksiList = Produksi::all();
        return view('transaksi.items.edit', compact('item', 'produksiList'));
    }

    public function update(Request $request, Item $item)
    {
        // Aturan dasar validasi untuk field-field lain
        $rules = [
            'produksi' => 'nullable|exists:master_produksi,id',
            'kode_item' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items')
                    ->where(function ($query) use ($request) {
                        return $query->where('produksi_id', $request->produksi);
                    })
                    ->ignore($item->id),
            ],
            'nama_item' => 'required|string|max:255',
            'satuan' => 'required|string|max:10',
            'jenis' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
            // 'kode_lokasi'   => 'nullable|string|max:255',
            'nama_lokasi' => 'nullable|string|max:255',
            'detail_lokasi' => 'nullable|string|max:255',
            'jumlah' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            // Validasi 'gambar' harus berupa array (bisa kosong)
            'gambar' => 'nullable|array',
        ];

        // Jika ada file yang diupload (instance UploadedFile),
        // maka validasi tiap elemen array sebagai file gambar
        if ($request->hasFile('gambar')) {
            $rules['gambar.*'] = 'image|mimes:jpeg,png,jpg|max:8192';
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();

        try {
            // Data gambar lama (sudah tersimpan di database)
            // Pastikan $oldImages adalah array, jika tidak, decode terlebih dahulu
            $oldImages = $item->gambar;
            if (!is_array($oldImages)) {
                $oldImages = json_decode($oldImages, true) ?? [];
            }

            $newImages = [];
            if ($request->has('gambar')) {
                foreach ($request->gambar as $gambar) {
                    // Jika berupa string, artinya gambar lama yang dipertahankan
                    if (is_string($gambar)) {
                        $newImages[] = $gambar;
                    } else {
                        // Jika merupakan instance UploadedFile, berarti gambar baru diupload
                        $path = $gambar->store('uploads', 'public');
                        $newImages[] = $path;
                    }
                }
            }

            // Optional: Hapus file fisik yang sudah dihapus (ada di $oldImages tapi tidak di $newImages)
            $imagesToDelete = array_diff($oldImages, $newImages);
            foreach ($imagesToDelete as $imgPath) {
                Storage::disk('public')->delete($imgPath);
            }

            // Perbaikan: Karena field 'gambar' di-cast sebagai array, simpan data sebagai array (tidak perlu json_encode)
            $item->update([
                'produksi_id' => $validatedData['produksi'],
                'kode_item' => $validatedData['kode_item'] ?? $item->kode_item,
                'nama_item' => $validatedData['nama_item'] ?? $item->nama_item,
                'satuan' => $validatedData['satuan'],
                'jenis' => $validatedData['jenis'],
                'kondisi' => $validatedData['kondisi'],
                // 'kode_lokasi' => $validatedData['kode_lokasi'],
                'nama_lokasi' => $validatedData['nama_lokasi'],
                'detail_lokasi' => $validatedData['detail_lokasi'],
                'jumlah' => $validatedData['jumlah'],
                'gambar' => $newImages,
            ]);

            DB::commit();
            session()->flash('success', 'Data berhasil diperbarui.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat update item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data.');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Item $item)
    {
        DB::beginTransaction();
        try {
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
            return redirect()->back()->withInput();
        }
    }

    public function approve(Item $item)
    {
        // Ambil data menu 'items'
        $menu = Menu::where('route', 'items')->first();

        // Ambil role user saat ini
        $currentRoleName = Auth::user()->getRoleNames()->first();
        $role = Role::where('name', $currentRoleName)->first();

        // Ambil konfigurasi approval untuk module 'items' berdasarkan role user
        $approvalRoute = ApprovalRoute::where('module', 'items')->where('role_id', $role->id)->first();

        if (!$approvalRoute) {
            abort(403, 'Anda tidak memiliki hak untuk melakukan approval.');
        }

        // Pastikan item dalam status draft untuk approval user ini:
        if ($item->approval_level !== $approvalRoute->sequence - 1) {
            abort(403, 'Item tidak dalam status draft untuk approval Anda.');
        }

        // Update approval_level menjadi nilai konfigurasi (misal, jika sequence = 1, maka item approval_level menjadi 1)
        $item->approval_level = $approvalRoute->sequence;

        // Update status: jika ada konfigurasi approval berikutnya
        $nextApprovalRoute = ApprovalRoute::where('module', 'items')->where('sequence', '>', $approvalRoute->sequence)->orderBy('sequence', 'asc')->first();
        if ($nextApprovalRoute) {
            $item->status = 'Waiting Approval';
            $item->keterangan = "Menunggu approval dari {$nextApprovalRoute->role->name}";
        } else {
            $item->status = 'Final';
            $item->keterangan = 'Final approved';
        }

        $item->save();

        // Kirim notifikasi ke seluruh user yang bertugas di tahap berikutnya (jika ada)
        if ($nextApprovalRoute) {
            if ($nextApprovalRoute->assigned_user_id) {
                $approvalUsers = User::where('id', $nextApprovalRoute->assigned_user_id)->get();
            } else {
                $approvalUsers = Role::find($nextApprovalRoute->role_id)->users;
            }

            if ($approvalUsers->count() > 0) {
                $waService = new WhatsAppNotificationService();

                foreach ($approvalUsers as $approvalUser) {
                    // Pastikan user memiliki contact
                    if ($approvalUser->contact) {
                        // Jika user memiliki data produksi (pivot) maka cek apakah ID produksi item ada di relasi pivot tersebut.
                        // Jika ada, notifikasi dikirim; jika tidak, lewati user tersebut.
                        if ($approvalUser->produksis->isNotEmpty() && !$approvalUser->produksis->pluck('id')->contains($item->produksi_id)) {
                            continue; // Lewati user yang produksinya tidak sesuai dengan item
                        }

                        $parameters = [
                            'body' => [
                                [
                                    'key' => '1',
                                    'value' => 'nama_aplikasi',
                                    'value_text' => 'Pendataan Item Motasa',
                                ],
                                [
                                    'key' => '2',
                                    'value' => 'yth',
                                    'value_text' => 'Kepada yth. ' . $approvalUser->name,
                                ],
                                [
                                    'key' => '3',
                                    'value' => 'konten',
                                    'value_text' => 'Terdapat permintaan persetujuan pada sistem pendataan barang MOI. Silakan akses alamat berikut untuk menyetujui: ' . url('/'),
                                ],
                            ],
                        ];

                        $waService->sendMessage(
                            $approvalUser->name,
                            $approvalUser->contact,
                            '7c8de24b-bc38-4dc7-b0dd-1e1ae693b653', // Template ID
                            '0e407445-9744-49b6-a648-0801dea7f33a', // Channel Integration ID
                            $parameters,
                        );
                    }
                }
            }
        }

        session()->flash('success', 'Item berhasil di-approve.');
        return redirect()->route('items.index');
    }

    public function revise(Item $item, Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'keterangan' => 'nullable|string|max:255',
        ]);

        $roleName = Auth::user()->getRoleNames()->first();

        DB::beginTransaction();

        try {
            // Kembalikan ke tahap awal approval
            $item->approval_level = 0;
            $item->status = 'Revisi';
            $item->keterangan = $validatedData['keterangan'] . ' | Diajukan oleh ' . $roleName;
            $item->save();

            DB::commit();
            session()->flash('success', 'data Item dikembalikan untuk Revisi.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat revise item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengembalikan item untuk Revisi. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function reject(Item $item, Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'keterangan' => 'nullable|string|max:255',
        ]);

        $roleName = Auth::user()->getRoleNames()->first();

        DB::beginTransaction();

        try {
            $item->status = 'Rejected';
            $item->keterangan = $validatedData['keterangan'] . ' | Rejected oleh ' . $roleName;
            $item->save();

            DB::commit();
            session()->flash('success', 'data Item telah direject.');
            return redirect()->route('items.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat reject item: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mereject data item. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function printQR($id)
    {
        $item = Item::findOrFail($id);
        return view('transaksi.items.printQR', compact('item'));
    }

    public function checkQR($id)
    {
        $exists = Item::where('id', $id)->exists();

        return response()->json([
            'exists' => $exists,
        ]);
    }
}
