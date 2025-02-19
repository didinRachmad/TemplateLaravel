<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'route', 'icon', 'order'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role_permission')
            ->withPivot('permission_id')
            ->withTimestamps();
    }
}
