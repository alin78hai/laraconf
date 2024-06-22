<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Traits\GetEnumAttributes;

enum Region: string
{
    use GetEnumAttributes;

    #[Description('United States')]
    case US = 'US';

    #[Description('Europe')]
    case EU = 'EU';

    #[Description('Australia')]
    case AU = 'AU';

    #[Description('India')]
    case IN = 'IN';

    #[Description('Online')]
    case ONLINE = 'OL';

    public function getRegion(): string
    {
        return match ($this) {
            self::US => 'United States',
            self::EU => 'Europe',
            self::AU => 'Australia',
            self::IN => 'India',
            self::ONLINE => 'Online',
        };
    }
}
