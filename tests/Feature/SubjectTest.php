<?php

use App\Models\Subject;
use App\Models\User;
use App\Support\Enums\Response;
use App\Support\Enums\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

uses(RefreshDatabase::class);

it('can validate the name on create subject', function (array $subject, string $message) {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));
    $response = $this->postJson(route('admin.subject.create'), $subject);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', Response::data_error()->value)
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('name', [$message])
                    ->etc())
        );
})->with([
    [fn () => Subject::factory()->make(['name' => ''])->toArray(), 'The name field is required.'],
    [fn () => Subject::factory()->make(['name' => 123])->toArray(), 'The name must be a string.'],
    [fn () => Subject::factory()->make(['name' => Str::random(55)])->toArray(), 'The name must not be greater than 50 characters.'],
]);

it('can create subject', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    $subject = Subject::factory()->make(['name' => 'Music Dancing'])->toArray();

    $response = $this->postJson(route('admin.subject.create'), $subject);

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Subject created successfully.')
                ->where('success', true)
                ->has('data', fn ($json) => $json
                    ->where('name', 'Music Dancing')
                    ->where('slug', 'music-dancing')
                    ->etc())
        );
});

it('can retrieve a subject for given id', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    Subject::factory()->create(['name' => 'music']);

    $response = $this->get(route('admin.subject.get', ['id' => 1]));

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', null)
                ->where('success', true)
                ->has('data', fn ($json) => $json
                    ->where('name', 'music')
                    ->etc())
        );
});

it('will return 404 if subject not found', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));
    $this->expectException(NotFoundHttpException::class);

    $response = $this->get(route('admin.subject.get', ['id' => 1]));

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', Response::not_found())
        );
});

it('can retrieve all subjects with pagination', function (int $total, array $pagination) {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    Subject::factory()->count($total)->create(['name' => 'music']);

    $response = $this->get(route('admin.subject.index', $pagination));

    $response->assertStatus(200)
        ->assertJson(
            function (AssertableJson $json) use ($pagination, $total) {
                $json
                    ->where('message', 'Subjects shown successfully.')
                    ->where('success', true)
                    ->has('data', function ($data) use ($pagination, $total) {
                        $data->has('subjects');
                        $data->where('subjects.0.name', 'music');
                        $data->where('subjects.0.slug', 'music');
                        $data->has('meta', function ($meta) use ($pagination, $total) {
                            $meta->where('total', $total);
                            $meta->has('count');
                            $meta->where('per_page', $pagination['per-page']);
                            $meta->where('current_page', $pagination['page']);
                            $meta->where('total_pages', (int) ceil($total / $pagination['per-page']));
                        });
                    })->has(3);
            }
        );
})->with([
    ['total' => 15, fn () => ['paginate' => true, 'page' => 1, 'per-page' => 10]],
    ['total' => 10, fn () => ['paginate' => true, 'page' => 1, 'per-page' => 15]],
    ['total' => 15, fn () => ['paginate' => true, 'page' => 2, 'per-page' => 10]],
]);

it('can retrieve all subjects without pagination', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    Subject::factory()->count(5)->create(['name' => 'music']);

    $response = $this->get(route('admin.subject.index'));

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Subjects shown successfully.')
                ->where('success', true)
                ->has('data', function ($json) {
                    $json->has('subjects');
                })->has(3)
        );
});

it('can validate the name on update subject', function (array $subject, string $message) {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    $response = $this->putJson(route('admin.subject.update', ['id' => 1]), $subject);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', Response::data_error()->value)
                ->where('success', false)
                ->has('data', fn ($json) => $json
                    ->where('name', [$message])
                    ->etc())
        );
})->with([
    [fn () => Subject::factory()->make(['name' => ''])->toArray(), 'The name field is required.'],
    [fn () => Subject::factory()->make(['name' => 123])->toArray(), 'The name must be a string.'],
    [fn () => Subject::factory()->make(['name' => Str::random(55)])->toArray(), 'The name must not be greater than 50 characters.'],
]);

it('will return 404 if the subject to be updated is not found', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));
    $subject = Subject::factory()->make(['name' => 'Music Dancing'])->toArray();

    $response = $this->putJson(route('admin.subject.update', ['id' => 1]), $subject);

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', Response::not_found()->value)
        );
});

it('can update subject', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));
    Subject::factory()->create(['name' => 'Music']);
    $subject = Subject::factory()->make(['name' => 'Music Dancing'])->toArray();

    $response = $this->putJson(route('admin.subject.update', ['id' => 1]), $subject);

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Subject updated successfully.')
                ->where('success', true)
                ->has('data', fn ($json) => $json
                    ->where('name', 'Music Dancing')
                    ->where('slug', 'music')
                    ->etc())
        );
});

it('will return 404 if the subject to be deleted is not found', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));

    $response = $this->deleteJson(route('admin.subject.delete', ['id' => 1]));

    $response->assertStatus(404)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', Response::not_found()->value)
        );
});

it('can delete subject', function () {
    Passport::actingAs(User::factory()->make(['role_id' => Roles::admin()->value]));
    Subject::factory()->create(['name' => 'Music']);

    $response = $this->deleteJson(route('admin.subject.delete', ['id' => 1]));

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', 'Subject deleted successfully.')
                ->where('success', true)
                ->where('data', true)
        );
});
