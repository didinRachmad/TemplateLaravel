<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['produksi_id', 'kode_item', 'nama_item', 'satuan', 'jenis', 'kondisi', 'kode_lokasi', 'nama_lokasi', 'detail_lokasi', 'jumlah', 'gambar', 'approval_level', 'status', 'keterangan'];

    protected $casts = [
        'gambar' => 'array',
    ];

    public function scopeByProduksi($query, $produksi)
    {
        return $query->where('produksi_id', $produksi);
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}
