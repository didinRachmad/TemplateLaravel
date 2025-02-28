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
                'title'       => 'Master',
                'route'       => null,
                'parent_id'   => null,
                'icon'        => null,
                'order'       => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Master Produksi',
                'route'       => 'master_produksi',
                'parent_id'   => '1',
                'icon'        => null,
                'order'       => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Transaksi',
                'route'       => null,
                'parent_id'   => null,
                'icon'        => null,
                'order'       => 2,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Items',
                'route'       => 'items',
                'parent_id'   => '3',
                'icon'        => null,
                'order'       => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Setting',
                'route'       => null,
                'parent_id'   => null,
                'icon'        => null,
                'order'       => 3,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Roles',
                'route'       => 'roles',
                'parent_id'   => '5',
                'icon'        => null,
                'order'       => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Permissions',
                'route'       => 'permissions',
                'parent_id'   => '5',
                'icon'        => null,
                'order'       => 2,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Users',
                'route'       => 'users',
                'parent_id'   => '5',
                'icon'        => null,
                'order'       => 3,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Menus',
                'route'       => 'menus',
                'parent_id'   => '5',
                'icon'        => null,
                'order'       => 4,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'title'       => 'Approval Routes',
                'route'       => 'approval_routes',
                'parent_id'   => '5',
                'icon'        => null,
                'order'       => 5,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        Menu::insert($menus);
    }
}
