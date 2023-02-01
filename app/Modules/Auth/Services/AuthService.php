<?php

namespace App\Modules\Auth\Services;
use App\Support\Enums\Roles;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use App\Modules\User\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TokenRepository $tokenRepository
    ) {
    }

    public function register(array $data): AuthUserResource
    {   
        try {
            event(new Registered($user = $this->userRepository->create($data)));

            $this->guard()->login($user);

            return new AuthUserResource($user);
        } catch (Exception $e) {
            abort(500);
        }
    }
    public function login(array $data)
    {
        try {
            return $this->attemptLogin($data);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function logout(User $user)
    {
        try {
            $token = $user->token();
            $this->tokenRepository->revokeAccessToken($token->id);
        } catch (Exception $e) {
            abort(500);
        }
    }

    protected function attemptLogin(array $data)
    {
        return $this->guard()->attempt($this->credentials($data));
    }

    protected function credentials(array $data): array
    {
        return ['email' => $data['email'], 'password' => $data['password']];
    }

    protected function guard(): Guard
    {
        return Auth::guard();
    }
}
