@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-6">
                        <h5 class="mb-0 text-gray-800 fw-bold">Visitor Demographics</h5>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                            <select id="exportPeriod" class="form-select form-select-sm shadow-none">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="semi-annually">Semi-Annually</option>
                                <option value="annually">Annually</option>
                            </select>
                            <button id="exportButton" class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2 p-md-4">
                <div class="table-responsive-xl">
                    <table class="table table-hover align-middle nowrap" id="visitorDataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                        <tr>
                            <th>Bus Number</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Nationality</th>
                            <th class="text-center">Male</th>
                            <th class="text-center">Female</th>
                            <th class="text-center">PWD</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $entry)
                            <tr>
                                <td>{{ $entry['bus_number'] }}</td>
                                <td>{{ $entry['full_name'] }}</td>
                                <td>{{ $entry['address'] }}</td>
                                <td>{{ $entry['nationality'] }}</td>
                                <td class="text-center">{{ $entry['male'] }}</td>
                                <td class="text-center">{{ $entry['female'] }}</td>
                                <td class="text-center">{{ $entry['pwd'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($entry['time_in'])->format('F d, Y h:i A') }}</td>
                                <td>{{ $entry['time_out'] ? \Carbon\Carbon::parse($entry['time_out'])->format('F d, Y h:i A') : 'N/A' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" title="View" onclick="viewEntry({{ $entry['id'] }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <style>
        /* Table styles */
        .table-responsive-xl {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 100%;
            width: max-content !important;
        }

        .table td, .table th {
            white-space: nowrap;
            min-width: 100px;
            padding: 0.75rem;
            vertical-align: middle;
        }

        /* Card styles */
        .card {
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0,0,0,.125);
        }

        /* DataTables styles */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
            padding: 0.5rem;
        }

        /* Custom scrollbar */
        .table-responsive-xl::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-xl::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive-xl::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive-xl::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .table td, .table th {
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-left: 0 !important;
            }

            .dataTables_wrapper .dataTables_length select {
                width: auto;
            }

            /* Swipe indicator */
            .table-responsive-xl::after {
                content: '';
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 5px;
                background: linear-gradient(to left, rgba(0,0,0,0.05), transparent);
                pointer-events: none;
            }

            /* Modal adjustments for mobile */
            .swal2-popup {
                font-size: 0.875rem;
            }

            .swal2-popup .table {
                font-size: 0.875rem;
            }
        }

        /* Form elements */
        .form-select:focus,
        .btn:focus {
            box-shadow: none;
        }

        .btn-info {
            color: #fff;
        }

        /* SweetAlert customization */
        .swal2-popup .table {
            margin-bottom: 0;
        }

        .swal2-popup .table th {
            font-weight: 600;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var table = $('#visitorDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                dom: '<"row"<"col-12 col-md-6"l><"col-12 col-md-6"f>>rtip',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries"
                },
                initComplete: function() {
                    table.columns.adjust();

                    if (window.innerWidth < 768) {
                        $('.dataTables_wrapper').append(
                            '<div class="text-muted text-center small mt-2">Swipe left/right to see more columns</div>'
                        );
                    }
                }
            });

            // Handle window resize
            var resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    table.columns.adjust();
                }, 250);
            });

            // Add touch swipe indication
            if ('ontouchstart' in window) {
                var tableWrapper = $('.table-responsive-xl');
                tableWrapper.on('scroll', function() {
                    $(this).addClass('is-scrolling');
                    clearTimeout($.data(this, 'scrollTimer'));
                    $.data(this, 'scrollTimer', setTimeout(function() {
                        tableWrapper.removeClass('is-scrolling');
                    }, 250));
                });
            }
        });

        function viewEntry(entryId) {
            fetch(`/visitor/demographics/${entryId}`)
                .then(response => response.json())
                .then(data => {
                    const totalVisitors = (data.age_17_below || 0) + (data.age_18_30 || 0) + (data.age_31_45 || 0) + (data.age_60_above || 0) + (data.students_grade_school || 0) + (data.students_high_school || 0) + (data.students_college || 0);
                    Swal.fire({
                        title: 'Demographic Data',
                        html: `
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th class="text-start">Age 17 Below:</th>
                            <td class="text-end">${data.age_17_below}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Age 18-30:</th>
                            <td class="text-end">${data.age_18_30}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Age 31-45:</th>
                            <td class="text-end">${data.age_31_45}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Age 60 Above:</th>
                            <td class="text-end">${data.age_60_above}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Students Grade School:</th>
                            <td class="text-end">${data.students_grade_school}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Students High School:</th>
                            <td class="text-end">${data.students_high_school}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Students College:</th>
                            <td class="text-end">${data.students_college}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Total Visitors:</th>
                            <td class="text-end">${totalVisitors}</td>
                        </tr>
                    </table>
                </div>
                `,
                        width: window.innerWidth < 768 ? '95%' : '500px',
                        icon: 'info',
                        confirmButtonClass: 'btn btn-primary'
                    });
                })
                .catch(error => {
                    console.error('Error fetching demographic data:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to fetch demographic data.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary'
                    });
                });
        }

        document.getElementById('exportButton').addEventListener('click', function() {
            const period = document.getElementById('exportPeriod').value;
            window.location.href = `/visitor/export?period=${period}`;
        });
    </script>
@endsection
