<?php

namespace App\Modules\Country\Repositories;

use App\Models\Country;
use App\Modules\Country\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class CountryRepository implements CountryRepositoryInterface
{
    public function __construct(protected Country $country)
    {
    }
    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->country::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->country::all();
    }

    public function get(int $id): Country
    {
        return $this->getCountryById($id);
    }
    private function getCountryById(int $id): ?Country
    {
        return $this->country::findOrFail($id);
    }

  
}
