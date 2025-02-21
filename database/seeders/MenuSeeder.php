<?php

namespace Database\Seeders;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'title'       => 'Items',
                'route'       => 'items',
                'icon'        => '', // Opsional, bisa diisi dengan class icon jika diperlukan
                'order'       => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Roles',
                'route'       => 'roles',
                'icon'        => '',
                'order'       => 2,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Permissions',
                'route'       => 'permissions',
                'icon'        => '',
                'order'       => 3,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Menus',
                'route'       => 'menus',
                'icon'        => '',
                'order'       => 4,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Approval Routes',
                'route'       => 'approval_routes',
                'icon'        => '',
                'order'       => 5,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Users',
                'route'       => 'users',
                'icon'        => '',
                'order'       => 6,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Master Produksi',
                'route'       => 'master_produksi',
                'icon'        => '',
                'order'       => 7,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        Menu::insert($menus);
    }
}
