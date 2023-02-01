<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Session_email extends JsonResource
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
            'session_id' => $this->session_id,
            'title' => $this->title,
            'body'=>$this->body,
            'type'=>$this->type,
            'date'=>$this->date,
            'time'=>$this->time,
            'status'=>$this->status,
        ];
    }
}
