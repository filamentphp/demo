<?php

namespace App\Enums;

use App\Filament\DemoIconAlias;
use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ExpenseCategory: string implements HasColor, HasIcon, HasLabel
{
    case Travel = 'travel';

    case Meals = 'meals';

    case Supplies = 'supplies';

    case Equipment = 'equipment';

    case Software = 'software';

    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Travel => 'Travel',
            self::Meals => 'Meals',
            self::Supplies => 'Supplies',
            self::Equipment => 'Equipment',
            self::Software => 'Software',
            self::Other => 'Other',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Travel => 'info',
            self::Meals => 'success',
            self::Supplies => 'warning',
            self::Equipment => 'primary',
            self::Software, self::Other => 'gray',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Travel => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_CATEGORY_TRAVEL) ?? Heroicon::GlobeAlt,
            self::Meals => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_CATEGORY_MEALS) ?? Heroicon::Cake,
            self::Supplies => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_CATEGORY_SUPPLIES) ?? Heroicon::ShoppingCart,
            self::Equipment => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_CATEGORY_EQUIPMENT) ?? Heroicon::WrenchScrewdriver,
            self::Software => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_CATEGORY_SOFTWARE) ?? Heroicon::ComputerDesktop,
            self::Other => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_CATEGORY_OTHER) ?? Heroicon::EllipsisHorizontal,
        };
    }
}
