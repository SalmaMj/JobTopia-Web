<?php

namespace App\Entity;
use MyCLabs\Enum\Enum;


class Etat extends Enum
{
    const DISPONIBLE = 'DISPONIBLE';
    const NON_DISPONIBLE = 'NON_DISPONIBLE';
    const ACCEPTED = 'ACCEPTED';
 

    public static function fromValue(string $value): Etat
    {
        if (!static::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid value "%s" for enum "%s"', $value, static::class));
        }

        return static::$value();
    }
}
