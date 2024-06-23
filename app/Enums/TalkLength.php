<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Traits\GetEnumAttributes;

enum TalkLength: string
{
    use GetEnumAttributes;

    #[Description('Normal - 30 minutes')]
    case NORMAL = 'N';

    #[Description('Lightning - 15 minutes')]
    case LIGHTNING = 'L';

    #[Description('Keynote Talk')]
    case KEYNOTE = 'K';

    public function getColor(): string
    {
        return match ($this) {
            self::KEYNOTE => 'primary',
            self::LIGHTNING => 'danger',
            self::NORMAL => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::KEYNOTE => 'heroicon-o-key',
            self::LIGHTNING => 'heroicon-o-bolt',
            self::NORMAL => 'heroicon-o-megaphone',
        };
    }
}
