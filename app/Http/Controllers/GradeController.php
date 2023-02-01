<?php


namespace App\Http\Controllers;

use App\Modules\Grade\Services\GradeService;
use Exception;
use App\Support\Enums\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;

class GradeController extends Controller
{
    public function __construct(protected GradeService $grade)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->grade->all($request->all());
        return $this->sendResponse($response, 'Grades shown successfully.'); 
    }
    public function create(Request $request): JsonResponse
    {
        $validator = $this->validateGrade($request->all());

        if ($validator->fails()) {
            return $this->sendError(Response::data_error(), $validator->errors());
        }

        $response = $this->grade->create($request->all());
        return $this->sendResponse($response, 'Grade created successfully.');
    }

    public function get(int $id): JsonResponse
    {
        $response = $this->grade->get($id);
        return $this->sendResponse($response, 'Grade shown successfully.');
    }
    public function update(int $id,Request $request): JsonResponse
    {
        $response = $this->grade->update($id,$request->all());
        return $this->sendResponse($response, 'Grade updated successfully.');
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->grade->delete($id);
        return $this->sendResponse($response, 'Grade deleted successfully.');
    }

    protected function validateGrade(array $data): ValidationValidator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
        ]);
    }
   
}
