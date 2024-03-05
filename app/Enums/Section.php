<?php

namespace App\Enums;


class Section
{
    const IN = 'in';
    const OUT = 'out';
    const RULES = 'rules';
    const ALL = 'all';

    public static function toArray(): array
    {
        // Return an associative array with enum values as keys and values
        return [
            self::IN => 'IN',
            self::OUT => 'OUT',
            self::RULES => 'Rules',
            self::ALL => 'All',
        ];
    }
}
