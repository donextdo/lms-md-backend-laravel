<?php

namespace App\Modules\Tutor\Services;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Tutor;
use App\Http\Resources\TutorCollection;
use App\Http\Resources\Class1Collection;
use App\Http\Resources\StudentCollection;
use App\Modules\Tutor\Repositories\Interfaces\TutorRepositoryInterface as TutorRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TutorService
{
    public function __construct(protected TutorRepository $tutorRepository)
    {
    }

    public function create(array $data): Tutor
    {
        try 
        {
            DB::beginTransaction();
            $tutor = $this->tutorRepository->create($data);
            DB::commit();
            return new Tutor($tutor);
        } catch (Exception $e) {dd($e);
            DB::rollback();
            abort(500, Response::server_error());
        }
    }

    public function get(int $id): Tutor
    {
        try 
        {
            $tutor = $this->tutorRepository->get($id);

            return new Tutor($tutor);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Tutor
    {
        try 
        {
            $tutor = $this->tutorRepository->update($id, $data);

            return new Tutor($tutor);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {dd($e);
            abort(500, Response::server_error());
        }
    }

    public function delete(int $id)
    {
        try 
        {
            return $this->tutorRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function all(array $data)
    {
        try 
        {
            $tutor = $this->tutorRepository->all($data);

            return new TutorCollection($tutor);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function tutorClasses(array $data)
    {
        try 
        {
            $classes = $this->tutorRepository->tutorClasses(Auth::user()->id,$data);
            if($classes)
            {
                return new Class1Collection($classes);
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

    public function tutorStudents(array $data)
    {
        try 
        {
            $students = $this->tutorRepository->tutorStudents(Auth::user()->id,$data);
            if($students)
            {
             return new StudentCollection($students);
            }
            else
            {
             return $students;
            }
        } catch (Exception $e)
        {
            abort(500, Response::server_error());
        }
    }

    public function studentCountPerTutor()
    {
        try 
        {
            $studentCount = $this->tutorRepository->studentCountPerTutor(Auth::user()->id);
            return $studentCount;
        } catch (Exception $e) 
        {
            abort(500, Response::server_error());
        }
    }
    public function sessionCountPerTutor()
    {
        try 
        {
            $sessionCount = $this->tutorRepository->sessionCountPerTutor(Auth::user()->id);
            return $sessionCount;
        } catch (Exception $e) 
        {  
            abort(500, Response::server_error());
        }
    }
    public function upcomingPerTutor(array $data)
    {
        try 
        {
            $upcoming = $this->tutorRepository->upcomingPerTutor(Auth::user()->id,$data);
            return $upcoming;
        } catch (Exception $e) 
        {  dd($e);
            abort(500, Response::server_error());
        }
    }

}
