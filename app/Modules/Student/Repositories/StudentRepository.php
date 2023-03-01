<?php

namespace App\Modules\Student\Repositories;
use App\Models\Student;
use App\Models\StudentData;
use App\Models\User;
use App\Modules\Student\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Support\Enums\Roles;
use Illuminate\Support\Collection; 
use Illuminate\Support\Facades\Storage;
class StudentRepository implements StudentRepositoryInterface
{
    protected $files=[];
    public function __construct(protected Student $student,protected StudentData $studentData ,protected User $user)
    {
       
    }

    public function create(array $data): Student
    {
        $user=  $this->user::create([
          'name'=>$data['name'],
          'email'=>$data['email'],
          'contact_no'=>$data['contact_no'],
          'date_of_birth'=>$data['date_of_birth'],
          'password'=> Hash::make($data['password']),
          'role_id'=>Roles::student()->value
         ]);

         $student= $this->student::create([
             'user_id' => $user->id,
             'country_id' => $data['country_id'],
             'approved' => 0,
             'status'=>'Active'
         ]);
        if($data['files']!==null)
        {
         $this->files[]=$data['files'];   
         $this->uploadIt($student->id,$data);
        }
    return $student;

    }
    public function uploadIt(int $id,array $data){
   
        foreach($this->files as $file){
          $name='img'.date('mdYHis').'.'.$file->extension();
         $file->storeAs('Student_status',$name,'public');
          $this->studentData::create(['student_id'=>$id,'subject_id'=>$data['subject_id'],'description'=>$data['description'],'file'=>$name]);

        }
       }
    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->student::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->student::all();
    }

    public function get(int $id): Student
    {
        return $this->getStudentById($id);
    }

    public function update(int $id, array $data): Student
    {
        $student = $this->getStudentById($id);

        if ($student) {
            return $this->updateStudent($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        $student = $this->getStudentById($id);

        if ($student) {
            return throw_unless($this->student::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getStudentById(int $id): Student
    {
        return $this->student::findOrFail($id);
    }

    private function updateStudent(int $id, array $data): Student
    {
        $student = $this->student::find($id);
        $student->user->name = $data['name'];
        $student->user->email = $data['email'];
        $student->user->contact_no = $data['contact_no'];
        $student->user->save();

        return $student;
    }
    public function block(int $id)
    {
        if($student = $this->student::find($id))
        {
            $student->status = 'blocked';
            $student->save();
        }
        else
        {
         return "wrong entry";   
        }
    }

    public function shiftSession(int $class,int $student)
    {
        if($student_class = DB::table('class1_student')->where('class1_id',$class)->where('student_id',$student))
        {
            $student_class->update(['status'=> $student_class->first()->status==1?0:1 ]);
        }
        else
        {
         return "wrong entry";   
        }
    }
    
    public function sessionStatus(int $class,int $student)
    {
        if($student_class = DB::table('class1_student')->where('class1_id',$class)->where('student_id',$student))
        {
            return $student_class->first('status');
        }
        else
        {
         return "wrong entry";   
        }
    }

    public function activate(int $id)
    {
        if($student = $this->student::find($id))
        {
            $student->status = 'active';
            $student->save();
        }
        else
        {
         return "wrong entry";   
        }
    }
    public function studentCount()
    {
        $local = $this->student->where('approved',true)->where('country_id',1)->count();
        $global = $this->student->where('approved',true)->where('country_id','!=',1)->count();
        $student['local']=$local;
        $student['global']=$global;
        return $student;
    }

    public function studentClasses(int $userId,array $data)
    {
      $sessions=[]; 
       foreach($this->student::where('user_id',$userId)->first()->classes as $class)
       {
        
         foreach($class->sessions as $session)
         {
            if(DB::table('session_student')->
            where('student_id',$this->student::where('user_id',$userId)->first()->id)
            ->where('session_id',$session->id)->exists())
            {
             $session['attended']=true;   
            }
            else{
                $session['attended']=false;   

            }
            $session['tutor']=$class->tutor->user->name;
            $session['subject']=$class->subject->name;  
          $sessions[]=$session;
         }
       }
       if (array_key_exists('paginate', $data) && $data['paginate'])
       {
        $sessions=collect($sessions)->paginate($data['per-page'] ?? 10);

       } 
       return $sessions;
    }
    
    public function studentUpcomingClasses(int $userId,array $data)
    {
      $sessions=[]; 
       foreach($this->student::where('user_id',$userId)->first()->classes as $class)
       {
        
         foreach($class->sessions->where('date','>',Carbon::today()) as $session)
         {
            if(DB::table('session_student')->
            where('student_id',$this->student::where('user_id',$userId)->first()->id)
            ->where('session_id',$session->id)->exists())
            {
             $session['attended']=true;   
            }
            else{
                $session['attended']=false;   

            }
            $session['tutor']=$class->tutor->user->name;
            $session['subject']=$class->subject->name;  
          $sessions[]=$session;
         }
       }
       if (array_key_exists('paginate', $data) && $data['paginate'])
       {
        $sessions=collect($sessions)->paginate($data['per-page'] ?? 10);

       } 
       return $sessions;
    }

    public function requests(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->student::where('approved',false)->orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->student::where('approved',false)->get();
    }

    public function studentPastClasses(int $userId,array $data)
    {
      $sessions=[];
       foreach($this->student::where('user_id',$userId)->first()->classes as $class)
       {
        
         foreach($class->sessions->where('date','<',Carbon::today()) as $session)
         {
            if(DB::table('session_student')->
            where('student_id',$this->student::where('user_id',$userId)->first()->id)
            ->where('session_id',$session->id)->exists())
            {
             $session['attended']=true;   
            }
            else{
                $session['attended']=false;   

            }
            $session['tutor']=$class->tutor->user->name;
            $session['subject']=$class->subject->name;  
          $sessions[]=$session;
         }
       }
       if (array_key_exists('paginate', $data) && $data['paginate'])
       {
        $sessions=collect($sessions)->paginate($data['per-page'] ?? 10);
       }  
       return $sessions;

    }
    public function requestDetails(int $id)
    {
      return  $this->student->find($id)->studentData;
    }
    
    public function studentInbox(int $id)
    {
     $messages=null;  
     $subjects= $this->student->where('user_id',$id)->get();
      foreach($subjects as $subject)
      {
       $messages = collect($messages)->merge(collect($subject->studentInbox));
      }
      return  $messages;
    }

    public function approveStudent(int $id,array $data)
    {
      $student= $this->student->find($id);
      $student->approved=true;
      $student->save();

      DB::table('class1_student')->insert(['class1_id'=>$data['class1_id'],'student_id'=>$id,'status'=>true]);

    }

}
