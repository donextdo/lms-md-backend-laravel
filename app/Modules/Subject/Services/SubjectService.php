<?php

namespace App\Modules\Subject\Services;

use App\Http\Resources\Subject;
use App\Http\Resources\SubjectCollection;
use App\Modules\Subject\Repositories\Interfaces\SubjectRepositoryInterface as SubjectRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubjectService
{
    public function __construct(protected SubjectRepository $subjectRepository)
    {
    }

    public function create(array $data): Subject
    {
        try {
            $subject = $this->subjectRepository->create($data);

            return new Subject($subject);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function get(int $id): Subject
    {
        try {
            $subject = $this->subjectRepository->get($id);

            return new Subject($subject);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Subject
    {
        try {
            $subject = $this->subjectRepository->update($id, $data);

            return new Subject($subject);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->subjectRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function all(array $data)
    {
        try {
            $subjects = $this->subjectRepository->all($data);

            return new SubjectCollection($subjects);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
}
