<?php

namespace App\Http\Controllers;

use App\Modules\Session_email\Services\Session_emailService;
use Exception;
use App\Support\Enums\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;

class Session_emailController extends Controller
{
    public function __construct(protected Session_emailService $session_email)
    {
    }
    public function index(Request $request): JsonResponse
    {
        $response = $this->session_email->all($request->all());
        return $this->sendResponse($response, 'Session_emails shown successfully.'); 
    }
    public function create(Request $request): JsonResponse
    {
        $validator = $this->validateSession_email($request->all());

        if ($validator->fails()) {
            return $this->sendError(Response::data_error(), $validator->errors());
        }

        $response = $this->session_email->create($request->all());
        return $this->sendResponse($response, 'Session_email created successfully.');
    }

    public function get(int $id): JsonResponse
    {
        $response = $this->session_email->get($id);
        return $this->sendResponse($response, 'Session_email shown successfully.');
    }
    public function update(int $id,Request $request): JsonResponse
    {
        $response = $this->session_email->update($id,$request->all());
        return $this->sendResponse($response, 'Session_email updated successfully.');
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->session_email->delete($id);
        return $this->sendResponse($response, 'Session_email deleted successfully.');
    }

    protected function validateSession_email(array $data): ValidationValidator
    {
        return Validator::make($data, [
           
            'session_id'=>['required', 'integer', 'max:50'],
            'title'=>['required', 'string', 'max:50'],
            'date'=>['required', 'date_format:Y-m-d', 'max:50'],
            'time' => ['required', 'date_format:H:i:s'],
            'status'=>['required', 'string', 'max:150'],
            
        ]);
    }
   
}
