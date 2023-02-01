<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',  
        'subject_id',     
    ];


   
    public function user()
    {
       return $this->belongsTo(User::class);
    }
    public function classes()
    {
       return $this->hasMany(Class1::class);
    }
    public function subject()
    {
       return $this->belongsTo(Subject::class);
    }
}
