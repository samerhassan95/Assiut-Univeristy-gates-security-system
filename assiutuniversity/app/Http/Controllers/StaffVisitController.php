<?php

namespace App\Http\Controllers;
use App\Models\StaffVisit;
use App\Models\Staff;
use Illuminate\Support\Facades\View; // Add this line

use Illuminate\Http\Request;

class StaffVisitController extends Controller
{

    public function getAllVisits(Request $request)
    {
        $query = StaffVisit::query();
    
        if ($request->filled('nationalID')) {
            $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
        }
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->input('position') . '%');
        }
        if ($request->filled('gate_name')) {
            $query->where('gate_name', 'like', '%' . $request->input('gate_name') . '%');
        }
        if ($request->filled('enter_outer')) {
            $query->where('enter_outer', 'like', '%' . $request->input('enter_outer') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->input('status') . '%');
        }
    
        if ($request->filled('from_datetime') && $request->filled('to_datetime')) {
            $fromDateTime = $request->input('from_datetime');
            $toDateTime = $request->input('to_datetime');
    
            $fromDateTime = Carbon::parse($fromDateTime)->startOfDay()->addHours(6);
        
            // Adjust the end date to end at 6am of the next day
            $toDateTime = Carbon::parse($toDateTime)->addDay()->startOfDay()->addHours(6);
            
            $query->whereBetween('visit_datetime', [$fromDateTime, $toDateTime]);
        }
        
        // Other filters and queries...
        
        $staffVisits = $query->get();
    return response()->json($staffVisits);
    }












    public function index(Request $request)
    {
        $perPage = 100; 
        $query = StaffVisit::query();
    
        // Apply filters if the input fields are filled
        if ($request->filled('nationalID')) {
            $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
        }
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->input('position') . '%');
        }
        if ($request->filled('gate_name')) {
            $query->where('gate_name', 'like', '%' . $request->input('gate_name') . '%');
        }
        if ($request->filled('enter_outer')) {
            $query->where('enter_outer', 'like', '%' . $request->input('enter_outer') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->input('status') . '%');
        }
    
        if ($request->filled('from_datetime') && $request->filled('to_datetime')) {
            $fromDateTime = $request->input('from_datetime');
            $toDateTime = $request->input('to_datetime');
            $fromDateTime = Carbon::parse($fromDateTime)->startOfDay()->addHours(6);
        
            // Adjust the end date to end at 6am of the next day
            $toDateTime = Carbon::parse($toDateTime)->addDay()->startOfDay()->addHours(6);
            
            $query->whereBetween('visit_datetime', [$fromDateTime, $toDateTime]);
        }
    
        // Subquery to fetch the last visits for each staff member
       $subQuery = StaffVisit::selectRaw('MAX(id) as id')->groupBy('nationalID');
        $query->whereIn('id', $subQuery);
        
        $query->select('nationalID', 'name', 'position','status', 'visit_datetime', 'gate_name', 'enter_outer');

        $staffVisits = $query->paginate($perPage);
        
    
        // You can customize the pagination view if needed
        $staffVisits->appends(request()->query());
        // Fetch the resulting staff visits

    
        if ($request->ajax()) {
            $html = View::make('staffvisits.index', compact('staffVisits'))->render();
            return response()->json(['html' => $html, 'data' => $staffVisits]);
        }
        
        return view('staffvisits.index', compact('staffVisits'));
    }
    
    
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nationalID' => 'required',
            'name' => 'required',
            'position' => 'required',
            'visit_datetime' => ['required', 'regex:/^\d{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])(\s\d{1,2}[:|,]\d{1,2}(:\d{1,2})?)?$/'],
            'gate_name' => 'required', // Include gate_name in the validation
        ]);
    
        $nationalId = $request->input('nationalID');
        $staff = Staff::where('nationalID', $nationalId)->first();
    
        if (!$staff) {
            return response()->json(['status' => 0, 'message' => 'Staff with this national ID not found'], 404);
        }
        $status = $staff->status;

        // Check the last visit status for the staff
        $lastVisit = StaffVisit::where('nationalID', $nationalId)
        ->orderBy('visit_datetime', 'desc')
        ->first();
    
    $enterOuter = $lastVisit ? ($lastVisit->enter_outer === 'enter' ? 'outer' : 'enter') : 'enter';
    
    // Store the new staff visit with the determined 'enter_outer' status
    $newStaffVisit = StaffVisit::create([
        'nationalID' => $nationalId,
        'name' => $request->input('name'),
        'position' => $request->input('position'),
        'visit_datetime' => $validatedData['visit_datetime'],
        'gate_name' => $validatedData['gate_name'],
        'enter_outer' => $enterOuter,
        'status' => $status // Include 'enter_outer' status
        // Add other fields as needed
    ]);
       // dd($newStaffVisit);
       $response = [
        'status' => 1,
        'message' => 'New staff visit created',
        'checkIn' => $enterOuter,
        // 'reloadPage' => true, // Indicate whether the page should be reloaded
    ];

    return response()->json($response, 201);    }
    
    
    

    
    
    
    public function create()
    {
        return view('staffvisits.create');
    }


    public function show(Request $request, $nationalID)
    {

        $perPage = 100; 
        $query = StaffVisit::where('nationalID', $nationalID)
            ->orderBy('visit_datetime', 'desc');

            if ($request->filled('gate_name')) {
                $query->where('gate_name', 'like', '%' . $request->input('gate_name') . '%');
            }
            if ($request->filled('enter_outer')) {
                $query->where('enter_outer', 'like', '%' . $request->input('enter_outer') . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', 'like', '%' . $request->input('status') . '%');
            }
    
        if ($request->filled('from_datetime') && $request->filled('to_datetime')) {
            $fromDateTime = date('Y-m-d H:i:s', strtotime($request->input('from_datetime')));
            $toDateTime = date('Y-m-d H:i:s', strtotime($request->input('to_datetime')));
    
            $query->whereBetween('visit_datetime', [$fromDateTime, $toDateTime]);
        }
    
        // Include 'gate_name' in the query
        $staffHistory = $query->select('nationalID', 'name', 'position', 'visit_datetime', 'gate_name','status','enter_outer')->paginate($perPage);
    
        $staffHistory->appends(request()->query());
        return view('staffvisits.history', [
            'staffHistory' => $staffHistory,
            'nationalID' => $nationalID,
        ]);
        // $staffVisits = $query->paginate($perPage);
        
    
        // You can customize the pagination view if needed
    }
    
    
    
    
    
    

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nationalID' => 'required',
            'name' => 'required',
            'position' => 'required',
            'visit_datetime' => 'required|date',
            'gate_name' => 'required', // Include gate_name in the validation
            // Ensure 'datetime' is an array
            // Add other validation rules for fields as needed
        ]);

        $staffVisit = StaffVisit::findOrFail($id);

        $staffVisit->update([
            'nationalID' => $request->input('nationalID'),
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'visit_datetime' => $validatedData['visit_datetime'],
            'gate_name' => $validatedData['gate_name'], // Update gate_name
            // Update the datetime array
            // Add other fields as needed
        ]);

        return response()->json(['message' => 'Staff visit updated successfully'], 200);
    }

    public function destroy($id)
    {
        $staffVisit = StaffVisit::findOrFail($id);
        $staffVisit->delete();

        return response()->json(['message' => 'Staff visit deleted successfully'], 200);
    }
}
