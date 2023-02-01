<?php

namespace App\Modules\Subject\Repositories\Interfaces;

use App\Models\Subject;

interface SubjectRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Subject;

    public function get(int $id): Subject;

    public function update(int $id, array $data): Subject;

    public function delete(int $id);
}
