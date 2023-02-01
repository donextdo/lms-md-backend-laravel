<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Class1 extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tutor_id',
        'subject_id',
        'grade_id',
        'country_id',
        'price',
        'day_of_week',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
   
}
