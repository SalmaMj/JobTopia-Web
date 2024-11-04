<?php

namespace App\Entity;
use MyCLabs\Enum\Enum;


class Categorie extends Enum{
    public const DESIGN = 'DESIGN';
    public const WEB = 'WEB';
    public const MOBILE = 'MOBILE';
    public const TRADUCTION = 'TRADUCTION';
    public const PHOTOGRAPHY = 'PHOTOGRAPHY';

    public static function getValues(): array
    {
        return [
            self::DESIGN,
            self::WEB,
            self::MOBILE,
            self::TRADUCTION,
            self::PHOTOGRAPHY,
        ];
    }
}
