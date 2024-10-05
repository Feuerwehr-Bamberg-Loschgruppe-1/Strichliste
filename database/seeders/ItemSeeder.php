<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Bier', 'type' => 'drink', 'drink_type' => 'alcoholic', 'price' => 1.30, 'description' => 'Ein kühles Bier', 'stock' => 0],
            ['name' => 'Alkoholfreies', 'type' => 'drink', 'drink_type' => 'alcoholic', 'price' => 1.30, 'description' => 'Ein alkoholfreies Bier', 'stock' => 0],
            ['name' => 'Radler', 'type' => 'drink', 'drink_type' => 'alcoholic', 'price' => 1.30, 'description' => 'Ein erfrischendes Radler', 'stock' => 0],
            ['name' => 'Weizen', 'type' => 'drink', 'drink_type' => 'alcoholic', 'price' => 1.30, 'description' => 'Ein leckeres Weizenbier', 'stock' => 0],
            ['name' => 'Wasser', 'type' => 'drink', 'drink_type' => 'non_alcoholic', 'price' => 1.00, 'description' => 'Ein stilles Wasser', 'stock' => 0],
            ['name' => 'Spezi', 'type' => 'drink', 'drink_type' => 'non_alcoholic', 'price' => 1.00, 'description' => 'Eine erfrischende Spezi', 'stock' => 0],
            ['name' => 'Limo', 'type' => 'drink', 'drink_type' => 'non_alcoholic', 'price' => 1.00, 'description' => 'Eine erfrischende Limo', 'stock' => 0],
            ['name' => 'Apfelschorle', 'type' => 'drink', 'drink_type' => 'non_alcoholic', 'price' => 1.00, 'description' => 'Eine erfrischende Apfelschorle', 'stock' => 0],
            ['name' => 'Cola', 'type' => 'drink', 'drink_type' => 'non_alcoholic', 'price' => 1.00, 'description' => 'Eine erfrischende Cola', 'stock' => 0],
            ['name' => 'Brezeln', 'type' => 'food', 'price' => 1.00, 'description' => 'Eine leckere Brezel', 'stock' => 0],
            ['name' => 'Haribo', 'type' => 'food', 'price' => 1.00, 'description' => 'Eine Packung Haribo', 'stock' => 0],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
