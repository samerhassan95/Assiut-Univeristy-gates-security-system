<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentVisit extends Model
{
    use HasFactory;
    protected $fillable = ['nationalID', 'name', 'faculty', 'status', 'visit_datetime','gate_name','enter_outer'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'nationalID', 'nationalID');
    }

          public function gate()
    {
        return $this->belongsTo(Gate::class, 'gate_name', 'name');
    }      // Deserialize the visit_date_times attribute into an array
            // public function getVisitDateTimesAttribute($value)
            // {
            //     return $value ? json_decode($value, true) : [];
            // }
        
            // // Serialize the array of date-time values before saving
            // public function setVisitDateTimesAttribute($value)
            // {
            //     $this->attributes['visit_date_times'] = $value ? json_encode($value) : null;
            // }
}
