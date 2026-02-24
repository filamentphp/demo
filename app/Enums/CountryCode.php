<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CountryCode: string implements HasLabel
{
    case Us = 'us';

    case Gb = 'gb';

    case De = 'de';

    case Fr = 'fr';

    case Ca = 'ca';

    case Au = 'au';

    case Nl = 'nl';

    case Br = 'br';

    case Jp = 'jp';

    case In = 'in';

    public function getLabel(): string
    {
        return match ($this) {
            self::Us => 'United States',
            self::Gb => 'United Kingdom',
            self::De => 'Germany',
            self::Fr => 'France',
            self::Ca => 'Canada',
            self::Au => 'Australia',
            self::Nl => 'Netherlands',
            self::Br => 'Brazil',
            self::Jp => 'Japan',
            self::In => 'India',
        };
    }
}
