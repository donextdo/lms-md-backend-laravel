<?php


namespace App\Http\Controllers;

use App\Modules\Session\Services\SessionService;
use App\Modules\Class\Services\ClassService;
use App\Modules\Student\Services\StudentService;
use App\Modules\Tutor\Services\TutorService;
use Exception;
use App\Support\Enums\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;

class DashboardController extends Controller
{
    public function __construct(protected StudentService $student,protected SessionService $session,protected ClassService $class,protected TutorService $tutor)
    {
    }

    public function adminDashboard(Request $request):JSONResponse
    {
     $students= $this->student->studentCount();   
     $classes=$this->class->classCount();
     $upcoming=$this->session->upcoming($request->all());
     $requests=$this->student->requests($request->all());
     $response= ([
       'students'=>$students, 
       'classes'=>$classes,
       'upcoming'=>$upcoming,
       'requests'=>$requests
     ]);
     return $this->sendResponse($response, 'Dashboard data shown successfully.');

    }

    public function studentRequestIndetail(int $id):JSONResponse
    {
      $response= $this->student->requestDetails($id);
      return $this->sendResponse($response, 'Dashboard data shown successfully.');

    }

    public function tutorDashboard(Request $request):JSONResponse
    {
    
      $students= $this->tutor->studentCountPerTutor();   
      $classes=$this->tutor->sessionCountPerTutor();
      $upcoming=$this->tutor->upcomingPerTutor($request->all());
      $response=['studentCount'=>$students,'classCount'=>$classes,'upcoming'=>$upcoming];
      return $this->sendResponse($response, 'Dashboard data shown successfully.');


    }

    public function studentDashboard():JSONResponse
    {
      $data=['per-page'=>2,'paginate'=>true];
      $recordings = $this->student->studentPastClasses($data);
      $classes = $this->student->studentClasses($data);
      $messages = $this->student->studentInbox();
      $response=['recordings'=>$recordings,'classes'=>$classes,'messages'=>$messages];
      return $this->sendResponse($response, 'Classes  shown  successfully.'); 
    }
  
    public function refreshRequests(Request $request):JSONResponse
    {
      $requests=$this->student->requests($request->all());
      $response= ([
        'requests'=>$requests
      ]);
      return $this->sendResponse($response, 'Dashboard data shown successfully.');
 
    }
}
