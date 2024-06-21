<?php

namespace App\Traits;

use Illuminate\Support\Str;
use ReflectionClassConstant;
use App\Enums\Attributes\Description;

trait GetEnumAttributes
{
    /**
     * @param self $enum
     */
    private static function getDescription(self $enum): string
    {
        $ref = new ReflectionClassConstant(self::class, $enum->name);
        $classAttributes = $ref->getAttributes(Description::class);

        if (count($classAttributes) === 0) {
            return Str::headline($enum->value);
        }

        return $classAttributes[0]->newInstance()->description;
    }

    /**
     * @return array<string,string>
     */
    public static function asSelectArray(): array
    {
        /** @var array<string,string> $values */
        $values = collect(self::cases())
            ->mapWithKeys(function ($enum) {
                return [
                    $enum->value => self::getDescription($enum),
                ];
            })
            ->toArray();

        return $values;
    }
}
