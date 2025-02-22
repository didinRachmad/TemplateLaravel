<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'P1'],
            ['name' => 'P2'],
            ['name' => 'HO'],
            ['name' => 'Studio RnD'],
            ['name' => 'Makassar'],
            ['name' => 'Ciwo'],
            ['name' => 'GT'],
        ];

        DB::table('master_produksi')->insert($data);
    }
}
