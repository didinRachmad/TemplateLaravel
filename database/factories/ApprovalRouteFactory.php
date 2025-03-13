<?php

namespace Database\Factories;

use App\Models\ApprovalRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalRouteFactory extends Factory
{
    protected $model = ApprovalRoute::class;

    public function definition()
    {
        return [
            'module'   => 'items',
            'role_id'  => 1, // atau bisa menggunakan factory untuk role, misalnya Role::factory()->create()->id
            'sequence' => $this->faker->numberBetween(1, 3),
            // Tambahkan field lain sesuai struktur tabel ApprovalRoute
        ];
    }
}
