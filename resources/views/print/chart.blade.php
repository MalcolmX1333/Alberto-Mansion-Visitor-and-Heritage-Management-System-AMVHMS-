<!DOCTYPE html>
<html>
<head>
    <title>Visitor Demographics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .chart img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .section-header {
            margin-bottom: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .data-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Visitor Demographics Report</h1>

<div class="section">
    <h2 class="section-header">Age Demographics</h2>
    <div class="chart">
        <img src="file://{{ $ageChart }}" alt="Age Demographics Chart">
    </div>
    <table class="data-table">
        <thead>
        <tr>
            <th>Age Group</th>
            <th>Count</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ageData as $ageGroup => $count)
            <tr>
                <td>{{ $ageGroup }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <h2 class="section-header">Gender Demographics</h2>
    <div class="chart">
        <img src="file://{{ $genderChart }}" alt="Gender Demographics Chart">
    </div>
    <table class="data-table">
        <thead>
        <tr>
            <th>Gender</th>
            <th>Count</th>
        </tr>
        </thead>
        <tbody>
        @foreach($genderData as $gender => $count)
            <tr>
                <td>{{ $gender }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <h2 class="section-header">Most Visited Locations</h2>
    <div class="chart">
        <img src="file://{{ $mostVisitedChart }}" alt="Most Visited Locations Chart">
    </div>
    <table class="data-table">
        <thead>
        <tr>
            <th>Day</th>
            <th>Visits</th>
        </tr>
        </thead>
        <tbody>
        @foreach($mostVisitedData as $day => $visits)
            <tr>
                <td>{{ $day }}</td>
                <td>{{ $visits }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <h2 class="section-header">Student Demographics</h2>
    <div class="chart">
        <img src="file://{{ $studentChart }}" alt="Student Demographics Chart">
    </div>
    <table class="data-table">
        <thead>
        <tr>
            <th>Student Group</th>
            <th>Count</th>
        </tr>
        </thead>
        <tbody>
        @foreach($studentData as $studentGroup => $count)
            <tr>
                <td>{{ $studentGroup }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="footer">
    <p>Generated on {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
</div>

</body>
</html>
