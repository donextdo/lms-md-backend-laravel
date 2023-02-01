<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'class1_id',
        'title',
        'date',
        'time',
        'recording_link',
        'description'
    ];

    public function class()
    {
        return $this->belongsTo(Class1::class);
    }

    public function session_emails()
    {
        return $this->hasMany(Session_email::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    public function sessionNotes()
    {
        return $this->hasMany(SessionNotes::class);
    }
}