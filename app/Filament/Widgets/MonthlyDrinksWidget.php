<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Item;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;

class MonthlyDrinksWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public $currentYear;
    public $availableYears = [];

    /**
     * Initialize the widget with available years and set the current year.
     */
    public function mount(): void
    {
        $this->availableYears = $this->getAvailableYears();
        $this->currentYear = $this->availableYears[0] ?? now()->year;
    }

    /**
     * Update the current year.
     *
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->currentYear = $year;
        $this->resetTable();
    }

    /**
     * Get the query for the table with drink item counts by month.
     *
     * @return Builder
     */
    protected function getTableQuery(): Builder
    {
        $year = $this->currentYear;

        // Retrieve all drink items.
        $items = Item::where('type', 'drink')->get();

        // Build dynamic select statement based on drink items.
        $selects = [
            'strftime("%m", transactions.created_at) as month',
            'strftime("%m", transactions.created_at) || "-" || items.id as id'
        ];

        foreach ($items as $item) {
            $selects[] = "SUM(CASE WHEN items.name = '{$item->name}' THEN 1 ELSE 0 END) as {$item->name}";
        }

        return Transaction::query()
            ->selectRaw(implode(', ', $selects))
            ->join('items', 'transactions.item_id', '=', 'items.id')
            ->whereYear('transactions.created_at', $year)
            ->groupBy('month')
            ->orderBy('month');
    }

    /**
     * Define table columns dynamically based on available drink items.
     *
     * @return array
     */
    protected function getTableColumns(): array
    {
        $columns = [
            TextColumn::make('month')
                ->label('Monat')
                ->formatStateUsing(fn($state) => Carbon::create()->month((int) $state)->format('F')),
        ];

        // Add columns for each drink item.
        $items = Item::where('type', 'drink')->get();

        foreach ($items as $item) {
            $columns[] = TextColumn::make($item->name)
                ->label($item->name)
                ->formatStateUsing(fn($state, $record) => $record->{$item->name} ?? 0)
                ->summarize(Sum::make()->label(''));
        }

        return $columns;
    }

    /**
     * Retrieve available years for the widget.
     *
     * @return array
     */
    protected function getAvailableYears(): array
    {
        return Transaction::query()
            ->selectRaw('strftime("%Y", created_at) as year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year', 'year')
            ->toArray();
    }

    /**
     * Configure the table widget.
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->heading('')
            ->paginated(false)
            ->striped();
    }

    /**
     * Render the widget view.
     *
     * @return View
     */
    public function render(): View
    {
        return view('filament.widgets.monthly-drinks-widget', [
            'availableYears' => $this->availableYears,
            'currentYear' => $this->currentYear,
        ]);
    }
}
