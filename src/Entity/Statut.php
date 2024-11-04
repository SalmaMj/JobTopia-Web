<?php

namespace App\Entity;

use MyCLabs\Enum\Enum;

class Statut extends Enum
{
    const EN_ATTENTE = 'EN_ATTENTE';
    const ACCEPTED = 'ACCEPTED';
    const REFUSED = 'REFUSED';

    public static function fromValue(string $value): self
    {
        if (!static::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid value "%s" for enum "%s"', $value, static::class));
        }

        return static::$value();
    }
}
