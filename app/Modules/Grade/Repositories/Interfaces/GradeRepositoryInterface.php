<?php

namespace App\Modules\Grade\Repositories\Interfaces;

use App\Models\Grade;

interface GradeRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Grade;

    public function get(int $id): Grade;

    public function update(int $id, array $data): Grade;

    public function delete(int $id);
}
