<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('container_count')->nullable(); // Anzahl der Kästen oder Tüten
            $table->integer('items_per_container')->nullable(); // Anzahl der Flaschen pro Kasten oder Produkte pro Tüte
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('price_per_item', 8, 2)->nullable(); // Preis pro Flasche oder Produkt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_entries');
    }
};
