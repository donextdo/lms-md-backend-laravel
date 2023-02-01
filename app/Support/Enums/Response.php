<?php

namespace App\Support\Enums;

use App\Support\Traits\Collectable;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self server_error()
 * @method static self not_found()
 * @method static self data_error()
 */
class Response extends Enum
{
    use Collectable;

    protected static function values(): array
    {
        return [
            'server_error' => 'server error',
            'not_found' => 'not found',
            'data_error' => 'data error',
        ];
    }
}
