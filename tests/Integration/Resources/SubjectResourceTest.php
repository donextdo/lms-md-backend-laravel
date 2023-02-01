<?php

use App\Http\Resources\Subject as ResourcesSubject;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can return subject resource', function () {
    $subject = Subject::factory()->make();

    $subjectResource = (new ResourcesSubject($subject))->resolve();

    $this->assertCount(3, $subjectResource);

    expect($subjectResource)->toBeArray();
    expect($subjectResource['name'])->toEqual($subject->name);
    expect($subjectResource['slug'])->toEqual($subject->slug);
});
