<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role_permission')
        ->withPivot('permission_id')
        ->withTimestamps();
    }
}
