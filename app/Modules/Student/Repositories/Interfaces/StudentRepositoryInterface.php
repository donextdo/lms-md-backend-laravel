<?php

namespace App\Modules\Student\Repositories\Interfaces;

use App\Models\Student;

interface StudentRepositoryInterface
{
    public function all(array $data);

    public function create(array $data): Student;

    public function get(int $id);

    public function update(int $id, array $data): Student;

    public function delete(int $id);

    public function block( int $id);

    public function activate( int $id);

    public function studentCount();

    public function studentClasses(int $id,array $data);

    public function studentPastClasses(int $id,array $data);

    public function studentUpcomingClasses(int $id,array $data);

    public function requests(array $data);

    public function requestDetails(int $id);

    public function studentInbox(int $id);

    public function uploadIt(int $id,array $data);

    public function approveStudent(int $id,array $data);

    public function shiftSession( int $class,int $student);

    public function sessionStatus( int $class,int $student);


}
