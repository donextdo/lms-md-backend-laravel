<?php

namespace App\Modules\Session\Repositories\Interfaces;

use App\Models\Session;

interface SessionRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Session;

    public function get(int $id): Session;

    public function update(int $id, array $data): Session;

    public function delete(int $id);

    public function upcoming(array $data);

    public function recording(int $id, array $data);

    public function getSessionNotes(int $id);

    public function renew();
}
