<?php

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

it('can validate the email on user login', function ($email, string $message) {
    $user = [
        'email' => $email,
    ];

    $response = $this->postJson(route('login'), $user);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('email', [$message])
                    ->etc())
        );
})->with([
    ['', 'The email field is required.'],
    [123, 'The email must be a string.'],
]);

it('can validate the password on user login', function ($password, string $message) {
    $user = [
        'password' => $password,
    ];

    $response = $this->postJson(route('login'), $user);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('password', [$message])
                    ->etc())
        );
})->with([
    ['', 'The password field is required.'],
    [123, 'The password must be a string.'],
]);

it('can validate the user credentials on login', function (string $email, string $password) {
    User::factory()->create(['email' => 'foo@test.com', 'password' => 'foobar123']);
    $user = ['email' => $email, 'password' => $password];

    $response = $this->postJson(route('login'), $user);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('email', ['These credentials do not match our records.'])
                    ->etc())
        );
})->with([
    ['foo123@test.com', 'foobar123'],
    ['foo@test.com', 'foo123'],
]);

it('can validate the user credentials on successful login', function (string $email, string $password) {
    $this->artisan('passport:install', ['--no-interaction' => true]);
    User::factory()->create(['email' => $email, 'password' => Hash::make($password)]);

    $user = ['email' => $email, 'password' => $password];

    $response = $this->postJson(route('login'), $user);

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'User logged in successfully.')
                ->where('success', true)
                ->has('data', fn ($json) => $json
                    ->where('email', $email)
                    ->etc())
        );
})->with([
    ['foo@test.com', 'foobar123'],
]);

it('can lockout account for decay time on unsuccessful max attempts', function (string $email, string $password) {
    User::factory()->create(['email' => $email, 'password' => Hash::make($password)]);

    $controller = resolve(LoginController::class);

    $user = ['email' => $email, 'password' => 'Foo123'];

    for ($i = 0; $i < $controller->maxAttempts(); $i++) {
        $response = $this->postJson(route('login'), $user);
    }

    $response = $this->postJson(route('login'), $user);

    $response->assertStatus(429)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('email', ['Too many login attempts. Please try again in ' . $controller->decayMinutes() * 60 . ' seconds.'])
                    ->etc())
        );
})->with([
    ['foo@test.com', 'foobar123'],
]);
