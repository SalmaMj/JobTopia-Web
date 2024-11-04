<?php

namespace App\Entity;
use MyCLabs\Enum\Enum;


class Role extends Enum{
    public const CLIENT = 'CLIENT';
    public const ADMIN = 'ADMIN';
    public const FREELANCER = 'FREELANCER';
}
