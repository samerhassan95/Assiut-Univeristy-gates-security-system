<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Query\Expression;




class Gate extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function studentVisits()
    {
        return $this->hasMany(StudentVisit::class, 'gate_name', 'name');
    }

    public function staffVisits()
    {
        return $this->hasMany(StaffVisit::class, 'gate_name', 'name');
    }
    public function studentEnteredToday()
    {
        return $this->studentVisits()
            ->whereDate('visit_datetime', now()->toDateString())
            ->where('enter_outer', 'enter');
    }

    public function studentOuterToday()
    {
        return $this->studentVisits()
            ->whereDate('visit_datetime', now()->toDateString())
            ->where('enter_outer', 'outer');
    }

    public function staffEnteredToday()
    {
        return $this->staffVisits()
            ->whereDate('visit_datetime', now()->toDateString())
            ->where('enter_outer', 'enter');
    }

    public function staffOuterToday()
    {
        return $this->staffVisits()
            ->whereDate('visit_datetime', now()->toDateString())
            ->where('enter_outer', 'outer');
    }

    public function totalEnteredVisits()
    {
        return $this->hasMany(StudentVisit::class, 'gate_name', 'name')
            ->whereDate('visit_datetime', now()->toDateString());
    }

    public function totalExitedVisits()
    {
        return $this->hasMany(StudentVisit::class, 'gate_name', 'name')
            ->whereDate('visit_datetime', now()->toDateString());
    }
// ...

// ...

// Gate.php

public function studentEnteredByFaculty()
{
    $query = $this->studentVisits()
        ->select('faculty', DB::raw('count(distinct nationalID) as count')) // Group by nationalID to count unique students
        ->where('enter_outer', 'enter');

    // Apply date range filtering if provided
    if (request()->filled('start_date') && request()->filled('end_date')) {
        $startDate = \Carbon\Carbon::parse(request('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse(request('end_date'))->endOfDay();

        $query->whereBetween('visit_datetime', [$startDate, $endDate]);
    } else {
        // Default to today's date if no date range is provided
        $query->whereDate('visit_datetime', now()->toDateString());
    }

    return $query->groupBy('faculty');
}

public function studentExitedByFaculty()
{
    $query = $this->studentVisits()
        ->select('faculty', DB::raw('count(distinct nationalID) as count')) // Group by nationalID to count unique students
        ->where('enter_outer', 'outer');

    // Apply date range filtering if provided
    if (request()->filled('start_date') && request()->filled('end_date')) {
        $startDate = \Carbon\Carbon::parse(request('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse(request('end_date'))->endOfDay();

        $query->whereBetween('visit_datetime', [$startDate, $endDate]);
    } else {
        // Default to today's date if no date range is provided
        $query->whereDate('visit_datetime', now()->toDateString());
    }

    return $query->groupBy('faculty');
}




public function staffEnteredCount()
{
    $query = $this->staffVisits()
        ->where('enter_outer', 'enter');

    // Apply date range filtering if provided
    if (request()->filled('start_date') && request()->filled('end_date')) {
        $startDate = \Carbon\Carbon::parse(request('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse(request('end_date'))->endOfDay();

        $query->whereBetween('visit_datetime', [$startDate, $endDate]);
    } else {
        // Default to today's date if no date range is provided
        $query->whereDate('visit_datetime', now()->toDateString());
    }

    return $query; // Return the relationship instance without calling count()
}

public function staffExitedCount()
{
    $query = $this->staffVisits()
        ->where('enter_outer', 'outer');

    // Apply date range filtering if provided
    if (request()->filled('start_date') && request()->filled('end_date')) {
        $startDate = \Carbon\Carbon::parse(request('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse(request('end_date'))->endOfDay();

        $query->whereBetween('visit_datetime', [$startDate, $endDate]);
    } else {
        // Default to today's date if no date range is provided
        $query->whereDate('visit_datetime', now()->toDateString());
    }

    return $query; // Return the relationship instance without calling count()
}



public function disallowedStaffCount()
{
    return Staff::where('status', 'disallowed')->count();
}

public function disallowedStudentsCount()
{
    return Student::where('status', 'disallowed')->count();
}

public function disallowedStudentsByFaculty()
{
    return Student::where('status', 'disallowed')
        ->get()
        ->groupBy('faculty')
        ->map->count();
}





public function currentlyInsideCount($startDate, $endDate)
{
    // Subquery to get the last visit datetime for each national ID
    $lastStudentVisitSubquery = StudentVisit::select('nationalID', DB::raw('MAX(visit_datetime) as last_visit_datetime'))
        ->where('gate_name', $this->name)
        ->groupBy('nationalID');

    $lastStaffVisitSubquery = StaffVisit::select('nationalID', DB::raw('MAX(visit_datetime) as last_visit_datetime'))
        ->where('gate_name', $this->name)
        ->groupBy('nationalID');

    // Count the number of individuals whose last visit was an entry within the specified date range
    $studentInsideCount = DB::table(DB::raw("({$lastStudentVisitSubquery->toSql()}) as last_student_visit"))
        ->mergeBindings($lastStudentVisitSubquery->getQuery())
        ->whereBetween('last_visit_datetime', [$startDate, $endDate])
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('student_visits')
                ->whereRaw('student_visits.nationalID = last_student_visit.nationalID')
                ->where('student_visits.visit_datetime', '=', DB::raw('last_student_visit.last_visit_datetime'))
                ->where('student_visits.enter_outer', '=', 'enter');
        })
        ->count();

    $staffInsideCount = DB::table(DB::raw("({$lastStaffVisitSubquery->toSql()}) as last_staff_visit"))
        ->mergeBindings($lastStaffVisitSubquery->getQuery())
        ->whereBetween('last_visit_datetime', [$startDate, $endDate])
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('staff_visits')
                ->whereRaw('staff_visits.nationalID = last_staff_visit.nationalID')
                ->where('staff_visits.visit_datetime', '=', DB::raw('last_staff_visit.last_visit_datetime'))
                ->where('staff_visits.enter_outer', '=', 'enter');
        })
        ->count();

    return $studentInsideCount + $staffInsideCount;
}


public function totalEnteredVisitsInRange($startDate, $endDate)
{
    // Adjust the start date to begin at 6am
    $startDate = Carbon::parse($startDate)->startOfDay()->addHours(6);

    // Adjust the end date to end at 6am of the next day
    $endDate = Carbon::parse($endDate)->addDay()->startOfDay()->addHours(6);
    $studentEnterCount = $this->studentVisits()
        ->where('enter_outer', 'enter')
        ->whereBetween(DB::raw('date(visit_datetime)'), [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d'),
        ])
        ->count();

    $staffEnterCount = $this->staffVisits()
        ->where('enter_outer', 'enter')
        ->whereBetween(DB::raw('date(visit_datetime)'), [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d'),
        ])
        ->count();

    return compact('studentEnterCount', 'staffEnterCount');
}

public function totalExitedVisitsInRange($startDate, $endDate)
{
    // Adjust the start date to begin at 6am
    $startDate = Carbon::parse($startDate)->startOfDay()->addHours(6);

    // Adjust the end date to end at 6am of the next day
    $endDate = Carbon::parse($endDate)->addDay()->startOfDay()->addHours(6);
    $studentExitCount = $this->studentVisits()
        ->where('enter_outer', 'outer')
        ->whereBetween(DB::raw('date(visit_datetime)'), [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d'),
        ])
        ->count();

    $staffExitCount = $this->staffVisits()
        ->where('enter_outer', 'outer')
        ->whereBetween(DB::raw('date(visit_datetime)'), [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d'),
        ])
        ->count();

    return compact('studentExitCount', 'staffExitCount');
}


}
