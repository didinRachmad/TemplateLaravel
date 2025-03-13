<?php

namespace Database\Factories;

use App\Models\Produksi;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduksiFactory extends Factory
{
    protected $model = Produksi::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company, // Contoh: nama perusahaan sebagai nama produksi
            // Tambahkan field lain yang diperlukan sesuai dengan migrasi Produksi
        ];
    }
}
