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

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Draft => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_STATUS_DRAFT) ?? Heroicon::Pencil,
            self::Submitted => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_STATUS_SUBMITTED) ?? Heroicon::PaperAirplane,
            self::Approved => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_STATUS_APPROVED) ?? Heroicon::Check,
            self::Rejected => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_STATUS_REJECTED) ?? Heroicon::XMark,
            self::Reimbursed => FilamentIcon::resolve(DemoIconAlias::ENUMS_EXPENSE_STATUS_REIMBURSED) ?? Heroicon::Banknotes,
        };
    }
}
