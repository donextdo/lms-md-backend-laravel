<?php

namespace App\Modules\Session_email\Repositories\Interfaces;

use App\Models\Session_email;

interface Session_emailRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Session_email;

    public function get(int $id): Session_email;

    public function update(int $id, array $data): Session_email;

    public function delete(int $id);
}
