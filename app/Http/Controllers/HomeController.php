<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MattDaneshvar\Survey\Models\Answer;
use MattDaneshvar\Survey\Models\Entry;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get visits today and this month (only visited entries)
        $visitToday = Entry::where('isVisited', true)
            ->whereDate('entries.created_at', today())
            ->count();

        $visitMonth = Entry::where('isVisited', true)
            ->whereMonth('entries.created_at', now()->month)
            ->whereYear('entries.created_at', now()->year)
            ->count();

        return view('home', compact('visitToday', 'visitMonth'));
    }

    public function ageDemographics(Request $request)
    {
        $filter = $request->get('filter', 'week');
        $dateFilter = $this->getDateFilter($filter);

        // Get age demographics from visited entries only
        $entries = Entry::select('entries.*', 'answers.value', 'questions.content')
            ->join('answers', 'entries.id', '=', 'answers.entry_id')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->where('entries.isVisited', true)
            ->where('entries.created_at', '>=', $dateFilter)
            ->whereIn('questions.content', ['Registration Type', '17 y/o and below', '18-30 y/o', '31-45 y/o', '60 y/o and above'])
            ->get();

        $ageCounts = [
            'seventeen' => 0,
            'thirty' => 0,
            'fortyfive' => 0,
            'sixty' => 0
        ];

        // Group entries by entry ID
        $groupedEntries = $entries->groupBy('id');

        foreach ($groupedEntries as $entryId => $entryAnswers) {
            $registrationType = null;
            $ageData = [];

            foreach ($entryAnswers as $answer) {
                if ($answer->content === 'Registration Type') {
                    $registrationType = $answer->value;
                } else {
                    $ageData[$answer->content] = (int)$answer->value;
                }
            }

            if ($registrationType === 'Individual') {
                // For individual, increment all age groups by 1
                $ageCounts['seventeen'] += 1;
                $ageCounts['thirty'] += 1;
                $ageCounts['fortyfive'] += 1;
                $ageCounts['sixty'] += 1;
            } else {
                // For groups, use the actual counts
                $ageCounts['seventeen'] += $ageData['17 y/o and below'] ?? 0;
                $ageCounts['thirty'] += $ageData['18-30 y/o'] ?? 0;
                $ageCounts['fortyfive'] += $ageData['31-45 y/o'] ?? 0;
                $ageCounts['sixty'] += $ageData['60 y/o and above'] ?? 0;
            }
        }

        return response()->json($ageCounts);
    }

    public function genderDemographics(Request $request)
    {
        $filter = $request->get('filter', 'week');
        $dateFilter = $this->getDateFilter($filter);

        // Get gender demographics from visited entries only
        $genderCounts = Entry::select('answers.value as gender', DB::raw('count(*) as count'))
            ->join('answers', 'entries.id', '=', 'answers.entry_id')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->where('entries.isVisited', true)
            ->where('entries.created_at', '>=', $dateFilter)
            ->where('questions.content', 'Gender')
            ->groupBy('answers.value')
            ->get();

        return response()->json($genderCounts);
    }

    public function studentDemographics(Request $request)
    {
        $filter = $request->get('filter', 'week');
        $dateFilter = $this->getDateFilter($filter);

        // Get student demographics from visited group entries only
        $entries = Entry::select('entries.*', 'answers.value', 'questions.content')
            ->join('answers', 'entries.id', '=', 'answers.entry_id')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->where('entries.isVisited', true)
            ->where('entries.created_at', '>=', $dateFilter)
            ->whereIn('questions.content', [
                'Registration Type',
                'No. of Students / Grade School',
                'No. of Students / High School',
                'No. of Students / College / GradSchool'
            ])
            ->get();

        $studentCounts = [
            'gradeSchool' => 0,
            'highSchool' => 0,
            'college' => 0
        ];

        // Group entries by entry ID
        $groupedEntries = $entries->groupBy('id');

        foreach ($groupedEntries as $entryId => $entryAnswers) {
            $registrationType = null;
            $studentData = [];

            foreach ($entryAnswers as $answer) {
                if ($answer->content === 'Registration Type') {
                    $registrationType = $answer->value;
                } else {
                    $studentData[$answer->content] = (int)$answer->value;
                }
            }

            // Only count students from Group registrations
            if ($registrationType === 'Group') {
                $studentCounts['gradeSchool'] += $studentData['No. of Students / Grade School'] ?? 0;
                $studentCounts['highSchool'] += $studentData['No. of Students / High School'] ?? 0;
                $studentCounts['college'] += $studentData['No. of Students / College / GradSchool'] ?? 0;
            }
        }

        return response()->json($studentCounts);
    }

    public function mostVisited(Request $request)
    {
        $filter = $request->get('filter', 'week');
        $dateFilter = $this->getDateFilter($filter);

        // Get most visited days from visited entries only
        $visitData = Entry::where('isVisited', true)
            ->where('entries.created_at', '>=', $dateFilter)
            ->select(DB::raw('DAYNAME(entries.created_at) as day'), DB::raw('count(*) as visits'))
            ->groupBy(DB::raw('DAYNAME(entries.created_at)'))
            ->orderBy('visits', 'desc')
            ->get();

        return response()->json($visitData);
    }

    public function visitToday()
    {
        // Get visits today (only visited entries)
        $visitToday = Entry::where('isVisited', true)
            ->whereDate('entries.created_at', today())
            ->count();

        return response()->json(['visitToday' => $visitToday]);
    }

    public function visitThisMonth()
    {
        // Get visits this month (only visited entries)
        $visitMonth = Entry::where('isVisited', true)
            ->whereMonth('entries.created_at', now()->month)
            ->whereYear('entries.created_at', now()->year)
            ->count();

        return response()->json(['visitMonth' => $visitMonth]);
    }

    private function getDateFilter($filter)
    {
        switch ($filter) {
            case 'today':
                return today();
            case 'week':
                return now()->startOfWeek();
            case 'month':
                return now()->startOfMonth();
            case 'year':
                return now()->startOfYear();
            default:
                return now()->startOfWeek();
        }
    }

    private function getAnswerValue($entry, $questionContent)
    {
        $answer = $entry->answers()
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->where('questions.content', $questionContent)
            ->first();

        return $answer ? $answer->value : null;
    }
}
