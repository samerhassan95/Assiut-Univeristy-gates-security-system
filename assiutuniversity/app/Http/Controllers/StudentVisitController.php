<?php

namespace App\Http\Controllers;


use Validator;
use App\Models\StudentVisit;
use App\Models\Student;
use Illuminate\Support\Facades\View; // Add this line

use Illuminate\Http\Request;

class StudentVisitController extends Controller
{

    public function getStudentVisits(Request $request)
    {
        $query = StudentVisit::query();

        if ($request->filled('nationalID')) {
            $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('faculty')) {
            $query->where('faculty', 'like', '%' . $request->input('faculty') . '%');
        }
        if ($request->filled('gate_name')) {
            $query->where('gate_name', 'like', '%' . $request->input('gate_name') . '%');
        }
        if ($request->filled('enter_outer')) {
            $query->where('enter_outer', 'like', '%' . $request->input('enter_outer') . '%');
        }

        if ($request->filled('from_datetime') && $request->filled('to_datetime')) {
            $fromDateTime = $request->input('from_datetime');
            $toDateTime = $request->input('to_datetime');
    
            $fromDateTime = Carbon::parse($fromDateTime)->startOfDay()->addHours(6);
        
            // Adjust the end date to end at 6am of the next day
            $toDateTime = Carbon::parse($toDateTime)->addDay()->startOfDay()->addHours(6);
            
            $query->whereBetween('visit_datetime', [$fromDateTime, $toDateTime]);
        }

        $studentVisits = $query->get();
        return response()->json($studentVisits);
    }








    public function index(Request $request)
    {
        $perPage = 100; 
        $query = StudentVisit::query();
    
        // Apply filters if the input fields are filled
        if ($request->filled('nationalID')) {
            $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
        }
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->filled('faculty')) {
            $query->where('faculty', 'like', '%' . $request->input('faculty') . '%');
        }
    
        if ($request->filled('gate_name')) {
            $query->where('gate_name', 'like', '%' . $request->input('gate_name') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', '=', $request->input('status'));
        }
        
        if ($request->filled('enter_outer')) {
            $query->where('enter_outer', 'like', '%' . $request->input('enter_outer') . '%');
        }
        if ($request->filled('from_datetime') && $request->filled('to_datetime')) {
            $fromDateTime = $request->input('from_datetime');
            $toDateTime = $request->input('to_datetime');
            $fromDateTime = Carbon::parse($fromDateTime)->startOfDay()->addHours(6);
        
            // Adjust the end date to end at 6am of the next day
            $toDateTime = Carbon::parse($toDateTime)->addDay()->startOfDay()->addHours(6);
            
            $query->whereBetween('visit_datetime', [$fromDateTime, $toDateTime]);
        }
    
        // Subquery to fetch the last visits for each student
        $subQuery = StudentVisit::selectRaw('MAX(id) as id')->groupBy('nationalID');
        $query->whereIn('id', $subQuery);
    
        $query->select('nationalID', 'name', 'faculty', 'visit_datetime','status', 'gate_name', 'enter_outer');

        // Fetch the resulting student visits
        $studentVisits = $query->paginate($perPage);
        
    
        // You can customize the pagination view if needed
        $studentVisits->appends(request()->query());
    
        // return view('studentvisits.index', compact('studentVisits'));
        if ($request->ajax()) {
            $html = View::make('studentvisits.index', compact('studentVisits'))->render();
        
            // Return the latest student visits data as JSON
            return response()->json(['html' => $html, 'data' => $studentVisits]); // Include 'data' key
        }
        
        return view('studentvisits.index', compact('studentVisits'));
    }        
    
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nationalID' => 'required',
            'name' => 'required',
            'faculty' => 'required',
            'status' => 'required',
            'visit_datetime' => ['required', 'regex:/^\d{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])(\s\d{1,2}[:|,]\d{1,2}(:\d{1,2})?)?$/'],
            'gate_name' => 'required',
        ]);
    
        if ($validator->fails()) {
            $response['response'] = $validator->messages();
            return response()->json($response, 400);
        }
    
        $nationalId = $request->input('nationalID');
        $student = Student::where('nationalID', $nationalId)->first();
        
        if (!$student) {
            return response()->json(['status' => 0, 'message' => 'Student with this national ID not found'], 404);
        }
    
        // Parse the visit datetime input to convert it into the standard format Y-m-d H:i:s
        $visitDatetime = $request->input('visit_datetime');
        if (strpos($visitDatetime, ':') !== false) {
            // Split the datetime string into date and time parts
            list($datePart, $timePart) = explode(' ', $visitDatetime);
            $timeParts = preg_split("/[:|,]/", $timePart);
            $hour = $timeParts[0];
            $minute = isset($timeParts[1]) ? $timeParts[1] : '00';
            $second = isset($timeParts[2]) ? $timeParts[2] : '00';
            // Reformat the time part
            $visitDatetime = $datePart . ' ' . sprintf("%02d", $hour) . ':' . sprintf("%02d", $minute) . ':' . sprintf("%02d", $second);
        }
    
        $lastVisit = StudentVisit::where('nationalID', $nationalId)
            ->orderBy('visit_datetime', 'desc')
            ->first();
    
        $enterOuter = $lastVisit ? ($lastVisit->enter_outer === 'enter' ? 'outer' : 'enter') : 'enter';
    
        // Store the new student visit
        $newStudentVisit = StudentVisit::create([
            'nationalID' => $nationalId,
            'name' => $request->input('name'),
            'faculty' => $request->input('faculty'),
            'status' => $request->input('status'),
            'visit_datetime' => $visitDatetime, // Use the parsed datetime value
            'gate_name' => $request->input('gate_name'),
            'enter_outer' => $enterOuter,
        ]);
    
        $response = [
            'status' => 1,
            'message' => 'New student visit created',
            'checkIn' => $enterOuter,
        ];
    
        return response()->json($response, 201);
    }
    

    public function create()
    {
        return view('studentvisits.create');
    }

    public function show(Request $request, $nationalID)
    {
        $perPage = 100; 
        $query = StudentVisit::where('nationalID', $nationalID)
            ->orderBy('visit_datetime', 'desc');
        
            if ($request->filled('gate_name')) {
                $query->where('gate_name', 'like', '%' . $request->input('gate_name') . '%');
            }
            if ($request->filled('enter_outer')) {
                $query->where('enter_outer', 'like', '%' . $request->input('enter_outer') . '%');
            }
        
        if ($request->filled('from_datetime') && $request->filled('to_datetime')) {
            $fromDateTime = $request->input('from_datetime');
            $toDateTime = $request->input('to_datetime');
    
            $query->whereBetween('visit_datetime', [$fromDateTime, $toDateTime]);
        }
    
        $studentHistory = $query->select('nationalID', 'name', 'faculty', 'visit_datetime', 'gate_name','enter_outer')->paginate($perPage);
        $studentHistory->appends(request()->query());
        return view('studentvisits.history', [
            'studentHistory' => $studentHistory,
            'nationalID' => $nationalID,
        ]);
    }
    

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nationalID' => 'required',
            'name' => 'required',
            'faculty' => 'required',
            'status' => 'required',
            'visit_datetime' => 'required|date',
        ]);

        $studentVisit = StudentVisit::findOrFail($id);

        $studentVisit->update([
            'nationalID' => $request->input('nationalID'),
            'name' => $request->input('name'),
            'faculty' => $request->input('faculty'),
            'status' => $request->input('status'),
            'visit_datetime' => $validatedData['visit_datetime'],
        ]);

        return response()->json(['message' => 'Student visit updated successfully'], 200);
    }

    public function destroy($id)
    {
        $studentVisit = StudentVisit::findOrFail($id);
        $studentVisit->delete();

        return response()->json(['message' => 'Student visit deleted successfully'], 200);
    }
}
