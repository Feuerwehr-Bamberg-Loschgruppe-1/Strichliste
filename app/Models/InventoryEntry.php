<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'container_count',
        'items_per_container',
        'total_price',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Berechnet die Menge basierend auf container_count und items_per_container
    public function getQuantityAttribute()
    {
        return $this->container_count * $this->items_per_container;
    }
}
