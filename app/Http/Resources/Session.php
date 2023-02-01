<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Session extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'class1_id' =>$this->class1_id,
            'title' => $this->title,
            'date'=>$this->date,
            'time'=>$this->time,
            'recording_link'=>$this->recording_link,
            'tutor'=>$this->tutor,
            'subject'=>$this->subject,
            'description'=>$this->description,
            'attended'=>$this->attended
        ];
    }
}
