@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-6">
                        <h5 class="mb-0 text-gray-800 fw-bold">All Reservations</h5>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                            <button class="btn btn-success btn-sm" onclick="refreshTable()">
                                <i class="fas fa-sync me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2 p-md-4">
                <div class="table-responsive-xl">
                    <table class="table table-hover align-middle nowrap" id="reservationsTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Survey</th>
                                <th>Visit Date</th>
                                <th class="text-center">Type</th>
                                <th>Full Name</th>
                                <th>Participant</th>
                                <th>Email</th>
                                <th class="text-center">Status</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation['id'] }}</td>
                                <td>{{ $reservation['survey_name'] }}</td>
                                <td>{{ $reservation['visit_date'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $reservation['registration_type'] }}</span>
                                </td>
                                <td>{{ $reservation['full_name'] }}</td>
                                <td>{{ $reservation['participant_name'] }}</td>
                                <td>{{ $reservation['participant_email'] }}</td>
                                <td class="text-center">
                                    @if($reservation['status'] === 'Visited')
                                        <span class="badge bg-success">{{ $reservation['status'] }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $reservation['status'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $reservation['created_at'] }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-info btn-sm" title="View Details" onclick="viewDetails({{ $reservation['id'] }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-success btn-sm" title="Generate QR" onclick="generateQR({{ $reservation['id'] }})">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" title="Toggle Status" onclick="toggleStatus({{ $reservation['id'] }}, '{{ $reservation['status'] }}')">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" title="Delete" onclick="deleteReservation({{ $reservation['id'] }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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

        .card {
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0,0,0,.125);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
            padding: 0.5rem;
        }

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

            .btn-group .btn {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
        }

        .btn:focus {
            box-shadow: none;
        }

        .btn-info {
            color: #fff;
        }

        .swal-wide {
            max-width: 700px !important;
        }

        .swal-qr {
            max-width: 500px !important;
        }

        .swal-qr img {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: white;
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
            var table = $('#reservationsTable').DataTable({
                pageLength: 25,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                order: [[ 8, "desc" ]],
                columnDefs: [
                    { "orderable": false, "targets": 9 }
                ],
                dom: '<"row"<"col-12 col-md-6"l><"col-12 col-md-6"f>>rtip',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search reservations...",
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

            var resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    table.columns.adjust();
                }, 250);
            });
        });

        window.refreshTable = function() {
            location.reload();
        }

        window.generateQR = function(id) {
            // Use the same QR generation route as guest blade
            const url = "{{ route('generate.qr', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Scan QR Code to Mark as Visited',
                html: `
                    <div class="text-center">
                        <img src="${url}" alt="QR Code" class="img-fluid mb-3" style="max-width: 300px;">
                        <p class="text-muted">Scan this QR code to mark the reservation as visited</p>
                    </div>
                `,
                width: window.innerWidth < 768 ? '95%' : '500px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-qr'
                }
            });
        }

        window.viewDetails = function(id) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const url = "{{ route('admin.reservation.details', ':id') }}".replace(':id', id);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let detailsHtml = `
                            <div class="text-left">
                                <div class="row mb-2">
                                    <div class="col-4"><strong>ID:</strong></div>
                                    <div class="col-8">${data.reservation.id}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Survey:</strong></div>
                                    <div class="col-8">${data.reservation.survey_name}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Visit Date:</strong></div>
                                    <div class="col-8">${data.reservation.visit_date}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Registration Type:</strong></div>
                                    <div class="col-8"><span class="badge badge-primary">${data.reservation.registration_type}</span></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>C.N. Bus Number:</strong></div>
                                    <div class="col-8">${data.reservation.cn_bus_number || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Full Name:</strong></div>
                                    <div class="col-8">${data.reservation.full_name}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Address/Affiliation:</strong></div>
                                    <div class="col-8">${data.reservation.address || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Nationality:</strong></div>
                                    <div class="col-8">${data.reservation.nationality || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Gender:</strong></div>
                                    <div class="col-8">${data.reservation.gender || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Participant:</strong></div>
                                    <div class="col-8">${data.reservation.participant_name}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Email:</strong></div>
                                    <div class="col-8">${data.reservation.participant_email}</div>
                                </div>
                        `;

                        if (data.reservation.registration_type === 'Group' && data.reservation.demographics) {
                            detailsHtml += `
                                <hr class="my-3">
                                <h5 class="mb-3 text-primary">Group Demographics</h5>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Grade School Students:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.grade_school || '0'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>High School Students:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.high_school || '0'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>College/Grad School Students:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.college_gradschool || '0'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>PWD:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.pwd || '-'}</div>
                                </div>
                                <hr class="my-2">
                                <h6 class="mb-2 text-secondary">Age Distribution</h6>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>17 y/o and below:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.age_17_below || '0'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>18-30 y/o:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.age_18_30 || '0'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>31-45 y/o:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.age_31_45 || '0'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>60 y/o and above:</strong></div>
                                    <div class="col-6">${data.reservation.demographics.age_60_above || '0'}</div>
                                </div>
                            `;
                        }

                        detailsHtml += `
                                <hr class="my-3">
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Status:</strong></div>
                                    <div class="col-8">
                                        <span class="badge ${data.reservation.status === 'Visited' ? 'badge-success' : 'badge-warning'}">
                                            ${data.reservation.status}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Created At:</strong></div>
                                    <div class="col-8">${data.reservation.created_at}</div>
                                </div>
                            </div>
                        `;

                        Swal.fire({
                            title: 'Reservation Details',
                            html: detailsHtml,
                            width: window.innerWidth < 768 ? '95%' : '700px',
                            showCloseButton: true,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'swal-wide'
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to load reservation details'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load reservation details'
                    });
                });
        }

        window.toggleStatus = function(id, currentStatus) {
            const newStatus = currentStatus === 'Visited' ? 'pending' : 'visited';
            const statusText = newStatus === 'visited' ? 'visited' : 'pending';

            Swal.fire({
                title: 'Update Status',
                text: `Mark this reservation as ${statusText}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, mark as ${statusText}!`
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = "{{ route('admin.reservation.update-status', ':id') }}".replace(':id', id);

                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to update status'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status'
                        });
                    });
                }
            });
        }

        window.deleteReservation = function(id) {
            Swal.fire({
                title: 'Delete Reservation',
                text: 'Are you sure you want to delete this reservation? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = "{{ route('admin.reservation.destroy', ':id') }}".replace(':id', id);

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to delete reservation'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete reservation'
                        });
                    });
                }
            });
        }
    </script>
@endsection
