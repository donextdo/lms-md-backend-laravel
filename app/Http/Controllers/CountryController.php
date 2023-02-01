<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Country\Services\CountryService;
use App\Support\Enums\Response;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function __construct(protected CountryService $countryService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->countryService->all($request->query());

        return $this->sendResponse($response, 'Countries shown successfully.');
    }

    public function get($id): JsonResponse
    {
        $response = $this->countryService->get($id);

        return $this->sendResponse($response, null);
    }
}
