<?php

namespace App\Modules\Grade\Services;

use App\Http\Resources\Grade;
use App\Http\Resources\GradeCollection;
use App\Modules\Grade\Repositories\Interfaces\GradeRepositoryInterface as GradeRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GradeService
{
    public function __construct(protected GradeRepository $gradeRepository)
    {
    }

    public function create(array $data): Grade
    {
        try {
            $grade = $this->gradeRepository->create($data);

            return new Grade($grade);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function get(int $id): Grade
    {
        try {
            $grade = $this->gradeRepository->get($id);

            return new Grade($grade);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Grade
    {
        try {
            $grade = $this->gradeRepository->update($id, $data);

            return new Grade($grade);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->gradeRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function all(array $data)
    {
        try {
            $grade = $this->gradeRepository->all($data);

            return new GradeCollection($grade);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
}
