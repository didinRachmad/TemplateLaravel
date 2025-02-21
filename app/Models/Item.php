<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['produksi_id', 'kode_item', 'nama_item', 'jenis', 'kondisi', 'kode_lokasi', 'nama_lokasi', 'jumlah', 'gambar', 'approval_level', 'status', 'keterangan'];

    public function scopeByProduksi($query, $produksi)
    {
        return $query->where('produksi_id', $produksi);
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}
