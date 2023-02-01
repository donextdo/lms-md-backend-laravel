<?php

use App\Models\User;
use App\Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('it will store user', function () {
    $user = User::factory()->raw();
    $user['password'] = 'foobar123';

    $modelUser = resolve(UserRepositoryInterface::class)->create($user);

    $this->assertDatabaseHas('users', [
        'email' => $user['email'],
        'name' => $user['name'],
        'contact_no' => $user['contact_no'],
        'date_of_birth' => $user['date_of_birth'],
    ]);

    expect($modelUser)
        ->toBeInstanceOf(User::class)
        ->id->toBeInt
        ->name->toEqual($user['name'])
        ->email->toEqual($user['email'])
        ->contact_no->toEqual($user['contact_no'])
        ->date_of_birth->toEqual($user['date_of_birth']);
});
