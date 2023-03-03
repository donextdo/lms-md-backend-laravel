<?php

namespace App\Modules\Student\Services;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Student;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\SessionCollection;
use App\Http\Resources\StudentDataCollection;
use App\Modules\Student\Repositories\Interfaces\StudentRepositoryInterface as StudentRepository;
use App\Support\Enums\Response;
use Exception;
use App\Http\Resources\StudentInboxCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Http\UploadedFile;

class StudentService
{
    public function __construct(protected StudentRepository $studentRepository)
    {
        
    }

    public function create(array $data): Student
    {
        try {
         
            DB::beginTransaction();
            $student = $this->studentRepository->create($data);
            DB::commit();
            return new Student($student);
        } catch (Exception $e) { 
            DB::rollBack();
            abort(500, $e);
        }
    }

    public function get(int $id): Student
    {
        try {
            $student = $this->studentRepository->get($id);

            return new Student($student);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Student
    {
        try {
            $student = $this->studentRepository->update($id, $data);

            return new Student($student);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {dd($e);
            abort(500, Response::server_error());
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->studentRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
    public function approveStudent(int $id,array $data)
    {
        try {
            return $this->studentRepository->approveStudent($id,$data);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {dd($e);
            abort(500, Response::server_error());
        }
    }
    public function all(array $data)
    {
        try {
            $students = $this->studentRepository->all($data);

            return new StudentCollection($students);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
    public function block(int $id)
    {
        try {
            $students = $this->studentRepository->block($id);

            return $students;
        } catch (Exception $e) {

            abort(500, Response::server_error());
        } 
    }

    public function activate(int $id)
    {
        try {
            $students = $this->studentRepository->activate($id);

            return $students;
        } catch (Exception $e) {

            abort(500, Response::server_error());
        } 
    }

    public function shiftSession(int $class,int $student)
    {
        try {
            $students = $this->studentRepository->shiftSession($class,$student);

            return $students;
        } catch (Exception $e) {
            abort(500, Response::server_error());
        } 
    }

    public function sessionStatus(int $class,int $student)
    {
        try {
            $students = $this->studentRepository->sessionStatus($class,$student);

            return $students;
        } catch (Exception $e) {dd($e);
            abort(500, Response::server_error());
        } 
    }

    public function studentCount()
    {
        try {
            $studentCount = $this->studentRepository->studentCount();

            return $studentCount;
        } catch (Exception $e) {

            abort(500, Response::server_error());
        } 
    }

    public function requests(array $data)
    {
        try {
            $students = $this->studentRepository->requests($data);

            return new StudentCollection($students);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
    public function requestDetails(int $id)
    {
        try {
            $requestDetails = $this->studentRepository->requestDetails($id);

            return new StudentDataCollection($requestDetails);
        } catch (Exception $e) {

            abort(500, Response::server_error());
        } 
    }
    public function studentClasses(array $data)
    {
        try 
        {
            $classes = $this->studentRepository->studentClasses(Auth::user()->id,$data);
            if($classes)
            {
                return new SessionCollection($classes);
            }
            else
            {
                return "invalid Entry";
            }
        } catch (Exception $e) {
            dd($e);
            abort(500, Response::server_error());
        }
    }

    public function studentUpcomingClasses(array $data)
    {
        try 
        {
            $classes = $this->studentRepository->studentUpcomingClasses(Auth::user()->id,$data);
            if($classes)
            {
                return new SessionCollection($classes);
            }
            else
            {
                return "invalid Entry";
            }
        } catch (Exception $e) {
            dd($e);
            abort(500, Response::server_error());
        }
    }

    public function studentPastClasses(array $data)
    {
        try 
        {
            $classes = $this->studentRepository->studentPastClasses(Auth::user()->id,$data);
            if($classes)
            {
                return new SessionCollection($classes);
            }
            else
            {
                return "invalid Entry";
            }
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
    public function studentInbox()
    {
        try {
            $messages= $this->studentRepository->studentInbox(Auth::user()->id);  
            return new StudentInboxCollection($messages);
        } catch (Exception $e) {

            abort(500, Response::server_error());
        }
     
    }
}
