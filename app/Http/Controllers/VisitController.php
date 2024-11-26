<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeNewVisitRequest;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Patient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VisitController extends Controller
{
    public function todaysVisits()
    {
        $today = Carbon::today();

        $visits = Visit::whereDate('checked_at', $today)->orderBy('checked_at', 'desc')->paginate(10);
        return view('visits.todays_visits', compact('visits'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'national_number' => 'required',
                'gender' => 'required',
                'address' => 'nullable|string',
                'phone_number' => 'nullable|string',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422); // HTTP status code for Unprocessable Entity
            }

            DB::beginTransaction();

            // Find patient by national number
            $patient = Patient::where('national_number', $request->national_number)->first();

            if ($patient) {
                // Check if the patient has an open visit
                $openVisit = $patient->visits()->whereNull('checked_out_at')->first();
                if ($openVisit) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'هذا المريض لديه زيارة مفتوحة بالفعل. يرجى تسجيل خروج الزيارة السابقة قبل إنشاء زيارة جديدة.',
                    ], 400);
                }
            } else {
                // Patient does not exist, create a new patient with a unique medical number
                $medical_number = strtoupper(Str::random(2)) . '-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

                $patient = Patient::create([
                    'name' => $request->name,
                    'national_number' => $request->national_number,
                    'gender' => $request->gender,
                    'medical_number' => $medical_number,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                ]);
            }

            // Create a new visit for the patient
            $patient->visits()->create([
                'visit_type' => $request->visit_type,
                'checked_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'تم تسجيل الزيارة بنجاح.');
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => "حدث خطأ ما: {$exception->getMessage()}"
            ], 500); // HTTP status code for Internal Server Error
        }
    }

    public function checkOut(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $visit->update(['checked_out_at' => now()]);

        return redirect()->back()->with('success', 'Patient successfully checked out.');
    }

    public function index()
    {
        $visits = Visit::with('patient')->paginate(10);
        
        return view('visits.index', compact('visits'));
    }

    public function cancel(Visit $visit)
    {
        $visit->delete();

        return response()->json(['message' => 'Visit canceled successfully.']);
    }

    public function printSticker($visitId)
    {
        $visit = Visit::findOrFail($visitId);

        return view('visits.sticker', compact('visit'));
    }
}
