<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

it('can validate the name on user registration', function (array $user, string $message) {
    $response = $this->postJson(route('register'), $user);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('name', [$message])
                    ->etc())
        );
})->with([
    [fn () => User::factory()->make(['name' => ''])->toArray(), 'The name field is required.'],
]);

it('can validate the email on user registration', function (array $user, string $message) {
    $response = $this->postJson(route('register'), $user);

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
    [fn () => User::factory()->make(['email' => ''])->toArray(), 'The email field is required.'],
    [fn () => User::factory()->make(['email' => 'foo123'])->toArray(), 'The email must be a valid email address.'],
]);

it('can validate the uniqueness of email on user registration', function () {
    User::factory()->create(['email' => 'foo@test.com']);
    $user = User::factory()->raw();
    $user['email'] = 'foo@test.com';

    $response = $this->postJson(route('register'), $user);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('email', ['The email has already been taken.'])
                    ->etc())
        );
});

it('can validate the password on user registration', function (User $user, string $message) {
    $response = $this->postJson(route('register'), $user->toArray());

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
    [fn () => User::factory()->make(['password' => '']), 'The password field is required.'],
]);

it('can validate the confirm password on user registration', function (string $password, string $confirmPassword, string $message) {
    $user = User::factory()->raw();
    $user['password'] = $password;
    $user['password_confirmation'] = $confirmPassword;

    $response = $this->postJson(route('register'), $user);

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
    ['foo', 'foo', 'The password must be at least 8 characters.'],
    ['foo12345', 'foo', 'The password confirmation does not match.'],
]);

it('can validate the contact number on user registration', function (User $user, string $message) {
    $response = $this->postJson(route('register'), $user->toArray());

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('contact_no', [$message])
                    ->etc())
        );
})->with([
    [fn () => User::factory()->create(['contact_no' => '']), 'The contact no field is required.'],
    [fn () => User::factory()->create(['contact_no' => 'foobar1234']), 'The contact no format is invalid.'],
    [fn () => User::factory()->create(['contact_no' => '123']), 'The contact no must be at least 8 characters.'],
    [fn () => User::factory()->create(['contact_no' => '123123423231232']), 'The contact no must not be greater than 12 characters.'],
]);

it('can validate the date of birth on user registration', function (User $user, string $message) {
    $response = $this->postJson(route('register'), $user->toArray());

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Validation Error.')
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('date_of_birth', [$message])
                    ->etc())
        );
})->with([
    [fn () => User::factory()->create(['date_of_birth' => '']), 'The date of birth field is required.'],
    [fn () => User::factory()->create(['date_of_birth' => now()->addDays(1)->format('Y-m-d')]), 'The date of birth must be a date before today.'],
]);

it('can register a new user', function () {
    $this->artisan('passport:install', ['--no-interaction' => true]);
    $user = User::factory()->raw();
    $user['password_confirmation'] = $user['password'];

    $response = $this->postJson(route('register'), $user);

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', fn ($json) => $json
                    ->where('name', $user['name'])
                    ->where('email', $user['email'])
                    ->missing('password')
                    ->has('token')
                    ->etc())
                ->where('message', 'User registered successfully.')
                ->where('success', true)
        );
});
