<?php

namespace App\Modules\User\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function all();

    public function create(array $data): User;

    public function get(int $id): User;

    public function updatePassword(int $id, array $data);

    public function updateInfo(int $id, array $data);

    public function delete(int $id);
}
