<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    // Tentukan model yang akan digunakan oleh factory ini
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'Items',
            'route' => 'items',
            // Jika ada field lain, tambahkan di sini
            // Contoh:
            // 'nama_menu' => $this->faker->word,
        ];
    }
}
