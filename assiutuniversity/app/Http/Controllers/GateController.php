<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\StudentVisit;
use App\Models\StaffVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class GateController extends Controller
{
    public function getAllGates(Request $request)
    {
        $query = Gate::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $gates = $query->get();
        return response()->json($gates);    }

    public function index(Request $request)
    {
        $query = Gate::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $gates = $query->get();
        return view('gates.index', compact('gates'));
    }

    public function create()
    {
        return view('gates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Gate::create($request->all());

        return redirect()->route('gates.index')
            ->with('success', 'Gate created successfully');
    }

    public function show($id)
    {
        $gate = Gate::findOrFail($id);
        return view('gates.show', compact('gate'));
    }

    public function edit($id)
    {
        $gate = Gate::findOrFail($id);
        return view('gates.edit', compact('gate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $gate = Gate::findOrFail($id);
        $gate->update($request->all());

        return redirect()->route('gates.index')
            ->with('success', 'Gate updated successfully');
    }

    public function destroy($id)
    {
        $gate = Gate::findOrFail($id);
        $gate->delete();

        return redirect()->route('gates.index')
            ->with('success', 'Gate deleted successfully');
    }

// GateController.php

// GateController.php

// GateController.php

// GateController.php

public function dashboard(Request $request)
{
    $gates = Gate::with([
        'studentEnteredToday',
        'studentOuterToday',
        'staffEnteredToday',
        'staffOuterToday',
        'totalEnteredVisits',
        'totalExitedVisits',
    ])->get();

    foreach ($gates as $gate) {
        $gate->loadCount([
            'studentEnteredToday',
            'studentOuterToday',
            'staffEnteredToday',
            'staffOuterToday',
            'totalEnteredVisits',
            'totalExitedVisits',
        ]);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $gate->studentEnteredByFaculty()->whereBetween('visit_datetime', [$request->input('start_date'), $request->input('end_date')]);
            $gate->studentExitedByFaculty()->whereBetween('visit_datetime', [$request->input('start_date'), $request->input('end_date')]);
            // Add more relationships as needed
            $gate->load([
                'studentEnteredByFaculty',
                'studentExitedByFaculty',
            ]);
        }
        // You can add more load statements if needed

        $gate->details = $gate->studentVisits()
            ->whereDate('visit_datetime', now()->toDateString())
            ->get();
    }
// dd($gates);
    return view('stats', compact('gates'));
}






public function gateDetails($gateId, Request $request)
{
    $gate = Gate::with(['studentVisits', 'staffVisits'])->find($gateId);

    $studentEnteredByFaculty = $gate->studentEnteredByFaculty();
    $studentExitedByFaculty = $gate->studentExitedByFaculty();
    $staffEnteredCount = $gate->staffEnteredCount(); // Remove get() here
    $staffExitedCount = $gate->staffExitedCount(); // Remove get() here
    $disallowedStaffCount = $gate->disallowedStaffCount(); // Add this line
    $disallowedStaffCount = $gate->disallowedStaffCount(); // Add this line

    // Initialize $startDate and $endDate
    $startDate = null;
    $endDate = null;

    // Apply date range filtering if provided
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();

        $gate->totalEnteredInRange = $gate->totalEnteredVisitsInRange($startDate, $endDate);
        $gate->totalExitedInRange = $gate->totalExitedVisitsInRange($startDate, $endDate);

        $studentEnteredByFaculty->whereBetween('visit_datetime', [$startDate, $endDate]);
        $studentExitedByFaculty->whereBetween('visit_datetime', [$startDate, $endDate]);

        // Apply date range filtering for staff counts as well
        $staffEnteredCount->whereBetween('visit_datetime', [$startDate, $endDate]);
        $staffExitedCount->whereBetween('visit_datetime', [$startDate, $endDate]);
    } else {
        // Default to today's date if no date range is provided
        $studentEnteredByFaculty->whereDate('visit_datetime', now()->toDateString());
        $studentExitedByFaculty->whereDate('visit_datetime', now()->toDateString());
    }

    // Get the actual data after applying filters
    $studentEnteredByFaculty = $studentEnteredByFaculty->get();
    $studentExitedByFaculty = $studentExitedByFaculty->get();
    $staffEnteredCount = $staffEnteredCount->get(); // Call get() to execute the query
    $staffExitedCount = $staffExitedCount->get(); // Call get() to execute the query
    // $disallowedStaffCount = $disallowedStaffCount->count(); // Add this line

    // Pass $startDate and $endDate to the view
    return view('gate_details', compact('gate', 'studentEnteredByFaculty', 'studentExitedByFaculty', 'staffEnteredCount', 'staffExitedCount', 'startDate', 'endDate'));
}


public function disallowedStaffCount()
{
    return $this->staffVisits()
        ->where('status', 'disallowed');
}

// use Illuminate\Support\Facades\View;

// ...

public function updateGateDetails($gateId)
{
    try {
        $gate = Gate::findOrFail($gateId);

        // Load the necessary relationships and counts
        $gate->load([
            'studentEnteredToday',
            'studentOuterToday',
            'staffEnteredToday',
            'staffOuterToday',
            'totalEnteredVisits',
            'totalExitedVisits',
        ]);

        $gate->loadCount([
            'studentEnteredToday',
            'studentOuterToday',
            'staffEnteredToday',
            'staffOuterToday',
            'totalEnteredVisits',
            'totalExitedVisits',
        ]);

        // Additional details for disallowed counts
        // You can add more details as needed

        return response()->json([
            'studentEnteredTodayCount' => $gate->student_entered_today_count,
            'studentOuterTodayCount' => $gate->student_outer_today_count,
            'staffEnteredTodayCount' => $gate->staff_entered_today_count,
            'staffOuterTodayCount' => $gate->staff_outer_today_count,
            'totalEnteredVisitsCount' => $gate->total_entered_visits_count,
            'totalExitedVisitsCount' => $gate->total_exited_visits_count,

        ]);
    } catch (\Exception $e) {
        // Log the exception for debugging
        \Log::error('Error in updateGateDetails: ' . $e->getMessage());
        \Log::error($e);

        // Return an error response
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}








}
