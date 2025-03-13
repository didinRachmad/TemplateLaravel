<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    // Tentukan model yang akan digunakan oleh factory ini
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            // Tambahkan field lain sesuai dengan struktur tabel Role kamu
        ];
    }
}
