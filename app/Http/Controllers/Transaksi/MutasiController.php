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

class MutasiController extends Controller
{
    public function index()
    {
        // Ambil data menu berdasarkan route 'mutasi'
        $menu = Menu::where('route', 'mutasi')->first();

        // Ambil nilai produksi dari user yang sedang login
        $allowedProduksi = Auth::user()->produksi_id;

        if ($allowedProduksi) {
            // Jika user memiliki nilai produksi, ambil item berdasarkan produksi tersebut dan status Final
            $items = Item::byProduksi($allowedProduksi)->where('status', 'Final')->get();
        } else {
            // Jika tidak ada nilai produksi, ambil semua item dengan status Final
            $items = Item::where('status', 'Final')->get();
        }

        // Kirim data ke view
        return view('transaksi.mutasi.index', compact('menu', 'items'));
    }

    public function show(Item $item)
    {
        return view('transaksi.mutasi.show', compact('item'));
    }

    public function create() {}

    public function store(Request $request) {}

    public function edit(Item $item)
    {
        $produksiList = Produksi::all();
        return view('transaksi.mutasi.edit', compact('item', 'produksiList'));
    }

    public function update(Request $request, Item $item)
    {
        $validatedData = $request->validate([
            'produksi_baru'      => 'required|exists:master_produksi,id',
            'lokasi_baru'        => 'required|string|max:255',
            'detail_lokasi_baru' => 'required|string|max:255',
            'jumlah_mutasi'      => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $jumlah_sekarang = $item->jumlah;
            $jumlah_mutasi   = $validatedData['jumlah_mutasi'];

            // Pengecekan: jumlah mutasi tidak boleh melebihi jumlah data awal
            if ($jumlah_mutasi > $jumlah_sekarang) {
                session()->flash('error', 'Jumlah mutasi tidak boleh melebihi jumlah item yang tersedia.');
                return redirect()->back()->withInput();
            }

            // Tentukan field yang akan dicatat log-nya
            $fields = ['produksi_id', 'nama_lokasi', 'detail_lokasi', 'jumlah'];

            // Ambil data lama dari item dan letakkan 'nama_produksi' di awal
            $baseOldData = array_intersect_key($item->toArray(), array_flip($fields));
            $oldData = array_merge(['nama_produksi' => optional($item->produksi)->name], $baseOldData);

            // Inisialisasi variabel changed_data (untuk new item) dan new_data (update pada item lama)
            $changedData = null; // default untuk mutasi total

            if ($jumlah_mutasi == $jumlah_sekarang) {
                // Mutasi TOTAL: update langsung tanpa membuat item baru
                $item->update([
                    'produksi_id'   => $validatedData['produksi_baru'],
                    'nama_lokasi'   => $validatedData['lokasi_baru'],
                    'detail_lokasi' => $validatedData['detail_lokasi_baru'],
                ]);
            } else {
                // Partial mutasi: update item lama dengan sisa jumlah
                $sisa = $jumlah_sekarang - $jumlah_mutasi;
                $item->update([
                    'jumlah' => $sisa,
                ]);

                // Buat item baru dengan jumlah mutasi
                $dataBaru = [
                    'produksi_id'   => $validatedData['produksi_baru'],
                    'kode_item'     => $item->kode_item,
                    'nama_item'     => $item->nama_item,
                    'satuan'        => $item->satuan,
                    'jenis'         => $item->jenis,
                    'kondisi'       => $item->kondisi,
                    'nama_lokasi'   => $validatedData['lokasi_baru'],
                    'detail_lokasi' => $validatedData['detail_lokasi_baru'],
                    'jumlah'        => $jumlah_mutasi,
                    'gambar'        => $item->gambar,
                    'approval_level' => $item->approval_level,
                    'status'        => $item->status,
                    'keterangan'    => $item->keterangan,
                ];

                $newItem = Item::create($dataBaru);
                // Simpan data baru (changed_data) dari new item, letakkan nama_produksi di awal
                $baseChangedData = array_intersect_key($newItem->toArray(), array_flip($fields));
                $changedData = array_merge(['nama_produksi' => optional($newItem->produksi)->name], $baseChangedData);
            }

            // Ambil data baru (new_data) pada item yang diupdate, sertakan relasi 'produksi'
            $itemFresh = $item->fresh(['produksi']);
            $baseNewData = array_intersect_key($itemFresh->toArray(), array_flip($fields));
            $newData = array_merge(['nama_produksi' => optional($itemFresh->produksi)->name], $baseNewData);

            // Cek apakah ada perubahan: bandingkan old_data dengan new_data dan juga pastikan jika partial mutasi, changed_data ada
            if ($oldData == $newData && is_null($changedData)) {
                DB::rollBack();
                throw new \Exception('Tidak ada data yang berubah.');
            } else {
                // Simpan log aktivitas dengan properti lengkap
                activity()
                    ->performedOn($item)
                    ->causedBy(auth()->user() ?? null)
                    ->withProperties([
                        'jumlah_mutasi' => $jumlah_mutasi,
                        'old_data'      => $oldData,
                        'new_data'      => $newData,
                        'changed_data'  => $changedData,
                    ])
                    ->log('Proses mutasi item dilakukan');
            }

            DB::commit();
            session()->flash('success', 'Mutasi berhasil.');
            return redirect()->route('mutasi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getMessage() === 'Tidak ada data yang berubah.') {
                session()->flash('error', $e->getMessage());
            } else {
                session()->flash('error', 'Terjadi kesalahan saat melakukan mutasi.');
            }
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Item $item) {}
}
