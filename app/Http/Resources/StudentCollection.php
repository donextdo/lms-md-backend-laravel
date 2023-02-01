<?php

namespace App\Http\Resources;
use App\Models\Student as ModelStudent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resource = [ 'student' => $this->collection->map(function ($student) { return new Student($student); }),  ];

        if (is_a($this->resource, LengthAwarePaginator::class)) {
            $resource = array_merge($resource, [
                'meta' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => (int) $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
            ]);
        }

        return $resource;
    }
}
