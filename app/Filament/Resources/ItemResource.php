<?php

namespace App\Filament\Resources;

use App\Enums\DrinkType;
use App\Enums\ItemType;
use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'hugeicons-vegetarian-food';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(ItemType::class)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, $state) => $set('drink_type', $state === 'drink' ? 'non_alcoholic' : null)),
                Select::make('drink_type')
                    ->options(DrinkType::class)
                    ->hidden(fn (callable $get) => $get('type') !== 'drink'),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                Textarea::make('description'),
                TextInput::make('stock')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->icon('heroicon-m-envelope')
                    ->sortable(),
                TextColumn::make('drink_type')
                    ->badge()
                    ->label('Getränketyp')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Preis')
                    ->sortable()
                    ->money('EUR'),
                TextColumn::make('stock')
                    ->label('Bestand')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
