<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Item;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class MonthlyDrinksWidget extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        $year = now()->year;

        $items = Item::where('type', 'drink')->get();

        $selects = ['strftime("%m", transactions.created_at) as month'];
        foreach ($items as $item) {
            $selects[] = "SUM(CASE WHEN items.name = '{$item->name}' THEN 1 ELSE 0 END) as {$item->name}";
        }

        return Transaction::query()
            ->selectRaw(implode(', ', $selects))
            ->selectRaw('strftime("%m", transactions.created_at) || "-" || items.id as id') // Eindeutige ID
            ->join('items', 'transactions.item_id', '=', 'items.id')
            ->whereYear('transactions.created_at', $year)
            ->groupBy('month')
            ->orderBy('month');
    }

    protected function getTableColumns(): array
    {
        $columns = [
            TextColumn::make('month')
                ->label('Monat')
                ->formatStateUsing(fn($state) => Carbon::create()->month((int)$state)->format('F')),
        ];

        $items = Item::where('type', 'drink')->get();

        foreach ($items as $item) {
            $columns[] = TextColumn::make($item->name)
                ->label($item->name)
                ->formatStateUsing(function ($state, $record) use ($item) {
                    return $record->{$item->name} ?? 0;
                })
                ->summarize(Sum::make()->label(''));
        }

        return $columns;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->filters([
                SelectFilter::make('year')
                    ->label('Jahr')
                    ->options($this->getAvailableYears())
                //->default(now()->year),  // Standardmäßig das aktuelle Jahr
            ]);
    }

    protected function getAvailableYears(): array
    {
        return Transaction::query()
            ->selectRaw('strftime("%Y", created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year', 'year')
            ->toArray();
    }

    protected int|string|array $columnSpan = 'full';
}
