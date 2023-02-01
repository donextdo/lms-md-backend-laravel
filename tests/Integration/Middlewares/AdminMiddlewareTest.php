<?php

use App\Http\Middleware\Admin;
use App\Models\User;
use App\Support\Enums\Roles;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

beforeEach(function () {
    Route::get('foo', ['as' => 'foo-bar', fn () => 'Hello World'])->middleware([Admin::class]);
});

it('will reject unauthorized requests for admin routes', function (int $roleId) {
    Passport::actingAs(User::factory()->make(['role_id' => $roleId]));

    $response = resolve(Admin::class)->handle(request(), fn () => null);

    expect($response->getStatusCode())->toBe(401);

    expect($response->getData())
        ->success->toBeFalse
        ->message->toEqual('Access denied');
})->with([
   'tutor' => Roles::tutor()->value,
   'student' => Roles::student()->value,
]);

it('will allow authorized requests for admin routes', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    $response = resolve(Admin::class)->handle(request(), fn () => null);

    $this->assertEquals($response, null);
});
