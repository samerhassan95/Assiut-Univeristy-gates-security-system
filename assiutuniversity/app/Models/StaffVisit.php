<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffVisit extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'position', 'visit_datetime', 'nationalID', 'gate_name','enter_outer','status'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'nationalID', 'nationalID');
    }

    public function gate()
    {
        return $this->belongsTo(Gate::class, 'gate_name', 'name');
    }
        // Deserialize the visit_date_times attribute into an array
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
