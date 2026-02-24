<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum ExpenseStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';

    case Submitted = 'submitted';

    case Approved = 'approved';

    case Rejected = 'rejected';

    case Reimbursed = 'reimbursed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Reimbursed => 'Reimbursed',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Submitted => 'info',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Reimbursed => 'primary',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Draft => Heroicon::Pencil,
            self::Submitted => Heroicon::PaperAirplane,
            self::Approved => Heroicon::Check,
            self::Rejected => Heroicon::XMark,
            self::Reimbursed => Heroicon::Banknotes,
        };
    }
}
