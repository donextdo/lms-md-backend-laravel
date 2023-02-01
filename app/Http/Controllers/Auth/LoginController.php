<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected int $maxAttempts = 10;

    protected int $decayMinutes = 1;

    public function __construct(protected AuthService $authService)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $validator = $this->validateLogin($request->all());

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->authService->login($request->all())) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
    }

    protected function validateLogin(array $data): ValidationValidator
    {
        return Validator::make($data, [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    }

    protected function sendLoginResponse(Request $request): JsonResponse
    {
        $response = new AuthUserResource(Auth::user());

        $this->clearLoginAttempts($request);

        return $this->sendResponse($response, 'User logged in successfully.');
    }

    protected function sendFailedLoginResponse(): JsonResponse
    {
        return $this->sendError('Validation Error.', ['email' => [trans('auth.failed')]]);
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts()
        );
    }

    protected function fireLockoutEvent(Request $request): void
    {
        event(new Lockout($request));
    }

    protected function sendLockoutResponse(Request $request): JsonResponse
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return $this->sendError('Validation Error.', [
            'email' => [trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }

    protected function incrementLoginAttempts(Request $request): void
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes() * 60
        );
    }

    protected function clearLoginAttempts(Request $request): void
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    public function maxAttempts(): int
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    public function decayMinutes(): int
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }
}
