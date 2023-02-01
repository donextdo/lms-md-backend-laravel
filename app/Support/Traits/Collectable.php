<?php

namespace App\Support\Traits;

use Illuminate\Support\Collection;

trait Collectable
{
    /** @return Collection<static> */
    public static function all(): Collection
    {
        return collect(self::cases());
    }
}
