<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::paginate();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'national_number' => 'required|unique:patients',
            'gender' => 'required',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        // Generate unique medical number
        $medicalNumber = strtoupper(Str::random(2)) . '-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        Patient::create([
            'name' => $request->name,
            'national_number' => $request->national_number,
            'gender' => $request->gender,
            'medical_number' => $medicalNumber,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('patients.index');
    }

}
