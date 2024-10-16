<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryEntryResource\Pages;
use App\Filament\Resources\InventoryEntryResource\RelationManagers;
use App\Models\InventoryEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class InventoryEntryResource extends Resource
{
    protected static ?string $model = InventoryEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wareneingang')
                    ->schema([
                        Repeater::make('entries')
                            ->schema([
                                Select::make('item_id')
                                    ->label('Artikel')
                                    ->relationship('item', 'name')
                                    ->required(),
                                TextInput::make('total_price')
                                    ->label('Gesamtpreis')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('container_count')
                                    ->label('Anzahl Container')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('items_per_container')
                                    ->label('Artikel pro Container')
                                    ->required()
                                    ->numeric(),
                            ])
                            ->minItems(1)
                            ->required()
                            ->grid(2)
                            ->columns(2)
                            ->reorderableWithDragAndDrop(false)
                            ->label('Produkte')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')->label('Artikel'),
                Tables\Columns\TextColumn::make('container_count')->label('Anzahl Container'),
                Tables\Columns\TextColumn::make('items_per_container')->label('Artikel pro Container'),
                Tables\Columns\TextColumn::make('total_price')->label('Gesamtpreis'),
                Tables\Columns\TextColumn::make('price_per_item')->label('Preis pro Artikel'),
            ])->defaultSort('created_at', 'desc')
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
            ])
            ->striped();
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
            'index' => Pages\ListInventoryEntries::route('/'),
            'create' => Pages\CreateInventoryEntry::route('/create'),
            'edit' => Pages\EditInventoryEntry::route('/{record}/edit'),
        ];
    }
}
