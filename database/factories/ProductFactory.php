<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item code' =>'IC-1000'.rand(10, 500),
            'description' => 'name of Product'.rand(10, 500),
            'unit_price' => mt_rand(10, 1000)
        ];
    }
}
