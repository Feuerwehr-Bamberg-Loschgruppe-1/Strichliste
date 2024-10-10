<?php

namespace App\Livewire\Filament\Tables;

use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\TransactionResource\Pages\EditTransaction;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class TransactionTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected ?User $user = null;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function table(Table $table): Table
    {
        return TransactionResource::table($table)
            ->query(fn() => TransactionResource::getEloquentQuery())
            ->modifyQueryUsing(function (Builder $query) {
                if (! $this->user) {
                    return;
                }

                $query->where('user_id', $this->user->id);
            })
            //->recordUrl(
            //fn($record) => EditTransaction::getUrl(['record' => $record])
            //)
            ->defaultSort('created_at', "desc");
    }

    public function render(): string
    {
        return <<<'HTML'
            <div>
                {{ $this->table }}
            </div>
        HTML;
    }
}
