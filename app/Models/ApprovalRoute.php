<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property string $module
 * @property int $role_id
 * @property int $sequence
 * @property int|null $assigned_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $assignedUser
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereAssignedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovalRoute withoutTrashed()
 * @mixin \Eloquent
 */
class ApprovalRoute extends Model
{
    use HasFactory;

    protected $fillable = ['module', 'role_id', 'sequence', 'assigned_user_id'];

    // Relasi ke Role (gunakan model Role milik Anda)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke User (jika ada assigned user)
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
