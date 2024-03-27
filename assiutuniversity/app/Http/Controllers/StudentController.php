<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
class StudentController extends Controller
{

    public function getBySerial($serialNumber)
    {

        // Find the student by serial number
        $student = Student::where('serial_number', $serialNumber)->first();
    
        // If the student with the given serial number exists, return it
        if ($student) {
            return response()->json($student);
        } else {
            // If the student with the given serial number doesn't exist, return an error
            return response()->json(['error' => 'Student not found for the given serial number'], 404);
        }
    }
    
    










    public function index(Request $request)
    {
        // Set the number of records per page
        $perPage = 100; // You can adjust this number based on your preferences
    
        $query = Student::query();
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->filled('nationalID')) {
            $query->where('nationalID', 'like', '%' . $request->input('nationalID') . '%');
        }
    
        if ($request->filled('faculty')) {
            $query->where('faculty', 'like', '%' . $request->input('faculty') . '%');
        }
    
        if ($request->filled('level')) {
            $query->where('level', 'like', '%' . $request->input('level') . '%');
        }
    
        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->input('status') . '%');
        }
    
        // Add other search filters as needed
    
        // Get the paginated results
        $students = $query->paginate($perPage);
    
        // You can customize the pagination view if needed
        $students->appends(request()->query());
    
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
{
    // Validate the incoming data
    $validatedData = $request->validate([
        'name' => 'required',
        'nationalID' => 'required|unique:students',
        'faculty' => 'required',
        'level' => 'required',
        'status' => 'required',
        // Add other fields and validation rules as needed
    ]);

    // Create a new Student instance with the validated data
    $student = new Student();
    $student->name = $validatedData['name'];
    $student->nationalID = $validatedData['nationalID'];
    $student->faculty = $validatedData['faculty'];
    $student->level = $validatedData['level'];
    $student->status = $validatedData['status'];
    // Set other attributes here...

    // Save the student to the database
    $student->save();

    // Redirect to the students index page after successful creation
    return redirect()->route('students.index')->with('success', 'Student created successfully');
}


    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required',
            'nationalID' => 'required|unique:students,nationalID,'.$id,
            'faculty' => 'required',
            'level' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

    // Find the student by ID
    $student = Student::findOrFail($id);

    // Update the student record with the validated data
    $student->name = $validatedData['name'];
    $student->nationalID = $validatedData['nationalID'];
    $student->faculty = $validatedData['faculty'];
    $student->level = $validatedData['level'];
    $student->status = $validatedData['status'];
    $student->serial_number = $validatedData['serial_number'];
    // Update other fields as needed...

    // Save the updated student record
    $student->save();

    // Redirect to the students index page after successful update
    return redirect()->route('students.index')
        ->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function updateSerial(Request $request, $nationalID)
    {
        // Find the student by their national ID
        $student = Student::where('nationalID', $nationalID)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found with the provided national ID'], 404);
        }

        // Validate the incoming data (serial number)
        $validatedData = $request->validate([
            'serial_number' => 'required|string', // Validation rules for the serial number
        ]);

        // Update the student record with the validated serial number
        $student->serial_number = $validatedData['serial_number'];

        // Save the updated student record
        $student->save();

        // Respond with a success message and the updated student data
        return response()->json(['success' => 'Serial number updated successfully', 'student' => $student]);
    }
}
