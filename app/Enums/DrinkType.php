<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DrinkType: string implements HasColor, HasIcon, HasLabel
{
    case Alcoholic = 'alcoholic';
    case NonAlcoholic = 'non_alcoholic';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Alcoholic => 'Alkoholisch',
            self::NonAlcoholic => 'Nicht Alkoholisch',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Alcoholic => 'lucide-beer',
            self::NonAlcoholic => 'lucide-beer-off',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Alcoholic => 'danger',
            self::NonAlcoholic => 'success',
        };
    }
}
