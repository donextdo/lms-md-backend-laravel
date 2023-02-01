<?php

use App\Http\Resources\Subject as ResourcesSubject;
use App\Http\Resources\SubjectCollection;
use App\Models\Subject;
use App\Modules\Subject\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Modules\Subject\Services\SubjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

uses(RefreshDatabase::class);

it('uses binded repository to create subject', function () {
    $subject = Subject::factory()->raw(['name' => 'music']);

    $modelSubject = Subject::factory()->make($subject);

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('create')
        ->once()
        ->withArgs(function (array $subject) {
            expect($subject)
                ->toBeArray()
                ->name->toEqual('music');

            return true;
        })->andReturn($modelSubject);

    $result = resolve(SubjectService::class)->create($subject);

    expect($result)
        ->toBeInstanceOf(ResourcesSubject::class)
        ->name->toEqual($modelSubject->name)
        ->slug->toEqual($modelSubject->slug);
});

it('uses binded repository to update subject', function () {
    $subject = Subject::factory()->raw(['name' => 'dancing']);

    $modelSubject = Subject::factory()->create();

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('update')
        ->once()
        ->withArgs(function (int $id, array $subject) use ($modelSubject) {
            expect($id)->toEqual($modelSubject->id);
            expect($subject)->toBeArray();

            return true;
        })->andReturn(Subject::factory()->make($subject));

    $result = resolve(SubjectService::class)->update($modelSubject->id, $subject);

    expect($result)
        ->toBeInstanceOf(ResourcesSubject::class)
        ->name->toEqual($subject['name'])
        ->slug->toEqual($subject['slug']);
});

it('can catch exception if subject to update is not found', function () {
    $subject = Subject::factory()->raw(['name' => 'dancing']);

    Subject::factory()->create();

    $this->expectException(NotFoundHttpException::class);

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('update')
        ->once()
        ->withArgs(function (int $id, array $subject) {
            expect($id)->toEqual(4);
            expect($subject)->toBeArray();

            return true;
        })->andThrow(ModelNotFoundException::class);

    resolve(SubjectService::class)->update(4, $subject);
});

it('uses binded repository to get subject', function () {
    $modelSubject = Subject::factory()->create();

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('get')
        ->once()
        ->withArgs(function (int $id) use ($modelSubject) {
            expect($id)->toEqual($modelSubject->id);

            return true;
        })->andReturn($modelSubject);

    $result = resolve(SubjectService::class)->get($modelSubject->id);

    expect($result)
        ->toBeInstanceOf(ResourcesSubject::class)
        ->name->toEqual($modelSubject['name'])
        ->slug->toEqual($modelSubject['slug']);
});

it('can catch exception if subject is not found', function () {
    Subject::factory()->create();

    $this->expectException(NotFoundHttpException::class);

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('get')
        ->once()
        ->withArgs(function (int $id) {
            expect($id)->toEqual(4);

            return true;
        })->andThrow(ModelNotFoundException::class);

    resolve(SubjectService::class)->get(4);
});

it('uses binded repository to delete subject', function () {
    $modelSubject = Subject::factory()->create();

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('delete')
        ->once()
        ->withArgs(function (int $id) use ($modelSubject) {
            expect($id)->toEqual($modelSubject->id);

            return true;
        })->andReturn(true);

    $result = resolve(SubjectService::class)->delete($modelSubject->id);

    expect($result)->isTrue;
});

it('can catch exception if subject to delete is not found', function () {
    Subject::factory()->create();

    $this->expectException(NotFoundHttpException::class);

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('delete')
        ->once()
        ->withArgs(function (int $id) {
            expect($id)->toEqual(4);

            return true;
        })->andThrow(ModelNotFoundException::class);

    resolve(SubjectService::class)->delete(4);
});

it('uses binded repository to fetch all subjects', function () {
    $modelSubject = Subject::factory()->count(15)->create();

    $this->mock(SubjectRepositoryInterface::class)
        ->shouldReceive('all')
        ->once()
        ->andReturn($modelSubject);

    $result = resolve(SubjectService::class)->all([]);

    expect($result)
        ->toBeInstanceOf(SubjectCollection::class)
        ->toHaveCount(15);
});
