<?php

namespace Database\Factories;

use App\Models\Pizza;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pizza>
 */
class PizzaFactory extends Factory
{
    protected $model = Pizza::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'ingredients' =>  ['tomate' => 1, 'mozzarella' => 1, 'basil' => 1, 'peperoni' => 2],
            'image' => $this->faker->imageUrl(),
        ];
    }
}
