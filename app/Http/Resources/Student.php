<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Student extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $grades=$this->classes->map(function ($class) {  return new Grade($class->grade);  });
        $subjects=$this->classes->map(function ($class) {  return new Subject($class->subject);  });
        return [
            'id'=>$this->id,
            'user' => $this->user,
            'subject' =>$subjects,
            'country'=>new Country($this->country),
            'approved'=>$this->approved,
            'email'=>$this->user->email,
            'contact_no'=>$this->contact_no,
            'created_at'=>$this->created_at->toDateString(),
            'grade'=>$grades,
            'status'=>$this->status
        ];
    }
}


