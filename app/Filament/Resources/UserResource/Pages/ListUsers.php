<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make('All'),
            'poisitiveBalance' => Tab::make('Guthaben')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('balance', '>', 0)),
            'negativeBalance' => Tab::make('Schulden')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('balance', '<', 0)),
        ];
    }
    public function getDefaultActiveTab(): string | int | null
    {
        return 'All';
    }
}
