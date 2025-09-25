<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use Barryvdh\DomPDF\PDF;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

    class ChartController extends Controller
    {
        protected $pdf;

        public function __construct(PDF $pdf)
        {
            $this->pdf = $pdf;
        }

        public function saveCharts(Request $request)
        {
            $ageChart = $request->input('ageChart');
            $genderChart = $request->input('genderChart');
            $mostVisitedChart = $request->input('mostVisitedChart');
            $studentChart = $request->input('studentChart');

            $ageData = $request->input('ageData');
            $genderData = $request->input('genderData');
            $mostVisitedData = $request->input('mostVisitedData');
            $studentData = $request->input('studentData');

            Storage::put('public/charts/ageChart.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $ageChart)));
            Storage::put('public/charts/genderChart.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $genderChart)));
            Storage::put('public/charts/mostVisitedChart.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $mostVisitedChart)));
            Storage::put('public/charts/studentChart.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $studentChart)));

            Storage::put('public/charts/ageData.json', json_encode($ageData));
            Storage::put('public/charts/genderData.json', json_encode($genderData));
            Storage::put('public/charts/mostVisitedData.json', json_encode($mostVisitedData));
            Storage::put('public/charts/studentData.json', json_encode($studentData));

            return response()->json(['success' => true]);
        }

        public function printCharts()
        {
            $ageChart = public_path('storage/charts/ageChart.png');
            $genderChart = public_path('storage/charts/genderChart.png');
            $mostVisitedChart = public_path('storage/charts/mostVisitedChart.png');
            $studentChart = public_path('storage/charts/studentChart.png');

            $ageData = json_decode(Storage::get('public/charts/ageData.json'), true) ?? [];
            $genderData = json_decode(Storage::get('public/charts/genderData.json'), true) ?? [];
            $mostVisitedData = json_decode(Storage::get('public/charts/mostVisitedData.json'), true) ?? [];
            $studentData = json_decode(Storage::get('public/charts/studentData.json'), true) ?? [];

            $pdf = $this->pdf->loadView('print.chart', compact(
                'ageChart',
                'genderChart',
                'mostVisitedChart',
                'studentChart',
                'ageData',
                'genderData',
                'mostVisitedData',
                'studentData'
            ));
            return $pdf->download('charts.pdf');
        }
    }
