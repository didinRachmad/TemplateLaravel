<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuRolePermission;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ambil menu dengan route 'roles'
        $menu = Menu::where('route', 'roles')->first();
        if (!$menu) {
            $this->command->error('Menu dengan route "roles" tidak ditemukan!');
            return;
        }

        // Ambil role yang akan diberi akses untuk menu Roles, misalnya 'super_admin'
        $role = Role::where('name', 'super_admin')->first();
        if (!$role) {
            $this->command->error('Role "super_admin" tidak ditemukan!');
            return;
        }

        // Ambil semua permission dari tabel permissions
        $permissions = Permission::pluck('id', 'name');

        foreach ($permissions as $permName => $permId) {
            // Masukkan data ke tabel pivot menu_role_permission
            MenuRolePermission::firstOrCreate(
                [
                    'menu_id'       => $menu->id,
                    'role_id'       => $role->id,
                    'permission_id' => $permId,
                ],
                [
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]
            );
        }

        $this->command->info('Data MenuRolePermission untuk menu Roles berhasil disisipkan.');
    }
}
