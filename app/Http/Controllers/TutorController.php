<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tutor\Services\TutorService;
use App\Support\Enums\Response;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TutorController extends Controller
{
    public function __construct(protected TutorService $tutorService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->tutorService->all($request->all());

        return $this->sendResponse($response, 'Tutors shown successfully.');
    }

    public function create(Request $request): JsonResponse
    {
       // $validator = $this->validateTutor($request->all());

      //  if ($validator->fails()) {
     //       return $this->sendError(Response::data_error(), $validator->errors());
      //  }
        $response = $this->tutorService->create($request->all());

        return $this->sendResponse($response, 'Tutor created successfully.');
    }

    public function get($id): JsonResponse
    {
        $response = $this->tutorService->get($id);

        return $this->sendResponse($response, null);
    }

    public function update(int $id, Request $request): JsonResponse
    {
       // $validator = $this->validateTutor($request->all());

      //  if ($validator->fails()) {
      //      return $this->sendError(Response::data_error(), $validator->errors());
     //   }

        $response = $this->tutorService->update($id, $request->all());

        return $this->sendResponse($response, 'Tutor updated successfully.');
    }

    public function delete($id): JsonResponse
    {
        $response = $this->tutorService->delete($id);

        return $this->sendResponse($response, 'Tutor deleted successfully.');
    }

    public function tutorClasses(Request $request):JsonResponse
    {
        $response = $this->tutorService->tutorClasses($request->all());
        return $this->sendResponse($response, 'Classes  shown  successfully.'); 
    }
    public function tutorStudents(Request $request):JsonResponse
    {
        $response = $this->tutorService->tutorStudents($request->all());
        return $this->sendResponse($response, 'Students  shown  successfully.'); 
    }
    protected function validateTutor(array $data): ValidationValidator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'email'=>['required','string','max:500'],
            'contact_no' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8', 'max:12'],
            'subject_id' => ['required', 'integer', 'max:50'],
        ]);
    }
}
