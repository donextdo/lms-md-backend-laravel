<?php

namespace App\Modules\Tutor\Repositories;

use App\Models\User;
use App\Http\Resources\Class1 as cls;
use App\Models\Session;
use App\Models\Class1;
use App\Models\Tutor;
use App\Modules\Tutor\Repositories\Interfaces\TutorRepositoryInterface;
use App\Support\Enums\Roles;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Collection; 
class TutorRepository implements TutorRepositoryInterface
{
    public function __construct(protected Tutor $tutor,protected User $user,protected Class1 $class,protected Session $session)
    {
    }

    public function create(array $data): Tutor
    {

         $user= $this->user::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'contact_no'=>$data['contact_no'],
            'password'=>Hash::make($data['name'].'123'),
            'role_id'=>Roles::tutor()->value
        ]);

        $tutor = $this->tutor::create([
            'user_id' =>$user->id,
            'subject_id' => $data['subject_id']
        ]);

        return $tutor;
    }

    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->tutor::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->tutor::all();
    }

    public function get(int $id): Tutor
    {
        return $this->getTutorById($id);
    }

    public function update(int $id, array $data): Tutor
    {
        $tutor = $this->getTutorById($id);

        if ($tutor) {
            return $this->updateTutor($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        $tutor = $this->getTutorById($id);

        if ($tutor) {
            return throw_unless($this->tutor::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getTutorById(int $id):Tutor
    {
        return $this->tutor::findOrFail($id);
    }

    private function updateTutor(int $id, array $data): Tutor
    { 
        $tutor = $this->tutor::find($id);
        $tutor->subject_id = $data['subject_id'];
        $user = $this->user::find($tutor->user_id);
        $user->name=$data['name'];
        $user->contact_no=$data['contact_no'];
        $user->email=$data['email'];
        $user->save();
        $tutor->save();

        return $tutor;
    }

    public function tutorClasses(int $userId,array $data)
    {
      
        if (array_key_exists('paginate', $data) && $data['paginate'])
        {
           return $this->tutor::where('user_id',$userId)->first()->classes
           ->orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }  
      return $this->tutor::where('user_id',$userId)->first()->classes;
    }
    public function tutorStudents(int $userId, array $data)
    {
       $students=[]; 
        foreach($this->tutor::where('user_id',$userId)->first()->classes as $class)
        {
            foreach($class->students as $student)
            {
                $students[]=$student;   
            }
        }
        if (array_key_exists('paginate', $data) && $data['paginate'])
        {
          return $students;
        }
        return $students;
        
    }
    public function studentCountPerTutor(int $id)
    {
     $count =0;   
     $subjects = $this->tutor->where('user_id',$id)->get();
     foreach($subjects as $subject)
     {
        $classes=$subject->classes;
        foreach($classes as $class)
        {
        $count += $class->students->count();
        }
     }
    
     return $count;
    }

    public function sessionCountPerTutor(int $id)
    {
     $count =0;   
     $subjects = $this->tutor->where('user_id',$id)->get();
     foreach($subjects as $subject)
     {
        $classes=$subject->classes;
        foreach($classes as $class)
        {
        $count += $class->sessions->count();
        }
     }
    
     return $count;
    }

    public function upcomingPerTutor(int $id, array $data)
    {
     $upcoming=collect([]);   
     $subjects= $this->tutor->where('user_id',$id)->get();
     foreach($subjects as $subject)
     {
        $classes=$subject->classes;
        foreach($classes as $class)
        {
         $upcoming= $upcoming->merge (
            $this->session->where('class1_id',$class->id)
            ->where('date',Carbon::today())->where('time','>',Carbon::now())->get());  
        }
     }
     return $this->addClassOfSession($upcoming);
    }
    public function addClassOfSession(Collection $upcoming)
    {
     $upcoming= $upcoming->map(function($session){

       return collect($session)->merge(new cls($this->class->find($session->class1_id)));
     });
     return $upcoming;
    }

}
