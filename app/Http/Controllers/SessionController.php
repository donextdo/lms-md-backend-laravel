<?php

namespace App\Http\Controllers;

use App\Modules\Session\Services\SessionService;
use Exception;
use App\Support\Enums\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use File;
use ZipArchive;

class SessionController extends Controller
{
    public function __construct(protected SessionService $session)
    {
    }
    public function index(Request $request): JsonResponse
    {
        $response = $this->session->all($request->all());
        return $this->sendResponse($response, 'Sessions shown successfully.'); 
    }
    public function create(Request $request): JsonResponse
    {
        $validator = $this->validateSession($request->all());

        if ($validator->fails()) {
            return $this->sendError(Response::data_error(), $validator->errors());
        }

        $response = $this->session->create($request->all());
        return $this->sendResponse($response, 'Session created successfully.');
    }

    public function get(int $id): JsonResponse
    {
        $response = $this->session->get($id);
        return $this->sendResponse($response, 'Session shown successfully.');
    }
    public function update(int $id,Request $request): JsonResponse
    {
        $response = $this->session->update($id,$request->all());
        return $this->sendResponse($response, 'Session updated successfully.');
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->session->delete($id);
        return $this->sendResponse($response, 'Session deleted successfully.');
    }

    public function postpone(int $id,Request $request)
    {
      $validatedRequest= $request->validate(['date'=>['required', 'date_format:Y-m-d', 'max:50'],
        'time' => ['required', 'date_format:H:i']]);
      $response = $this->session->postpone($validatedRequest,$id);
      return $this->sendResponse($response, 'Session deleted successfully.');

    }

    public function recording(int $id,Request $request)
    {
        $validatedRequest= $request->validate([ 'recording_link'=>[ 'string', 'max:650']]);
      $response = $this->session->recording($id,$validatedRequest);
      return $this->sendResponse($response, 'link added successfully.');
    }

    protected function validateSession(array $data): ValidationValidator
    {
        return Validator::make($data, [
           
            'class1_id'=>['required', 'integer', 'max:50'],
            'title'=>['required', 'string', 'max:50'],
            'date'=>['required', 'date_format:Y-m-d', 'max:50'],
            'time' => ['required', 'date_format:H:i'],
            'recording_link'=>[ 'string', 'max:650'],
            'description'=>['string','max:650'],
            'file.*'=>['mimes:png,jpg,jpeg,pdf','nullable','max:10000']
            
        ]);
    }
    public function getSessionNotes(int $id){
        $fileLinks = collect($this->session->getSessionNotes($id));
        $zip = new ZipArchive;

        $fileName = 'my-images.zip';

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {

            $files = File::files(public_path('Class_Notes'));

            foreach ($files as $key => $value) {
               if($fileLinks->contains(basename($value)))
               {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
               }
               
            }

            $zip->close();
        }
        
        return response()->download(public_path($fileName))->withHeaders('Content-Disposition: attachment');

    }
   
}
