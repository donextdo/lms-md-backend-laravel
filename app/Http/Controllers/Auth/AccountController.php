<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function resetPassword(Request $request): JsonResponse
    {
      $validatedRequest=  $request->validate([
        'password' => ['required', 'string', 'min:8', 'confirmed']
      ]);
     $response = $this->userService->resetPassword($validatedRequest);
     return $this->sendResponse($response, 'password reset  successfully.');

    }

    public function resetInfo(Request $request): JsonResponse
    {
      $validatedRequest=  $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'email' => ['required', 'string', 'email', 'max:100'],
        'contact_no' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8', 'max:12'],      ]);
      $response = $this->userService->resetInfo($validatedRequest);
      return $this->sendResponse($response, 'Info reset  successfully.');

    }
    public function view()
    {
      return $this->sendResponse($this->userService->view(), 'Info shown  successfully.');
      

    }
}
