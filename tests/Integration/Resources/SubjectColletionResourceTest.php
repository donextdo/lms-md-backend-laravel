<?php

use App\Http\Resources\Subject as SubjectResource;
use App\Http\Resources\SubjectCollection;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can return subject resource', function () {
    $subjects = Subject::factory()->count(15)->make();

    $subjectResource = (new SubjectCollection($subjects))->resolve();

    expect($subjectResource)->toBeArray();

    expect($subjectResource['subjects'])->toHaveCount(15);
    expect($subjectResource['subjects'])->each()->toBeInstanceOf(SubjectResource::class);
});
