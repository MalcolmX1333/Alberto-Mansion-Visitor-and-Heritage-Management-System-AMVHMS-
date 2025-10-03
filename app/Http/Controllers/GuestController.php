<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;

class GuestController extends Controller
{
   public function create()
   {
       $survey = Survey::where('name', 'Visitor Information')->first();

       // Check if user was redirected from login after authentication
       if (session('survey_redirect')) {
           session()->forget('survey_redirect'); // Clear the session flag
           return view('survey.guest', ['survey' => $survey, 'show_welcome_swal' => true]);
       }

       return view('survey.guest', ['survey' => $survey]);
   }

    public function store(Request $request)
    {
        $deviceIdentifier = Cookie::get('device_identifier');

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit the survey.');
        }

        // Validate the custom fields and survey data first to get visit_datetime
        $request->validate([
            'registration_type' => 'required|in:Individual,Group',
            'visit_datetime' => 'required|date',
            'cn_bus_number' => $request->registration_type === 'Group' ? 'required|string' : 'nullable|string',
            'full_name' => 'required|string',
            'address_affiliation' => 'required|string',
            'nationality' => 'required|string',
            'gender' => 'required|in:Male,Female',
            // Demographics fields - only required for Group registration
            'grade_school_students' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'high_school_students' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'college_students' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'pwd' => $request->registration_type === 'Group' ? 'required|string' : 'nullable|string',
            'age_17_below' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'age_18_30' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'age_31_45' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'age_60_above' => $request->registration_type === 'Group' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
        ]);

        // Check if there is an event on the selected visit date
        $visitDate = Carbon::parse($request->input('visit_datetime'))->toDateString();
        $eventExists = \App\Models\Event::whereDate('start_date', '<=', $visitDate)
            ->whereDate('end_date', '>=', $visitDate)
            ->exists();
        if ($eventExists) {
            return redirect()->back()->with('error', 'There is an event scheduled for your selected visit date. Please choose another date.');
        }

        $survey = $this->survey();
        $participantId = auth()->id();

        // Check if the user has already submitted a response today
        $existingEntry = Entry::where('survey_id', $survey->id)
            ->where('participant_id', $participantId)
            ->whereDate('created_at', Carbon::today())
            ->first();


        Log::info($existingEntry);

        if ($existingEntry) {
            return redirect()->back()->with('error', 'You have already submitted a response today.');
        }

        Log::info($request->all());

        // Create a new entry
        $entry = new Entry();
        $entry->survey_id = $survey->id;
        $entry->device_identifier = $deviceIdentifier;
        $entry->participant_id = Auth()->check() ? auth()->id() : null;
        $entry->registration_type = $request->input('registration_type') === 'Group' ? 1 : 0; // Convert to boolean
        $entry->created_at = Carbon::now()->setTimezone('Asia/Manila');
        $entry->updated_at = Carbon::now()->setTimezone('Asia/Manila');
        $entry->save();

        // Save the custom answers manually
        $entry->answers()->create([
            'question_id' => 1, // Registration Type
            'value' => $request->input('registration_type'),
        ]);

        $entry->answers()->create([
            'question_id' => 2, // Visit Date and Time
            'value' => $request->input('visit_datetime'),
        ]);

        // Save the C.N. Bus Number only for Group registration
        if ($request->registration_type === 'Group') {
            $entry->answers()->create([
                'question_id' => 3, // C.N. Bus Number
                'value' => $request->input('cn_bus_number'),
            ]);
        }

        $entry->answers()->create([
            'question_id' => 4, // Full name
            'value' => $request->input('full_name'),
        ]);

        $entry->answers()->create([
            'question_id' => 5, // Address/Affiliation
            'value' => $request->input('address_affiliation'),
        ]);

        $entry->answers()->create([
            'question_id' => 6, // Nationality
            'value' => $request->input('nationality'),
        ]);

        $entry->answers()->create([
            'question_id' => 7, // Gender
            'value' => $request->input('gender'),
        ]);

        // Save demographics data only if registration type is Group
        if ($request->registration_type === 'Group') {
            $entry->answers()->create([
                'question_id' => 8, // No. of Students / Grade School
                'value' => $request->input('grade_school_students'),
            ]);

            $entry->answers()->create([
                'question_id' => 9, // No. of Students / High School
                'value' => $request->input('high_school_students'),
            ]);

            $entry->answers()->create([
                'question_id' => 10, // No. of Students / College / GradSchool
                'value' => $request->input('college_students'),
            ]);

            $entry->answers()->create([
                'question_id' => 11, // PWD
                'value' => $request->input('pwd'),
            ]);

            $entry->answers()->create([
                'question_id' => 12, // 17 y/o and below
                'value' => $request->input('age_17_below'),
            ]);

            $entry->answers()->create([
                'question_id' => 13, // 18-30 y/o
                'value' => $request->input('age_18_30'),
            ]);

            $entry->answers()->create([
                'question_id' => 14, // 31-45 y/o
                'value' => $request->input('age_31_45'),
            ]);

            $entry->answers()->create([
                'question_id' => 15, // 60 y/o and above
                'value' => $request->input('age_60_above'),
            ]);
        }

        return redirect()->route('guest.reservation.index')->with('success', 'Survey submitted successfully!');
    }

    protected function survey()
    {
        return Survey::where('name', 'Visitor Information')->first();
    }
}
