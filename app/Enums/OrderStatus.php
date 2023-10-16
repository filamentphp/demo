<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case NEW = 'new';

    case PROCESSING = 'processing';

    case SHIPPED = 'shipped';

    case DELIVERED = 'delivered';

    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => 'gray',
            self::PROCESSING => 'warning',
            self::SHIPPED, self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
