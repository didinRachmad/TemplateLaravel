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
        // Ambil semua menu yang memiliki route tidak null
        $menus = Menu::whereNotNull('route')->get();
        if ($menus->isEmpty()) {
            $this->command->error('Tidak ada menu dengan route yang valid!');
            return;
        }

        // Ambil role super_admin
        $role = Role::where('name', 'super_admin')->first();
        if (!$role) {
            $this->command->error('Role "super_admin" tidak ditemukan!');
            return;
        }

        // Ambil semua permission
        $permissions = Permission::pluck('id', 'name');

        foreach ($menus as $menu) {
            foreach ($permissions as $permName => $permId) {
                // Masukkan data ke tabel pivot menu_role_permission
                MenuRolePermission::firstOrCreate(
                    [
                        'menu_id' => $menu->id,
                        'role_id' => $role->id,
                        'permission_id' => $permId,
                    ],
                    [
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                );
            }
        }

        $this->command->info('Data MenuRolePermission untuk semua menu dengan route yang valid berhasil disisipkan.');
    }
}
