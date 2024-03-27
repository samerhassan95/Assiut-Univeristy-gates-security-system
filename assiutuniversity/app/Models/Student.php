<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'nationalID', 'faculty', 'level', 'status',
    //  'image'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty', 'name');
    }
    public function visits()
    {
        return $this->hasMany(StudentVisit::class, 'nationalID', 'nationalID');
    }
}

