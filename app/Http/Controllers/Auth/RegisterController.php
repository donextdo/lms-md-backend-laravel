<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function register(Request $request): JsonResponse
    {

       // $validator = $this->validateRegister($request->all());

       // if ($validator->fails()) {
       //     return $this->sendError('Validation Error.', $validator->errors());
     //   }

        $response = $this->authService->register($request->all());

        return $this->sendResponse($response, 'User registered successfully.');
    }

    public function resetPassword(Request $request)
    {
      $validatedRequest=  $request->validate([
        'password' => ['required', 'string', 'min:8', 'confirmed']
      ]);
      $response = $this->authService->resetPassword($validatedRequest);
      return $this->sendResponse($response, 'password reset  successfully.');

    }

    protected function validateRegister(array $data): ValidationValidator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact_no' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8', 'max:12'],
            'date_of_birth' => ['required', 'date_format:Y-m-d', 'before:today'],
        ]);
    }
}
