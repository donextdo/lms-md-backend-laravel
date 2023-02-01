<?php

namespace App\Modules\Class\Services;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\Class1;
use App\Http\Resources\Class1Collection;
use App\Http\Resources\SessionCollection;
use App\Http\Resources\StudentCollection;
use App\Modules\Class\Repositories\Interfaces\ClassRepositoryInterface as ClassRepository;
use App\Modules\Session\Repositories\Interfaces\SessionRepositoryInterface as SessionRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassService
{
    public function __construct(protected ClassRepository $classRepository,protected SessionRepository $sessionRepository)
    {
    }

    public function create(array $data): Class1
    {
        try {
            DB::beginTransaction();
            $class = $this->classRepository->create($data);
            $data['class1_id']=$class->id;
            $this->sessionRepository->firstSession($data);
            DB::commit();
            return new Class1($class);
        } catch (Exception $e) {
            DB::rollBack();
           abort(500, $e);
        }
    }

    public function get(int $id): Class1
    {
        try {
            $class = $this->classRepository->get($id);

            return new Class1($class);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Class1
    {
        try {
            $class = $this->classRepository->update($id, $data);

            return new Class1($class);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->classRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function all(array $data)
    {
        try {
            $class = $this->classRepository->all($data);
            return new Class1Collection($class);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function classesPerSubject(int $id,array $data)
    {
        try {
            $class = $this->classRepository->classesPerSubject($id,$data);
            return new Class1Collection($class);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function sessionClass(int $id,array $data)
    {
        try {
            $class = $this->classRepository->sessionClass($id,$data);
            return new SessionCollection($class);
        } catch (Exception $e) {
            dd($e);
            abort(500, Response::server_error());
        }
    }

    public function studentClass(int $id,array $data)
    {
        try {
            $class = $this->classRepository->studentClass($id,$data);
            return new StudentCollection($class);
        } catch (Exception $e) {
            dd($e);
            abort(500, Response::server_error());
        }
    }

    public function classCount()
    {
        try {
            $classCount = $this->classRepository->classCount();
            return $classCount;
        } catch (Exception $e) {
            dd($e);
            abort(500, Response::server_error());
        }
    }
}
