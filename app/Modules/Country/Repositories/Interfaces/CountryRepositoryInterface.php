<?php

namespace App\Modules\Country\Repositories\Interfaces;

use App\Models\Country;

interface CountryRepositoryInterface
{
    public function all(array $data);
    public function get(int $id): Country;
}
