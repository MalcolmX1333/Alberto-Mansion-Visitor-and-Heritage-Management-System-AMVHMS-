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
        $visitToday = $this->visitToday();
        $visitMonth = $this->visitThisMonth();
        return view('home', compact('visitToday', 'visitMonth'));
    }

    // app/Http/Controllers/HomeController.php

    public function ageDemographics(Request $request)
    {
        $filter = $request->input('filter', 'today');

        $seventeenQuery = Answer::where('question_id', 11);
        $thirtyQuery = Answer::where('question_id', 12);
        $fortyfiveQuery = Answer::where('question_id', 13);
        $sixtyQuery = Answer::where('question_id', 14);

        switch ($filter) {
            case 'week':
                $seventeenQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $thirtyQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $fortyfiveQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $sixtyQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $seventeenQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $thirtyQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $fortyfiveQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $sixtyQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $seventeenQuery->whereYear('created_at', Carbon::now()->year);
                $thirtyQuery->whereYear('created_at', Carbon::now()->year);
                $fortyfiveQuery->whereYear('created_at', Carbon::now()->year);
                $sixtyQuery->whereYear('created_at', Carbon::now()->year);
                break;
            case 'today':
            default:
                $seventeenQuery->whereDate('created_at', Carbon::today());
                $thirtyQuery->whereDate('created_at', Carbon::today());
                $fortyfiveQuery->whereDate('created_at', Carbon::today());
                $sixtyQuery->whereDate('created_at', Carbon::today());
                break;
        }

        $seventeen = $seventeenQuery->selectRaw('SUM(value) as seventeen')->first();
        $thirty = $thirtyQuery->selectRaw('SUM(value) as thirty')->first();
        $fortyfive = $fortyfiveQuery->selectRaw('SUM(value) as fortyfive')->first();
        $sixty = $sixtyQuery->selectRaw('SUM(value) as sixty')->first();

        return response()->json([
            'seventeen' => $seventeen->seventeen,
            'thirty' => $thirty->thirty,
            'fortyfive' => $fortyfive->fortyfive,
            'sixty' => $sixty->sixty,
        ]);
    }

    public function studentDemographics(Request $request)
    {
        $filter = $request->input('filter', 'today');

        $gradeSchoolQuery = Answer::where('question_id', 7);
        $highSchoolQuery = Answer::where('question_id', 8);
        $collegeQuery = Answer::where('question_id', 9);

        switch ($filter) {
            case 'week':
                $gradeSchoolQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $highSchoolQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $collegeQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $gradeSchoolQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $highSchoolQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $collegeQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $gradeSchoolQuery->whereYear('created_at', Carbon::now()->year);
                $highSchoolQuery->whereYear('created_at', Carbon::now()->year);
                $collegeQuery->whereYear('created_at', Carbon::now()->year);
                break;
            case 'today':
            default:
                $gradeSchoolQuery->whereDate('created_at', Carbon::today());
                $highSchoolQuery->whereDate('created_at', Carbon::today());
                $collegeQuery->whereDate('created_at', Carbon::today());
                break;
        }

        $gradeSchool = $gradeSchoolQuery->selectRaw('SUM(value) as gradeSchool')->first();
        $highSchool = $highSchoolQuery->selectRaw('SUM(value) as highSchool')->first();
        $college = $collegeQuery->selectRaw('SUM(value) as college')->first();

        return response()->json([
            'gradeSchool' => $gradeSchool->gradeSchool,
            'highSchool' => $highSchool->highSchool,
            'college' => $college->college,
        ]);
    }

    public function genderDemographics(Request $request)
    {
        $filter = $request->input('filter', 'today');

        $maleQuery = Answer::where('question_id', 5);
        $femaleQuery = Answer::where('question_id', 6);

        switch ($filter) {
            case 'week':
                $maleQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $femaleQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $maleQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                $femaleQuery->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $maleQuery->whereYear('created_at', Carbon::now()->year);
                $femaleQuery->whereYear('created_at', Carbon::now()->year);
                break;
            case 'today':
            default:
                $maleQuery->whereDate('created_at', Carbon::today());
                $femaleQuery->whereDate('created_at', Carbon::today());
                break;
        }

        $maleSum = $maleQuery->selectRaw('SUM(value) as total')->first()->total;
        $femaleSum = $femaleQuery->selectRaw('SUM(value) as total')->first()->total;

        return response()->json([
            ['gender' => 'Female', 'count' => $femaleSum],
            ['gender' => 'Male', 'count' => $maleSum],
        ]);
    }

    public function mostVisited(Request $request)
    {
        $filter = $request->input('filter', 'today');
        $query = Entry::selectRaw('DAYNAME(created_at) as day, COUNT(DISTINCT device_identifier) as visits')
            ->groupBy('day')
            ->orderBy(DB::raw('FIELD(day, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")'));

        switch ($filter) {
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'today':
            default:
                $query->whereDate('created_at', Carbon::today());
                break;
        }

        $entriesByDay = $query->get();

        return response()->json($entriesByDay);
    }

    public function visitToday()
    {
        return Entry::whereDate('created_at', Carbon::today())
            ->distinct('device_identifier')
            ->count('device_identifier');
    }

    public function visitThisMonth()
    {
        return Entry::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->distinct('device_identifier')
            ->count('device_identifier');
    }




}
