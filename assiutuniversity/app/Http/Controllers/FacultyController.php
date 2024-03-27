<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $query = Faculty::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $faculties = $query->get();
        return view('faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('faculties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add validation rules for other fields as needed
        ]);

        Faculty::create($request->all());

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty created successfully');
    }

    public function show($id)
    {
        $faculty = Faculty::findOrFail($id);
        return view('faculties.show', compact('faculty'));
    }

    public function edit($id)
    {
        $faculty = Faculty::findOrFail($id);
        return view('faculties.edit', compact('faculty'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add validation rules for other fields as needed
        ]);

        $faculty = Faculty::findOrFail($id);
        $faculty->update($request->all());

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty updated successfully');
    }

    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty deleted successfully');
    }
}
