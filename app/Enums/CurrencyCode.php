<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CurrencyCode: string implements HasLabel
{
    case Usd = 'usd';

    case Eur = 'eur';

    case Gbp = 'gbp';

    case Cad = 'cad';

    case Aud = 'aud';

    case Jpy = 'jpy';

    case Brl = 'brl';

    case Inr = 'inr';

    public function getLabel(): string
    {
        return match ($this) {
            self::Usd => 'US Dollar',
            self::Eur => 'Euro',
            self::Gbp => 'British Pound',
            self::Cad => 'Canadian Dollar',
            self::Aud => 'Australian Dollar',
            self::Jpy => 'Japanese Yen',
            self::Brl => 'Brazilian Real',
            self::Inr => 'Indian Rupee',
        };
    }
}
