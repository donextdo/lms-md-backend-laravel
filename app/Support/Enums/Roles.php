<?php

namespace App\Support\Enums;

use App\Support\Traits\Collectable;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self admin()
 * @method static self tutor()
 * @method static self student()
 */
class Roles extends Enum
{
    use Collectable;

    protected static function values(): array
    {
        return [
            'admin' => 1,
            'tutor' => 2,
            'student' => 3,
        ];
    }

    public static function getRole(int $value)
    {
        return match ($value) {
            self::admin()->value => self::admin(),
            self::tutor()->value => self::tutor(),
            self::student()->value => self::student(),
        };
    }
}
