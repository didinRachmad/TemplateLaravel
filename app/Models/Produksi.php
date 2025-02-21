<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'master_produksi';

    protected $fillable = ['nama_produksi'];

    public function users()
    {
        return $this->hasMany(User::class, 'produksi_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'produksi_id');
    }
}
