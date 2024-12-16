<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $students = Student::query();

    if ($request->filled('name')) {
        $students->where(function($query) use ($request) {
            $query->where('first_name', 'like', "%{$request->name}%")
                  ->orWhere('last_name', 'like', "%{$request->name}%");
        });
    }

    if ($request->filled('email')) {
        $students->where('email', 'like', "%{$request->email}%");
    }

    $students = $students->paginate(10); 

    if ($request->ajax()) {
        return response()->json([
            'students' => $students->items(),
            'links' => [
                'next' => $students->nextPageUrl(),
                'prev' => $students->previousPageUrl(),
            ],
        ]);
    }

    return view('students.index', compact('students'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required',
            'address_line_1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);

        Student::create($request->all());

        return response()->json(['success' => 'Student registered successfully!']);
    }


    public function getStates(Request $request, $country)
    {
        $path = storage_path('app/states.json'); 
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        
        $states = [];

        foreach ($data as $entry) {
            if (strtolower($entry['name']) == strtolower($country) || strtolower($entry['code2']) == strtolower($country)) {
                $states = $entry['states']; // Get the states for the specified country
                break;
            }
        }
        if (empty($states)) {
            return response()->json(['error' => 'States not found for the country: ' . $country], 404);
        }
        return response()->json($states);
    }

    public function getCities(Request $request, $stateCode)
    {
        $path = storage_path('app/city.json');
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        
        $cities = []; 
        $found = false; 
        foreach ($data as $entry) {
            foreach ($entry['states'] as $state) {
                if (strtolower($state['code']) == strtolower($stateCode)) {
                    if (isset($state['cities'])) {
                        $cities = $state['cities']; 
                    }
                    $found = true; 
                    break 2; 
                }
            }
        }
        
        if (!$found) {
            return response()->json(['error' => 'State not found: ' . $stateCode], 404);
        }
        
        return response()->json($cities); 
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'required|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $student = Student::findOrFail($id);
        $student->update($request->all());

        return response()->json(['success' => 'Student updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
 
    }
}
