<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Class1 extends JsonResource
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
            'id' => $this->id,
            'grade' =>new Grade($this->grade),
            'subject' =>new Subject($this->subject),
            'country'=>new Country($this->country),
            'tutor'=>new tutor($this->tutor),
            'day_of_week'=>$this->day_of_week,
            'price'=>$this->price,
            'session'=>$this->sessions->first(),
            'sessionsCount'=>$this->sessions->count(),
            'studentsCount'=>$this->students->count(),
        ];
    }
}
