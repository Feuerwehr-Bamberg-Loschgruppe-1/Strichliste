<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use App\Models\Item;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->mapWithKeys(function ($user) {
                        return [$user->id => $user->name . ' ' . $user->first_name];
                    }))
                    ->searchable()
                    ->required(),
                Forms\Components\ToggleButtons::make('category')
                    ->label('Category')
                    ->inline()
                    ->options([
                        'booking' => 'Booking',
                        'payment' => 'Payment',
                    ])
                    ->colors([
                        'booking' => 'warning',
                        'payment' => 'success',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('item_id')
                    ->label('Item')
                    ->options(Item::all()->pluck('name', 'id'))
                    ->searchable()
                    ->nullable() // Make item_id nullable
                    ->required(fn($get) => $get('category') === 'booking')
                    ->visible(fn($get) => $get('category') === 'booking'),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(fn($get) => $get('category') === 'payment')
                    ->visible(fn($get) => $get('category') === 'payment'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Name'),
                Tables\Columns\TextColumn::make('user.first_name')->label('Vorname'),
                Tables\Columns\TextColumn::make('item.name')->label('Item'),
                Tables\Columns\TextColumn::make('amount')->sortable()->money('EUR'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategorie')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state === 'booking' ? 'Buchung' : 'Bezahlung';
                    })
                    ->icon(function ($record) {
                        return $record->category === 'booking' ? 'heroicon-o-shopping-cart' : 'bi-cash-coin';
                    })
                    ->color(function ($record) {
                        return $record->category === 'booking' ? 'warning' : 'success';
                    }),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }
}
