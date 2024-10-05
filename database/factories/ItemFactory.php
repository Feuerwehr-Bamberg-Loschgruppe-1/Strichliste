<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'type' => 'drink', // oder 'food'
            'price' => $this->faker->randomFloat(2, 1, 10), // Zufälliger Preis
            'description' => $this->faker->sentence(),
            'stock' => $this->faker->numberBetween(0, 100), // Zufälliger Lagerbestand
        ];
    }
}
