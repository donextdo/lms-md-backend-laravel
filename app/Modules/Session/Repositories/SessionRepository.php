<?php

namespace App\Modules\Session\Repositories;

use App\Models\Session;
use App\Models\Class1;
use App\Models\SessionNotes;
use App\Modules\Session\Repositories\Interfaces\SessionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Carbon\Carbon;
class SessionRepository implements SessionRepositoryInterface
{
    protected $files=[];

    public function __construct(protected Session $session,protected SessionNotes $sessionNotes,protected Class1 $class)
    {
    }

    public function create(array $data): Session
    {
        $session= $this->session::create([
            'class1_id' => $data['class1_id'],
            'description'=>$data['description'],
            'title' => $data['title'],
            'date' => $data['date'],
            'time'=> $data['time'],
            'recording_link'=> null,
        ]);

        if(isset($data['file']) &&  $data['file']!==null)
        {
         $this->files[]=$data['file'];   
         $this->uploadNotes($session->id,$data);
        }
        return $session;
    }

    public function uploadNotes(int $id,array $data)
    {
        foreach($this->files as $file){
          $name='img'.date('mdYHis').'.'.$file->extension();
         $file->storeAs('Class_Notes',$name,'public');
         $this->sessionNotes->create([
            'session_id'=>$id,
             'notes'=>$name
         ]); 
        }
    }

    
    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->session::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->session::all();
    }

    public function get(int $id): Session
    {
        return $this->getSessionById($id);
    }

    public function update(int $id, array $data): Session
    {
        $session = $this->getSessionById($id);

        if ($session) {
            $session= $this->updateSession($id, $data);
        }
        if(isset($data['file']) &&  $data['file']!==null)
        {
         $this->files[]=$data['file'];   
         $this->uploadNotes($id,$data);
        }
        return $session;
    }

    public function delete(int $id): bool
    {
        $session = $this->getSessionById($id);

        if ($session) {
            return throw_unless($this->session::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getSessionById(int $id):Session
    {
        return $this->session::findOrFail($id);
    }

    private function updateSession(int $id, array $data): Session
    {
        $session = $this->session::find($id);
        $session->title = $data['title'];
        $session->date = $data['date'];
        $session->time = $data['time'];
        $session->recording_link = null;
        $session->description= $data['description'];
        $session->save();

        return $session;
    }

    public function upcoming(array $data)
    {
   
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->session::where('date',Carbon::today())->where('time','>',Carbon::now())->orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }
        return $this->session::where('date',Carbon::today())->where('time','>',Carbon::now())->get();
    }

    public function postpone(array $data,int $id): Session
    {
        $session = $this->session::find($id);
        $session->date = $data['date'];
        $session->time = $data['time'];
        $session->save();

        return $session;
    }

    public function recording(int $id,array $data)
    {
        $session = $this->session::find($id);
        $session->recording_link = $data['recording_link'];
        $session->save();

        return $session;
    }

   public function firstSession(array $data)
   {
    $data['description']=null;
    $data['title']='Session'.$this->session->max('id')+1;
    $data['recording_link']=null;
    $data['date'] = Carbon::now()
    ->next((int)$data['day_of_week'])->format('Y-m-d');
    $this->create($data);
   } 
   public function getSessionNotes(int $id){
      $noteLinks=[]; 
      $notes= $this->session->find($id)->sessionNotes;
      foreach($notes as $note)
      {
       $noteLinks[]=$note['notes'];
      }
      return $noteLinks;
   }
   
   public function renew()
   {           

    foreach($this->session->get() as $session)
    {   $data=[];
        $data['description']=null; 
        $data['title']='Session'.$this->session->max('id')+1;
        $data['recording_link']=null; 
        $class_id=$session->class1_id;     
        $day= $this->class->find($class_id)->day_of_week;  
        $data['date'] = Carbon::now()
        ->next((int)$day)->format('Y-m-d');
        $data['time']=$session->time;
        $data['class1_id']=$session->class1_id;
        $this->create($data);

    } 
   }

}
