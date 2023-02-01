<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'country_id',
        'approved',
        'status',
    ];

    protected $casts = [
      'created_at'  => 'date:Y-m-d',
  ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function classes()
    {
        return $this->belongsToMany(Class1::class);
    }
    public function sessions()
    {
        return $this->belongsToMany(Session::class);
    }

    public function studentData()
    {
        return $this->hasMany(studentData::class);
    }

    public function studentInbox()
    {
        return $this->hasMany(Student_inbox::class);
    }
}
