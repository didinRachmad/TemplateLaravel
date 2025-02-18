<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'route', 'icon', 'order'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role_permission')
            ->withPivot('permission_id')
            ->withTimestamps();
    }
}
