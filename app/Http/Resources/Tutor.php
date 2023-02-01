<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tutor extends JsonResource
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
        return [
            'id' => $this->id,
            'subject'=>new Subject($this->subject),
            'user'=>$this->user,
            'created_at'=>$this->created_at->toDateString(),
            'grade'=>$grades,  
        ];
    }
}
