<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $results = $this->getSurveyReport($search);
        $averages = $this->getAverageOfSpecificQuestions();
        return view('welcome', compact('results', 'averages','search'));
    }

    public function getSurveyReport($search = null)
    {
        $query = DB::table('surveys')
            ->join('questions', 'surveys.id', '=', 'questions.survey_id')
            ->join('answers', 'questions.id', '=', 'answers.question_id')
            ->select('surveys.name as survey_name', 'questions.content as question_content', 'answers.value as answer_value')
            ->where('surveys.name', 'Visitor Feedback')
            ->whereBetween('questions.id', [14, 24]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('surveys.name', 'like', '%' . $search . '%')
                    ->orWhere('questions.content', 'like', '%' . $search . '%')
                    ->orWhere('answers.value', 'like', '%' . $search . '%');
            });
        }

        $results = $query->paginate(5);

        // Convert integer values to star icons
        foreach ($results as $result) {
            if (is_numeric($result->answer_value)) {
                $result->answer_value = str_repeat('<i class="fa fa-star"></i>', $result->answer_value);
            }
        }

        return $results;
    }

    public function getAverageOfSpecificQuestions()
    {
        $questions = [
            18 => ['title' => 'App Quality', 'icon' => 'lnr lnr-star'],
            19 => ['title' => 'Visit Retention', 'icon' => 'lnr lnr-heart'],
            21 => ['title' => 'Customer Service', 'icon' => 'lnr lnr-smile'],
            23 => ['title' => 'Competent Officers', 'icon' => 'lnr lnr-users']
        ];

        $averages = DB::table('answers')
            ->select(DB::raw('question_id, AVG(value) as average_value'))
            ->whereIn('question_id', array_keys($questions))
            ->groupBy('question_id')
            ->get()
            ->map(function ($item) use ($questions) {
                $item->title = $questions[$item->question_id]['title'];
                $item->icon = $questions[$item->question_id]['icon'];
                return $item;
            });

        Log::info($averages);
        return $averages;
    }
}
