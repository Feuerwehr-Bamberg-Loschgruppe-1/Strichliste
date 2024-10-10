<?php

namespace Database\Factories;

use App\Enums\DrinkType;
use App\Enums\ItemType;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'type' => Arr::random(ItemType::cases()), // oder 'food'
            'drink_type' => Arr::random(DrinkType::cases()),
            'price' => $this->faker->randomFloat(2, 1, 10), // Zufälliger Preis
            'description' => $this->faker->sentence(),
            'stock' => $this->faker->numberBetween(0, 100), // Zufälliger Lagerbestand
        ];
    }
}
