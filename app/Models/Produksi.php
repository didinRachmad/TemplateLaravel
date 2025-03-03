<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Produksi withoutTrashed()
 * @mixin \Eloquent
 */
class Produksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_produksi';

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_produksi', 'produksi_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'produksi_id');
    }
}
