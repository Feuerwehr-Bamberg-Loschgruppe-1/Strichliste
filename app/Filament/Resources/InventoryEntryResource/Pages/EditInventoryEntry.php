<?php

namespace App\Filament\Resources\InventoryEntryResource\Pages;

use App\Filament\Resources\InventoryEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryEntry extends EditRecord
{
    protected static string $resource = InventoryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
