<?php

namespace App\Models;

use App\Enums\DrinkType;
use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'drink_type',
        'price',
        'description',
        'stock',
    ];

    protected $casts = [
        'type' => ItemType::class,
        'drink_type' => DrinkType::class,
    ];
}
