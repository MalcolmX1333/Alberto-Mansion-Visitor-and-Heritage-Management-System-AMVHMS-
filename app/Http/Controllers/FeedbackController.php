<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class FeedbackController extends Controller
{
    public function create()
    {
        $survey = Survey::where('name', 'Visitor Feedback')->first();
        return view('survey.feedback', ['survey' => $survey]);
    }

   public function store(Request $request)
{
    $survey = $this->survey();
    $deviceIdentifier = Cookie::get('device_identifier');

    // If the cookie does not exist, create a new unique identifier
    if (!$deviceIdentifier) {
        $deviceIdentifier = $request->ip() . '-' . Str::random(40);
        $expiresAt = Carbon::now()->setTimezone('Asia/Manila')->endOfDay();
        Cookie::queue('device_identifier', $deviceIdentifier, $expiresAt->diffInMinutes());
    }

    // Check if the user has already submitted a response today
    $existingEntry = Entry::where('survey_id', $survey->id)
        ->where('device_identifier', $deviceIdentifier)
        ->whereDate('created_at', Carbon::today())
        ->first();

    if ($existingEntry) {
        return redirect()->back()->with('error', 'You have already submitted a response today.');
    }

    // Validate the request data
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'feedback' => 'required|string|max:255',
        'ease_of_navigation' => 'required|integer|min:1|max:5',
        'ar_features' => 'required|integer|min:1|max:5',
        'ar_experience' => 'required|integer|min:1|max:5',
        'recommend_app' => 'required|integer|min:1|max:5',
        'improve_app' => 'nullable|string|max:255',
        'office_help' => 'required|integer|min:1|max:5',
        'service_satisfaction' => 'required|integer|min:1|max:5',
        'staff_knowledge' => 'required|integer|min:1|max:5',
        'response_clarity' => 'required|integer|min:1|max:5',
    ]);

    // Create a new entry with the validated rating
    $entry = new Entry();
    $entry->device_identifier = $deviceIdentifier;
    $entry->survey_id = $survey->id;
    $entry->created_at = Carbon::now()->setTimezone('Asia/Manila');
    $entry->updated_at = Carbon::now()->setTimezone('Asia/Manila');
    $entry->save();

    // Save the answers to the entry
    $entry->answers()->create([
        'question_id' => 14, // Assuming question_id 14 is for the rating question
        'value' => $request->input('rating'),
    ]);

    $entry->answers()->create([
        'question_id' => 15, // Assuming question_id 15 is for the feedback question
        'value' => $request->input('feedback'),
    ]);

    // Save the new questions for APPLICATION
    $entry->answers()->create([
        'question_id' => 16, // Assuming question_id 16 is for the ease of navigation question
        'value' => $request->input('ease_of_navigation'),
    ]);

    $entry->answers()->create([
        'question_id' => 17, // Assuming question_id 17 is for the AR features question
        'value' => $request->input('ar_features'),
    ]);

    $entry->answers()->create([
        'question_id' => 18, // Assuming question_id 18 is for the AR experience question
        'value' => $request->input('ar_experience'),
    ]);

    $entry->answers()->create([
        'question_id' => 19, // Assuming question_id 19 is for the recommend app question
        'value' => $request->input('recommend_app'),
    ]);

    $entry->answers()->create([
        'question_id' => 20, // Assuming question_id 20 is for the improve app question
        'value' => $request->input('improve_app'),
    ]);

    // Save the new questions for MUSEUM
    $entry->answers()->create([
        'question_id' => 21, // Assuming question_id 21 is for the office help question
        'value' => $request->input('office_help'),
    ]);

    $entry->answers()->create([
        'question_id' => 22, // Assuming question_id 22 is for the service satisfaction question
        'value' => $request->input('service_satisfaction'),
    ]);

    $entry->answers()->create([
        'question_id' => 23, // Assuming question_id 23 is for the staff knowledge question
        'value' => $request->input('staff_knowledge'),
    ]);

    $entry->answers()->create([
        'question_id' => 24, // Assuming question_id 24 is for the response clarity question
        'value' => $request->input('response_clarity'),
    ]);

    $currentTime = Carbon::now()->setTimezone('Asia/Manila')->toDateTimeString();

    return redirect()->back()->with('success', 'Congratulations on successfully completing your visit of Alberto Mansion.');
}

    protected function survey()
    {
        return Survey::where('name', 'Visitor Feedback')->first();
    }
}
