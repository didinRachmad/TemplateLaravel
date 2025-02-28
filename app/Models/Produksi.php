<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
