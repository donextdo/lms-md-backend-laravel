<?php

namespace App\Modules\Country\Services;

use App\Http\Resources\Country;
use App\Http\Resources\CountryCollection;
use App\Modules\Country\Repositories\Interfaces\CountryRepositoryInterface as CountryRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CountryService
{
    public function __construct(protected CountryRepository $countryRepository)
    {
    }

 

    public function get(int $id): Country
    {
        try {
            $country = $this->countryRepository->get($id);

            return new Country($country);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
    public function all(array $data)
    {
        try {
            $country = $this->countryRepository->all($data);

            return new CountryCollection($country);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
}
