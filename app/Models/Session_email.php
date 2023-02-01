<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session_email extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'session_id',
        'title',
        'body',
        'type',
        'date',
        'time',
        'status',
    ];


    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
