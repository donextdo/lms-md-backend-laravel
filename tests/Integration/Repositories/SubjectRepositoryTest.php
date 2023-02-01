<?php

use App\Models\Subject;
use App\Modules\Subject\Repositories\Interfaces\SubjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

it('can store subject', function () {
    $subject = Subject::factory()->raw();

    $modelSubject = resolve(SubjectRepositoryInterface::class)->create($subject);

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    expect($modelSubject)
        ->toBeInstanceOf(Subject::class)
        ->id->toBeInt
        ->name->toEqual($subject['name'])
        ->slug->toEqual($subject['slug']);
});

it('throws an exception if given subject is not found', function () {
    $subject = Subject::factory()->create();

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    $this->expectException(ModelNotFoundException::class);

    resolve(SubjectRepositoryInterface::class)->get(2);
});

it('can fetch a subject by given id', function () {
    $subject = Subject::factory()->create();

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    $modelSubject = resolve(SubjectRepositoryInterface::class)->get($subject->id);

    expect($modelSubject)
        ->toBeInstanceOf(Subject::class)
        ->id->toBeInt
        ->name->toEqual($subject['name'])
        ->slug->toEqual($subject['slug']);
});

it('can fetch subjects without pagination', function () {
    Subject::factory()->count(15)->create();

    $subjectCollection = resolve(SubjectRepositoryInterface::class)->all([]);

    $this->assertDatabaseCount('subjects', 15);

    expect($subjectCollection)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(15);
    expect($subjectCollection->first())
        ->toBeInstanceOf(Subject::class)
        ->name->toEqual('music')
        ->slug->toEqual('music');
});

it('can fetch subjects with pagination', function () {
    Subject::factory()->count(15)->create();

    $subjectCollection = resolve(SubjectRepositoryInterface::class)->all(['paginate' => true, 'per-page' => 10]);

    $this->assertDatabaseCount('subjects', 15);

    expect($subjectCollection)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->toHaveCount(10)
        ->total()->toEqual(15)
        ->count()->toEqual(10)
        ->perPage()->toEqual(10)
        ->currentPage()->toEqual(1)
        ->lastPage()->toEqual(2);

    expect($subjectCollection->first())
        ->toBeInstanceOf(Subject::class)
        ->name->toEqual('music')
        ->slug->toEqual('music');
});

it('throws an exception if given subject to update is not found', function () {
    $subject = Subject::factory()->create()->toArray();

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    $this->expectException(ModelNotFoundException::class);

    resolve(SubjectRepositoryInterface::class)->update(2, $subject);
});

it('can update the subject of given id', function () {
    $subject = Subject::factory()->create(['name' => 'music'])->toArray();

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    $subject['name'] = 'dancing';

    $modelSubject = resolve(SubjectRepositoryInterface::class)->update($subject['id'], $subject);

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    expect($modelSubject)
        ->toBeInstanceOf(Subject::class)
        ->id->toBeInt
        ->name->toEqual($subject['name'])
        ->slug->toEqual($subject['slug']);
});

it('throws an exception if given subject to delete is not found', function () {
    $subject = Subject::factory()->create()->toArray();

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    $this->expectException(ModelNotFoundException::class);

    resolve(SubjectRepositoryInterface::class)->delete(2);
});

it('can delete the subject of given id', function () {
    $subject = Subject::factory()->create(['name' => 'music'])->toArray();

    $this->assertDatabaseHas('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);

    resolve(SubjectRepositoryInterface::class)->delete($subject['id']);

    $this->assertSoftDeleted('subjects', [
        'name' => $subject['name'],
        'slug' => $subject['slug'],
    ]);
});
