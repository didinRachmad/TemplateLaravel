<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int|null $produksi_id
 * @property string $kode_item
 * @property string $nama_item
 * @property string $satuan
 * @property string $jenis
 * @property string $kondisi
 * @property string|null $kode_lokasi
 * @property string|null $nama_lokasi
 * @property string|null $detail_lokasi
 * @property string $jumlah
 * @property array|null $gambar
 * @property int $approval_level
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Produksi|null $produksi
 * @method static \Illuminate\Database\Eloquent\Builder|Item byProduksi($produksi)
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereApprovalLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDetailLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereKodeItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereKodeLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNamaItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNamaLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereProduksiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Item withoutTrashed()
 * @mixin \Eloquent
 */
class Item extends Model
{
    use HasFactory;

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
