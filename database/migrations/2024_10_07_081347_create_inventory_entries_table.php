<?php

use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class)->constrained()->cascadeOnDelete();
            $table->integer('container_count')->nullable(); // Anzahl der Kästen oder Tüten
            $table->integer('items_per_container')->nullable(); // Anzahl der Flaschen pro Kasten oder Produkte pro Tüte
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('price_per_item', 8, 2)->nullable(); // Preis pro Flasche oder Produkt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_entries');
    }
};
