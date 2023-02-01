<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    protected $fillable=['student_id','subject_id','description','file'];
    use HasFactory;
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
