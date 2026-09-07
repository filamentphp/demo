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

enum EmploymentType: string implements HasColor, HasIcon, HasLabel
{
    case FullTime = 'full_time';

    case PartTime = 'part_time';

    case Contractor = 'contractor';

    case Intern = 'intern';

    public function getLabel(): string
    {
        return match ($this) {
            self::FullTime => 'Full Time',
            self::PartTime => 'Part Time',
            self::Contractor => 'Contractor',
            self::Intern => 'Intern',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::FullTime => 'success',
            self::PartTime => 'info',
            self::Contractor => 'warning',
            self::Intern => 'gray',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::FullTime => FilamentIcon::resolve(DemoIconAlias::ENUMS_EMPLOYMENT_TYPE_FULL_TIME) ?? Heroicon::UserGroup,
            self::PartTime => FilamentIcon::resolve(DemoIconAlias::ENUMS_EMPLOYMENT_TYPE_PART_TIME) ?? Heroicon::Clock,
            self::Contractor => FilamentIcon::resolve(DemoIconAlias::ENUMS_EMPLOYMENT_TYPE_CONTRACTOR) ?? Heroicon::Briefcase,
            self::Intern => FilamentIcon::resolve(DemoIconAlias::ENUMS_EMPLOYMENT_TYPE_INTERN) ?? Heroicon::AcademicCap,
        };
    }
}
