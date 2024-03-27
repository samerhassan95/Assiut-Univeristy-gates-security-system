<?php

namespace App\Http\Controllers;
// use Illuminate\Http\Response;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class StaffController extends Controller
{
    public function getAllStaff(Request $request)
    {
        
            $query = Staff::query();
    
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }
    
            if ($request->filled('nationalID')) {
                $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
            }
    
            if ($request->filled('job')) {
                $query->where('job', 'like', '%' . $request->input('job') . '%');
            }
    
            if ($request->filled('place')) {
                $query->where('place', 'like', '%' . $request->input('place') . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', 'like', '%' . $request->input('status') . '%');
            }
    
            // Add other filters for additional columns as needed
    
            $staff = $query->get();
            // $staff = Staff::all();
        return response()->json($staff);
        // You can return a view or transform data in any way you prefer
    }


    // public function getStaffByNationalID($nationalID)
    // {
    //     try {
    //         $staff = Staff::where('nationalID', $nationalID)->get();
    
    //         if ($staff->isEmpty()) {
    //             return response()->json(['message' => 'No staff found with the given National ID'], 404);
    //         }
    
    //         return response()->json(['staff' => $staff]);
    //     } catch (\Exception $e) {
    //         \Log::error($e->getMessage());
    //         return response()->json(['message' => 'Server Error'], 500);
    //     }
    // }









    public function index(Request $request)
    {
        try {
            $perPage = 100; 
        $query = Staff::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('nationalID')) {
            $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
        }

        if ($request->filled('job')) {
            $query->where('job', 'like', '%' . $request->input('job') . '%');
        }

        if ($request->filled('place')) {
            $query->where('place', 'like', '%' . $request->input('place') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->input('status') . '%');
        }
        // Add other filters for additional columns as needed

        $staff = $query->paginate($perPage);
        // $staff = Staff::all();
        if ($request->expectsJson()) {
            return Response::json(['staff' => $staff]);
        }
        $staff->appends(request()->query());
        return view('staff.index', compact('staff'));
    } catch (\Exception $e) {
        // Log the exception or handle it appropriately
        \Log::error($e->getMessage());

        // Return a JSON response with an error message
        return response()->json(['message' => 'Server Error'], 500);
    }
    }
    public function create()
    {
        return view('staff.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nationalID' => 'required|unique:students',
            'job' => 'required',
            'place' => 'required',
            'status' => 'required',
            // Add validation rules for other fields as needed
        ]);

        // if ($validatedData->fails()) {
        //     return response()->json(['error' => $validatedData->errors()], 400);
        // }

    // Create a new Student instance with the validated data
    $staff = new Staff();
    $staff->name = $validatedData['name'];
    $staff->nationalID = $validatedData['nationalID'];
    $staff->job = $validatedData['job'];
    $staff->place = $validatedData['place'];
    $staff->status = $validatedData['status'];
    // Set other attributes here...

    // Save the student to the database
    $staff->save();

    // Redirect to the students index page after successful creation
    return redirect()->route('staff.index')->with('success', 'Staff created successfully');
}

    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return view('staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            // Add validation rules for other fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $staff = Staff::findOrFail($id);
        $staff->update($request->all());

        return redirect()->route('staff.index')
        ->with('success', 'Staff updated successfully');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')
        ->with('success', 'Staff updated successfully');
    }
}


