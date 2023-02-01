<?php

namespace App\Modules\Tutor\Repositories\Interfaces;

use App\Models\Tutor;

interface TutorRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Tutor;

    public function get(int $id): Tutor;

    public function update(int $id, array $data): Tutor;

    public function delete(int $id);

    public function tutorClasses(int $userId,array $data);

    public function studentCountPerTutor(int $id); 

    public function sessionCountPerTutor(int $id); 

    public function upcomingPerTutor(int $id,array $data);



}
