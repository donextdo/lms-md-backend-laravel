<?php


namespace App\Http\Controllers;

use App\Modules\Student\Services\StudentService;
use Exception;
use App\Support\Enums\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;

class StudentController extends Controller
{
    public function __construct(protected StudentService $student)
    {
    }
    public function index(Request $request): JsonResponse
    {
        $response = $this->student->all($request->all());
        return $this->sendResponse($response, 'Students shown successfully.'); 
    }
    public function create(Request $request): JsonResponse
    {
        $validator = $this->validateStudent($request->all());

       if ($validator->fails()) {
           return $this->sendError(Response::data_error(), $validator->errors());
       }

        $response = $this->student->create($request->all());
        return $this->sendResponse($response, 'Student created successfully.');
    }

    public function get(int $id): JsonResponse
    {
        $response = $this->student->get($id);
        return $this->sendResponse($response, 'Student shown successfully.');
    }
    public function update(int $id,Request $request): JsonResponse
    {
        $response = $this->student->update($id,$request->all());
        return $this->sendResponse($response, 'Student updated successfully.');
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->student->delete($id);
        return $this->sendResponse($response, 'Student deleted successfully.');
    }

    public function block(int $id):JsonResponse
    {
        $response = $this->student->block($id);
        return $this->sendResponse($response, 'Student blocked successfully.');
    }

    public function activate(int $id):JsonResponse
    {
        $response = $this->student->activate($id);
        return $this->sendResponse($response, 'Student blocked successfully.');
    }

    public function shiftSession(int $class,int $student):JsonResponse
    {
        $response = $this->student->shiftSession($class,$student);
        return $this->sendResponse($response, 'Student blocked successfully.');
    }
    public function sessionStatus(int $class,int $student):JsonResponse
    {
        $response = $this->student->sessionStatus($class,$student);
        return $this->sendResponse($response, 'Student blocked successfully.');
    }
    public function studentClasses(Request $request):JsonResponse
    {
        $response = $this->student->studentClasses($request->all());
        return $this->sendResponse($response, 'Classes  shown  successfully.'); 
    }
    
    public function studentPastClasses(Request $request):JsonResponse
    {
        $response = $this->student->studentPastClasses($request->all());
        return $this->sendResponse($response, 'Classes  shown  successfully.'); 
    }
    public function approveStudent(Request $request,int $id){
        $response = $this->student->approveStudent($id,$request->all());
        return $this->sendResponse($response, 'Student approved  shown  successfully.'); 
    }
    protected function validateStudent(array $data): ValidationValidator
    {  $data['country_id']=(int)$data['country_id'];
        return Validator::make($data, [
            'subject_id'=>['required', 'integer', 'max:50'],
            'date_of_birth' => ['date_format:Y-m-d', 'before:today'],
            'country_id'=>['required', 'integer', 'max:50'],
            'approved' => ['boolean'],
            'name' => ['required', 'string', 'max:50'],
            'email'=>['required','string','max:500'],
            'contact_no' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8', 'max:12'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'description'=>['string','max:1000','nullable'],
            'files.*'=>['mimes:png,jpg,jpeg,pdf','nullable','max:10000']
        ]);
    }
   
}
