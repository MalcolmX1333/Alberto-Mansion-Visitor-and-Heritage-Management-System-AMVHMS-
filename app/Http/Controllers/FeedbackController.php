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
        $deviceIdentifier = Cookie::get('device_identifier');


        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit feedback.');
        }

        \Log::info($request->all());
        $survey = $this->survey();
        $participantId = auth()->id();

        // Check if the user has already submitted a response today
        $existingEntry = Entry::where('survey_id', $survey->id)
            ->where('participant_id', $participantId)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($existingEntry) {
            return redirect()->back()->with('error', 'You have already submitted a response today.');
        }

        // Validate the request data based on the form fields
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|max:1000',
            'office_help' => 'required|integer|min:1|max:5',
            'service_satisfaction' => 'required|integer|min:1|max:5',
            'staff_knowledge' => 'required|integer|min:1|max:5',
            'response_clarity' => 'required|integer|min:1|max:5',
        ]);

        // Create a new entry
        $entry = new Entry();
        $entry->device_identifier = $deviceIdentifier;
        $entry->survey_id = $survey->id;
        $entry->participant_id = Auth()->check() ? auth()->id() : null;
        $entry->created_at = Carbon::now()->setTimezone('Asia/Manila');
        $entry->updated_at = Carbon::now()->setTimezone('Asia/Manila');
        $entry->save();

        // Get questions dynamically from the survey
        $questions = $survey->questions()->orderBy('id')->get();
        $sectionQuestions = $survey->sections()->where('name', 'MUSEUM')->first()->questions()->orderBy('id')->get();

        // Save answers for main survey questions
        if ($questions->count() >= 2) {
            $entry->answers()->create([
                'question_id' => $questions[0]->id, // "How is your Visit?" rating question
                'value' => $request->input('rating'),
            ]);

            $entry->answers()->create([
                'question_id' => $questions[1]->id, // "Feedback" text question
                'value' => $request->input('feedback'),
            ]);
        }

        // Save answers for MUSEUM section questions
        if ($sectionQuestions->count() >= 4) {
            $entry->answers()->create([
                'question_id' => $sectionQuestions[0]->id, // office help
                'value' => $request->input('office_help'),
            ]);

            $entry->answers()->create([
                'question_id' => $sectionQuestions[1]->id, // service satisfaction
                'value' => $request->input('service_satisfaction'),
            ]);

            $entry->answers()->create([
                'question_id' => $sectionQuestions[2]->id, // staff knowledge
                'value' => $request->input('staff_knowledge'),
            ]);

            $entry->answers()->create([
                'question_id' => $sectionQuestions[3]->id, // response clarity
                'value' => $request->input('response_clarity'),
            ]);
        }

        return redirect()->back()->with('success', 'Congratulations on successfully completing your visit of Alberto Mansion.');
    }

    protected function survey()
    {
        return Survey::where('name', 'Visitor Feedback')->first();
    }
}
