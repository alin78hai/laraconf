<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Traits\GetEnumAttributes;

enum TalkStatus: string
{
    use GetEnumAttributes;

    #[Description('Submitted')]
    case SUBMITTED = 'S';

    #[Description('Approved')]
    case APPROVED = 'A';

    #[Description('Rejected')]
    case REJECTED = 'R';

    public function getColor(): string
    {
        return match ($this) {
            self::SUBMITTED => 'primary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }
}
