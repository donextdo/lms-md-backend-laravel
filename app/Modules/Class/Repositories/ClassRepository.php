<?php

namespace App\Modules\Class\Repositories;

use App\Models\Class1;
use App\Models\Subject;
use App\Modules\Class\Repositories\Interfaces\ClassRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class ClassRepository implements ClassRepositoryInterface
{
    public function __construct(protected Class1 $class,protected Subject $subject)
    {
    }

    public function create(array $data): Class1
    {
        return $this->class::create([
            'subject_id' => $data['subject_id'],
            'tutor_id' => $data['tutor_id'],
            'grade_id' => $data['grade_id'],
            'country_id' => $data['country_id'],
            'price' => $data['price'],
            'day_of_week' => $data['day_of_week'],
        ]);
    }

    public function all(array $data)
    {
   
        if (array_key_exists('paginate', $data) && $data['paginate']) 
        {
            return $this->class::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->class::all();
    }

    public function get(int $id): Class1
    {
        return $this->getClassById($id);
    }

    public function update(int $id, array $data): Class1
    {
        $class = $this->getClassById($id);
        if ($class) 
        {
            return $this->updateClass($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        $class = $this->getClassById($id);

        if ($class) 
        {
            return throw_unless($this->class::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getClassById(int $id): Class1
    {
        return $this->class::findOrFail($id);
    }
    private function updateClass(int $id, array $data): Class1
    {
        $class = $this->class::find($id);
        $class->subject_id = $data['subject_id'];
        $class->tutor_id =$data['tutor_id'];
        $class->grade_id =$data['grade_id'];
        $class->country_id=$data['country_id'];
        $class->price=$data['price'];
        $class->day_of_week=$data['day_of_week'];
        $class->save();

        return $class;
    }
    public function classesPerSubject(int $id,array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) 
        {
            return $this->class->where('subject_id',$id)->orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return    $this->class->where('subject_id',$id)->get();   
    }
    public function sessionClass(int $id,array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) 
        {
            return $this->class->find($id)->sessions->orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }
      return  $this->class->find($id)->sessions;  
    }

    public function studentClass(int $id,array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) 
        {
            return $this->class->find($id)->students->orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }  
      return  $this->class->find($id)->students;  
    }

    public function classCount()
    {
      $local= $this->class->where('country_id',1)->count();
      $global=$this->class->where('country_id','!=',1)->count();
      $classes['local']=$local;
      $classes['global']=$global;
      return $classes;
    }

}

    