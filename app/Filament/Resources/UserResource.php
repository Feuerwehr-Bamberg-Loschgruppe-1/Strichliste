<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'sui-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Split::make([
                Section::make('Infos')
                    ->id('userinformations')
                    ->icon('heroicon-m-user')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('first_name')->required(),
                        TextInput::make('email')->email()->required(),
                    ])
                    ->compact(),
                Section::make('Admin')
                    ->id('adminstatus')
                    ->icon('clarity-administrator-solid')
                    ->schema([
                        ToggleButtons::make('is_admin')
                            ->label('Administrator')
                            ->boolean()
                            ->inline()
                            ->default(false)
                            ->reactive() // Macht das Feld reaktiv, sodass Änderungen sofort angewendet werden
                            ->disabled(function ($get) {
                                // Prüfe, ob der eingeloggte Benutzer seine eigenen Daten bearbeitet
                                return Auth::user()->id === $get('id'); // Hier wird die Benutzer-ID verglichen
                            })
                            ->helperText(fn ($get) => Auth::user()->id === $get('id') ? 'Du kannst deinen eigenen Admin-Status nicht ändern.' : null)
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Wenn der Benutzer zum Admin gemacht wird, setze das Passwortfeld auf erforderlich
                                if ($state) {
                                    $set('password_required', true); // Setze einen temporären Zustand
                                } else {
                                    $set('password_required', false); // Passwort nicht erforderlich, wenn kein Admin
                                    $set('password', null);
                                }
                            }), // Admin-Status bearbeiten
                        TextInput::make('password')
                            ->password()
                            ->label('Passwort')
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state)) // Speichert nur, wenn ein Passwort gesetzt wird
                            ->hidden(fn (callable $get) => ! $get('is_admin')) // Feld nur anzeigen, wenn is_admin == true
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null), // Passwort hashen, wenn vorhanden
                        TextInput::make('password_confirmation')
                            ->password()
                            ->label('Passwort bestätigen')
                            ->revealable()
                            ->same('password') // Muss dem Passwort entsprechen
                            ->dehydrated(false) // Nicht speichern
                            ->hidden(fn (callable $get) => ! $get('is_admin')), // Nur anzeigen, wenn is_admin == true
                    ])
                    ->compact(),
            ])->from('2xl'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable(),
                TextColumn::make('first_name')->sortable(),
                TextColumn::make('email')->sortable()->icon('heroicon-m-envelope'),
                TextColumn::make('balance')
                    ->label('Guthaben')
                    ->sortable()
                    ->money('EUR')
                    ->color(function ($record) {
                        return $record->balance < 0 ? 'danger' : 'success';
                    }),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean() // Zeigt verschiedene Icons für true/false an
                    ->trueIcon('heroicon-o-check-circle') // Icon für true
                    ->falseIcon('heroicon-o-x-circle') // Icon für false
                    ->trueColor('success') // Farbe für true
                    ->falseColor('danger'), // Farbe für false
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('transactions')
                    ->color('default')
                    ->icon('heroicon-o-banknotes')
                    ->modal(true)
                    ->modalContent(
                        fn (User $record): View => view('components.user-transaction-modal', ['user' => $record])
                    )
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    ->modalSubmitAction(false),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
