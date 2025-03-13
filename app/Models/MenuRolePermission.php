<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $menu_id
 * @property int $role_id
 * @property int $permission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Menu $menu
 * @property-read \App\Models\Permission $permission
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuRolePermission withoutTrashed()
 * @mixin \Eloquent
 */
class MenuRolePermission extends Model
{
    use HasFactory;

    protected $table = 'menu_role_permission';

    protected $fillable = ['menu_id', 'role_id', 'permission_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
