<?php


namespace App\Http\Controllers;

use App\Modules\Class\Services\ClassService;
use Exception;
use App\Support\Enums\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;

class ClassController extends Controller
{
    public function __construct(protected ClassService $class)
    {
    }
    public function index(Request $request): JsonResponse
    {
        $response = $this->class->all($request->all());
        return $this->sendResponse($response, 'Classes shown successfully.'); 
    }
    public function classesPerSubject(Request $request,int $id): JsonResponse
    {
        $response = $this->class->classesPerSubject($id,$request->all());
        return $this->sendResponse($response, 'Classes shown successfully.'); 
    }
    public function create(Request $request): JsonResponse
    {
        $validator = $this->validateClass($request->all());
       if ($validator->fails()) {
          return $this->sendError(Response::data_error(), $validator->errors());
        }

        $response = $this->class->create($request->all());
        return $this->sendResponse($response, 'Class created successfully.');
    }

    public function get(int $id): JsonResponse
    {
        $response = $this->class->get($id);
        return $this->sendResponse($response, 'Class shown successfully.');
    }
    public function update(int $id,Request $request): JsonResponse
    {
        $response = $this->class->update($id,$request->all());
        return $this->sendResponse($response, 'Class updated successfully.');
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->class->delete($id);
        return $this->sendResponse($response, 'Class deleted successfully.');
    }

    public function sessionClass(int $id,Request $request): JsonResponse
    {
        $response = $this->class->sessionClass($id,$request->all());
        return $this->sendResponse($response, 'Class sessions shown  successfully.');
    }

    public function studentClass(int $id,Request $request): JsonResponse
    {
        $response = $this->class->studentClass($id,$request->all());
        return $this->sendResponse($response, 'Class students shown  successfully.');
    }

    protected function validateClass(array $data): ValidationValidator
    {
        $data['grade_id']=(int)$data['grade_id'];
        $data['subject_id']=(int)$data['subject_id'];
        $data['country_id']=(int)$data['country_id'];
        $data['tutor_id']=(int)$data['tutor_id'];
        $data['price']=(float)$data['price'];
        $data['day_of_week']=(int)$data['day_of_week'];
        return Validator::make($data, [
            'grade_id'=>['required', 'integer', 'max:50'],
            'subject_id'=>['required', 'integer', 'max:50'],
            'country_id'=>['required', 'integer', 'max:50'],
            'tutor_id'=>['required', 'integer', 'max:50'],
            'price' => ['required', 'numeric', 'max:5000000'],
            'day_of_week'=>['required', 'integer', 'max:7'],
            'time'=>['required'],

        ]);
    }
   
}
