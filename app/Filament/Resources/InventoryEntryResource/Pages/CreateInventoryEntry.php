<?php

namespace App\Filament\Resources\InventoryEntryResource\Pages;

use App\Filament\Resources\InventoryEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use App\Models\InventoryEntry;


class CreateInventoryEntry extends CreateRecord
{
    protected static string $resource = InventoryEntryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        foreach ($data['entries'] as $entryData) {
            $inventoryEntry = InventoryEntry::create($entryData);

            // Holen Sie sich das entsprechende Item
            $item = Item::find($inventoryEntry->item_id);

            // Überprüfen Sie den Wert von quantity
            $quantity = $inventoryEntry->quantity;

            // Berechnen Sie den Preis pro Artikel
            if ($quantity > 0) {
                $inventoryEntry->price_per_item = $inventoryEntry->total_price / $quantity;
            } else {
                $inventoryEntry->price_per_item = 0;
            }

            // Speichern Sie das InventoryEntry erneut, um den price_per_item zu aktualisieren
            $inventoryEntry->save();

            // Aktualisieren Sie den Bestand des Items
            if ($item) {
                $item->stock += $quantity;
                $item->save();
            }
        }

        // Rückgabe eines der erstellten Einträge, um die Methode zu erfüllen
        return $inventoryEntry;
    }
}
