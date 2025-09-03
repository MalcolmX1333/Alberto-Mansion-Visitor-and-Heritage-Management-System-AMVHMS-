@extends('layouts.app')

@section('content')
    <style>
        /* Keep all your existing styles from the original file */
        /* Modern Card Styles */
        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1),
            0 5px 15px rgba(0, 0, 0, 0.07);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #97CC70, #6FAE55, #4F8B43);
            border-radius: 20px 20px 0 0;
        }

        .modern-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15),
            0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(151, 204, 112, 0.1), rgba(111, 174, 85, 0.05));
            border: 1px solid rgba(151, 204, 112, 0.2);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #97CC70, #6FAE55);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(151, 204, 112, 0.2);
            border-color: rgba(151, 204, 112, 0.4);
        }

        .stat-card .card-body {
            position: relative;
            z-index: 2;
        }

        .stat-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(151, 204, 112, 0.2), rgba(111, 174, 85, 0.1));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #4F8B43;
        }

        .stat-number {
            font-size: 3.5rem !important;
            font-weight: 700 !important;
            background: linear-gradient(135deg, #4F8B43, #6FAE55);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin: 0;
        }

        .stat-title {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            color: #4F8B43;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        /* Chart Cards */
        .chart-card {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(151, 204, 112, 0.15);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #97CC70, #6FAE55, #4F8B43);
        }

        .chart-header {
            padding: 25px 25px 0 25px;
            border-bottom: 1px solid rgba(151, 204, 112, 0.1);
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #3C6D34;
            margin-bottom: 0;
        }

        /* Filter Dropdown */
        .modern-filter {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(151, 204, 112, 0.3);
            border-radius: 12px;
            padding: 8px 15px;
            font-weight: 600;
            color: #4F8B43;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .modern-filter:focus {
            outline: none;
            border-color: #6FAE55;
            box-shadow: 0 0 0 3px rgba(151, 204, 112, 0.2);
        }

        .filter-icon {
            background: linear-gradient(135deg, #97CC70, #6FAE55);
            color: white;
            border: none;
            border-radius: 10px 0 0 10px;
            padding: 8px 12px;
        }

        /* Weather Widget Container */
        .weather-container {
            background: linear-gradient(135deg, rgba(151, 204, 112, 0.05), rgba(111, 174, 85, 0.02));
            border-radius: 16px;
            padding: 20px;
            border: 1px solid rgba(151, 204, 112, 0.15);
        }

        /* Generate Report Button */
        .modern-btn {
            background: linear-gradient(135deg, #97CC70, #6FAE55);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(151, 204, 112, 0.3);
            transition: all 0.3s ease;
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(151, 204, 112, 0.4);
            background: linear-gradient(135deg, #6FAE55, #4F8B43);
        }

        /* Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, rgba(151, 204, 112, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid rgba(151, 204, 112, 0.2);
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3C6D34, #4F8B43);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        /* Animations */
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Canvas container improvements */
        .chart-canvas-container {
            background: rgba(248, 250, 252, 0.5);
            border-radius: 12px;
            padding: 15px;
            margin: 0 -10px;
        }
    </style>

    <div class="container">
        <!-- Modern Dashboard Header -->
        <div class="dashboard-header fade-in">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="dashboard-title">Dashboard</h1>
                <button id="generateReportButton" class="modern-btn d-none d-sm-inline-block">
                    <i class="fas fa-download me-2"></i>Generate Report
                </button>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Main Content Card -->
            <div class="col-md-12">
                <div class="modern-card fade-in">
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Weather Widget -->
                            <div class="col-md-6 d-flex align-items-center justify-content-center">
                                <div class="weather-container w-100 text-center">
                                    <div id="weatherapi-weather-widget-4"></div>
                                    <script type='text/javascript' src='https://www.weatherapi.com/weather/widget.ashx?loc=1857837&wid=4&tu=1&div=weatherapi-weather-widget-4' async></script>
                                    <noscript><a href="https://www.weatherapi.com/weather/q/pantay-1857837" alt="Hour by hour Pantay weather">10 day hour by hour Pantay weather</a></noscript>
                                </div>
                            </div>

                            <!-- Stats Cards -->
                            <div class="col-md-6">
                                <div class="row">
                                    <!-- Visits Today -->
                                    <div class="col-12 mb-4">
                                        <div class="stat-card card text-center p-4 border-0">
                                            <div class="stat-icon">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="stat-title">Visits Today</h5>
                                                <p class="stat-number">{{ $visitToday }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Visits This Month -->
                                    <div class="col-12">
                                        <div class="stat-card card text-center p-4 border-0">
                                            <div class="stat-icon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="stat-title">Visits This Month</h5>
                                                <p class="stat-number">{{ $visitMonth }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row justify-content-center mt-4">
            <!-- Age Demographics -->
            <div class="col-md-6 mb-4">
                <div class="chart-card modern-card fade-in">
                    <div class="chart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="chart-title">Age Demographics</h3>
                            <div class="input-group" style="width: auto;">
                                <span class="input-group-text filter-icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <select class="form-select modern-filter" aria-label="Filter by Time Period" id="ageFilter">
                                    <option selected>Filter by</option>
                                    <option value="1">Today</option>
                                    <option value="2">This Week</option>
                                    <option value="3">This Month</option>
                                    <option value="4">This Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="chart-canvas-container">
                            <div style="height: 400px;">
                                <canvas id="ageDemographicsChart" style="height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gender Demographics -->
            <div class="col-md-6 mb-4">
                <div class="chart-card modern-card fade-in">
                    <div class="chart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="chart-title">Gender Demographics</h3>
                            <div class="input-group" style="width: auto;">
                                <span class="input-group-text filter-icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <select class="form-select modern-filter" aria-label="Filter by Time Period" id="genderFilter">
                                    <option selected>Filter by</option>
                                    <option value="1">Today</option>
                                    <option value="2">This Week</option>
                                    <option value="3">This Month</option>
                                    <option value="4">This Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="chart-canvas-container">
                            <div style="height: 400px;">
                                <canvas id="genderDemographicsChart" style="height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Demographics and Most Visited Charts -->
        <div class="row justify-content-center mt-4">
            <!-- Student Demographics -->
            <div class="col-md-6 mb-4">
                <div class="chart-card modern-card fade-in">
                    <div class="chart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="chart-title">Student Demographics</h3>
                            <div class="input-group" style="width: auto;">
                                <span class="input-group-text filter-icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <select class="form-select modern-filter" aria-label="Filter by Time Period" id="studentFilter">
                                    <option selected>Filter by</option>
                                    <option value="1">Today</option>
                                    <option value="2">This Week</option>
                                    <option value="3">This Month</option>
                                    <option value="4">This Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="chart-canvas-container">
                            <div style="height: 400px;">
                                <canvas id="studentDemographicsChart" style="height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most Visited Chart -->
            <div class="col-md-6 mb-4">
                <div class="chart-card modern-card fade-in">
                    <div class="chart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="chart-title">Most Visited Day</h3>
                            <div class="input-group" style="width: auto;">
                                <span class="input-group-text filter-icon">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <select class="form-select modern-filter" aria-label="Filter by Time Period" id="mostVisitedFilter">
                                    <option selected>Filter by</option>
                                    <option value="1">Today</option>
                                    <option value="2">This Week</option>
                                    <option value="3">This Month</option>
                                    <option value="4">This Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="chart-canvas-container">
                            <div style="height: 400px;">
                                <canvas id="mostVisitedChart" style="height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let ageDemographicsChart = null;
            let genderDemographicsChart = null;
            let mostVisitedChart = null;
            let studentDemographicsChart = null;

            function fetchAgeDemographics(filter) {
                fetch(`/age-demographics?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('ageDemographicsChart').getContext('2d');

                        if (ageDemographicsChart) {
                            ageDemographicsChart.destroy();
                        }

                        ageDemographicsChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['17 y/o and below', '18-30 y/o', '31-45 y/o', '60 y/o and above'],
                                datasets: [{
                                    label: 'Age Demographics',
                                    data: [
                                        data.seventeen,
                                        data.thirty,
                                        data.fortyfive,
                                        data.sixty
                                    ],
                                    backgroundColor: [
                                        '#97CC70',
                                        '#6FAE55',
                                        '#4F8B43',
                                        '#3C6D34'
                                    ],
                                    borderColor: [
                                        '#5B8B42',
                                        '#497C34',
                                        '#386829',
                                        '#2A5420'
                                    ],
                                    borderWidth: 2,
                                    borderRadius: 5,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333',
                                            font: {
                                                size: 14,
                                                weight: 'bold'
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            }
                                        },
                                        grid: {
                                            color: '#CCC'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            }
                                        },
                                        grid: {
                                            color: '#CCC'
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1500,
                                    easing: 'easeOutBounce'
                                }
                            }
                        });

                        window.ageData = {
                            '17 y/o and below': data.seventeen,
                            '18-30 y/o': data.thirty,
                            '31-45 y/o': data.fortyfive,
                            '60 y/o and above': data.sixty
                        };
                    });
            }

            function fetchGenderDemographics(filter) {
                fetch(`/gender-demographics?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        const genders = data.map(item => item.gender);
                        const counts = data.map(item => item.count);

                        const ctx = document.getElementById('genderDemographicsChart').getContext('2d');

                        if (genderDemographicsChart) {
                            genderDemographicsChart.destroy();
                        }

                        genderDemographicsChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: genders,
                                datasets: [{
                                    label: 'Gender Demographics',
                                    data: counts,
                                    backgroundColor: [
                                        '#97CC70',
                                        '#6FAE55',
                                    ],
                                    borderColor: [
                                        '#5B8B42',
                                        '#497C34',
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                cutout: '50%',
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333',
                                            font: {
                                                size: 16,
                                                weight: 'bold'
                                            }
                                        }
                                    }
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: {
                                    duration: 1500,
                                    easing: 'easeOutBounce'
                                }
                            }
                        });

                        window.genderData = data.reduce((acc, item) => {
                            acc[item.gender] = item.count;
                            return acc;
                        }, {});
                    })
                    .catch(error => {
                        console.error('Error fetching gender demographics data:', error);
                    });
            }

            function fetchStudentDemographics(filter) {
                fetch(`/student-demographics?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('studentDemographicsChart').getContext('2d');

                        if (studentDemographicsChart) {
                            studentDemographicsChart.destroy();
                        }

                        studentDemographicsChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Grade School', 'High School', 'College / GradSchool'],
                                datasets: [{
                                    label: 'Student Demographics',
                                    data: [
                                        data.gradeSchool,
                                        data.highSchool,
                                        data.college
                                    ],
                                    backgroundColor: [
                                        '#97CC70',
                                        '#6FAE55',
                                        '#4F8B43'
                                    ],
                                    borderColor: [
                                        '#5B8B42',
                                        '#497C34',
                                        '#386829'
                                    ],
                                    borderWidth: 2,
                                    borderRadius: 5,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333',
                                            font: {
                                                size: 14,
                                                weight: 'bold'
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            }
                                        },
                                        grid: {
                                            color: '#CCC'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            }
                                        },
                                        grid: {
                                            color: '#CCC'
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1500,
                                    easing: 'easeOutBounce'
                                }
                            }
                        });

                        window.studentData = {
                            'Grade School': data.gradeSchool,
                            'High School': data.highSchool,
                            'College / GradSchool': data.college
                        };
                    });
            }

            function fetchMostVisited(filter) {
                fetch(`/most-visited?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        const days = data.map(item => item.day);
                        const visits = data.map(item => item.visits);

                        const ctx = document.getElementById('mostVisitedChart').getContext('2d');

                        if (mostVisitedChart) {
                            mostVisitedChart.destroy();
                        }

                        mostVisitedChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: days,
                                datasets: [{
                                    label: 'Most Visited Days',
                                    data: visits,
                                    backgroundColor: [
                                        '#97CC70',
                                        '#6FAE55',
                                        '#4F8B43',
                                        '#3C6D34',
                                        '#2A5420'
                                    ],
                                    borderColor: [
                                        '#5B8B42',
                                        '#497C34',
                                        '#386829',
                                        '#2A5420'
                                    ],
                                    borderWidth: 2,
                                    borderRadius: 5,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#333',
                                            font: {
                                                size: 14,
                                                weight: 'bold'
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            }
                                        },
                                        grid: {
                                            color: '#CCC'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                size: 12,
                                                weight: 'bold'
                                            }
                                        },
                                        grid: {
                                            color: '#CCC'
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1500,
                                    easing: 'easeOutBounce'
                                }
                            }
                        });

                        window.mostVisitedData = data.reduce((acc, item) => {
                            acc[item.day] = item.visits;
                            return acc;
                        }, {});
                    })
                    .catch(error => {
                        console.error('Error fetching most visited data:', error);
                    });
            }

            // Initial fetch with default filter
            fetchAgeDemographics('week');
            fetchGenderDemographics('week');
            fetchStudentDemographics('week');
            fetchMostVisited('week');

            // Handle filter changes
            document.getElementById('ageFilter').addEventListener('change', function (event) {
                const filter = event.target.value;
                switch (filter) {
                    case '1': fetchAgeDemographics('today'); break;
                    case '2': fetchAgeDemographics('week'); break;
                    case '3': fetchAgeDemographics('month'); break;
                    case '4': fetchAgeDemographics('year'); break;
                    default: fetchAgeDemographics('week'); break;
                }
            });

            document.getElementById('genderFilter').addEventListener('change', function (event) {
                const filter = event.target.value;
                switch (filter) {
                    case '1': fetchGenderDemographics('today'); break;
                    case '2': fetchGenderDemographics('week'); break;
                    case '3': fetchGenderDemographics('month'); break;
                    case '4': fetchGenderDemographics('year'); break;
                    default: fetchGenderDemographics('week'); break;
                }
            });

            document.getElementById('studentFilter').addEventListener('change', function (event) {
                const filter = event.target.value;
                switch (filter) {
                    case '1': fetchStudentDemographics('today'); break;
                    case '2': fetchStudentDemographics('week'); break;
                    case '3': fetchStudentDemographics('month'); break;
                    case '4': fetchStudentDemographics('year'); break;
                    default: fetchStudentDemographics('week'); break;
                }
            });

            document.getElementById('mostVisitedFilter').addEventListener('change', function (event) {
                const filter = event.target.value;
                switch (filter) {
                    case '1': fetchMostVisited('today'); break;
                    case '2': fetchMostVisited('week'); break;
                    case '3': fetchMostVisited('month'); break;
                    case '4': fetchMostVisited('year'); break;
                    default: fetchMostVisited('week'); break;
                }
            });

            function printCharts() {
                const ageChart = document.getElementById('ageDemographicsChart').toDataURL('image/png');
                const genderChart = document.getElementById('genderDemographicsChart').toDataURL('image/png');
                const mostVisitedChart = document.getElementById('mostVisitedChart').toDataURL('image/png');
                const studentChart = document.getElementById('studentDemographicsChart').toDataURL('image/png');

                const requestData = {
                    ageChart: ageChart,
                    genderChart: genderChart,
                    mostVisitedChart: mostVisitedChart,
                    studentChart: studentChart,
                    ageData: window.ageData,
                    genderData: window.genderData,
                    mostVisitedData: window.mostVisitedData,
                    studentData: window.studentData
                };

                fetch('/save-charts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/print-charts';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to save charts. Please try again.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error saving charts:', error);
                });
            }

            document.getElementById('generateReportButton').addEventListener('click', printCharts);
        });
    </script>
@endsection
