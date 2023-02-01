<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Subject\Services\SubjectService;
use App\Support\Enums\Response;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $subjectService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->subjectService->all($request->query());

        return $this->sendResponse($response, 'Subjects shown successfully.');
    }

    public function create(Request $request): JsonResponse
    {
        $validator = $this->validateSubject($request->all());

        if ($validator->fails()) {
            return $this->sendError(Response::data_error(), $validator->errors());
        }

        $response = $this->subjectService->create($request->all());

        return $this->sendResponse($response, 'Subject created successfully.');
    }

    public function get($id): JsonResponse
    {
        $response = $this->subjectService->get($id);

        return $this->sendResponse($response, null);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $validator = $this->validateSubject($request->all());

        if ($validator->fails()) {
            return $this->sendError(Response::data_error(), $validator->errors());
        }

        $response = $this->subjectService->update($id, $request->all());

        return $this->sendResponse($response, 'Subject updated successfully.');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->subjectService->delete($id);

        return $this->sendResponse($response, 'Subject deleted successfully.');
    }

    protected function validateSubject(array $data): ValidationValidator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
        ]);
    }
}
