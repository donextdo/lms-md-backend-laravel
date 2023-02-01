<?php

namespace App\Modules\Class\Repositories\Interfaces;

use App\Models\Class1;

interface ClassRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Class1;

    public function get(int $id): Class1;

    public function update(int $id, array $data): Class1;

    public function delete(int $id);

    public function classesPerSubject(int $id,array $data);

    public function sessionClass(int $id,array $data);

    public function classCount();

}
