<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code' ,'currency' 
    ];
    
    public function classes()
    {
        return $this->hasMany(Class1::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
