<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Payment extends JsonResource
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
            'student_id' => $this->student_id,
            'class1_id'=> $this->class1_id,
            'amount'=>$this->amount,
            'type'=>$this->type,
            'status'=>$this->status,
            'payment_recipt'=>$this->payment_recipt,
            'reject_description'=>$this->reject_description,
        ];
    }
}
