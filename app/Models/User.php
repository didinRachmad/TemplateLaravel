<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool hasMenuPermission(int $menuId, string $permissionName)
 * @mixin \App\Models\User
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'produksi_id',
        'contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'contact' => 'encrypted',
    ];

    /**
     * Cek apakah user memiliki hak akses pada menu tertentu.
     *
     * @param int $menuId
     * @param string $permissionName
     * @return bool
     */
    public function hasMenuPermission($menuId, $permissionName)
    {
        // Ambil permission berdasarkan nama
        $permission = Permission::where('name', $permissionName)->first();
        if (!$permission) {
            return false;
        }

        // Ambil semua role ID user
        $roleIds = $this->roles->pluck('id')->toArray();

        // Cek di tabel pivot menu_role_permission
        return MenuRolePermission::where('menu_id', $menuId)
            ->whereIn('role_id', $roleIds)
            ->where('permission_id', $permission->id)
            ->exists();
    }

    public function produksis()
    {
        return $this->belongsToMany(Produksi::class, 'user_produksi', 'user_id', 'produksi_id');
    }
}
