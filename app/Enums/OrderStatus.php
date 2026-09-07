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

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';

    case Processing = 'processing';

    case Shipped = 'shipped';

    case Delivered = 'delivered';

    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Processing => 'Processing',
            self::Shipped => 'Shipped',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'info',
            self::Processing => 'warning',
            self::Shipped, self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::New => FilamentIcon::resolve(DemoIconAlias::ENUMS_ORDER_STATUS_NEW) ?? Heroicon::Sparkles,
            self::Processing => FilamentIcon::resolve(DemoIconAlias::ENUMS_ORDER_STATUS_PROCESSING) ?? Heroicon::ArrowPath,
            self::Shipped => FilamentIcon::resolve(DemoIconAlias::ENUMS_ORDER_STATUS_SHIPPED) ?? Heroicon::Truck,
            self::Delivered => FilamentIcon::resolve(DemoIconAlias::ENUMS_ORDER_STATUS_DELIVERED) ?? Heroicon::CheckBadge,
            self::Cancelled => FilamentIcon::resolve(DemoIconAlias::ENUMS_ORDER_STATUS_CANCELLED) ?? Heroicon::XCircle,
        };
    }
}
