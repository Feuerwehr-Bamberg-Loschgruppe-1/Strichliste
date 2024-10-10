<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ItemType: string implements HasColor, HasIcon, HasLabel
{
    case Food = 'food';
    case Drink = 'drink';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Food => 'Essen',
            self::Drink => 'Getränk',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Food => 'fluentui-food-apple-20-o',
            self::Drink => 'carbon-drink-01',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Food => 'warning',
            self::Drink => 'info',
        };
    }
}
