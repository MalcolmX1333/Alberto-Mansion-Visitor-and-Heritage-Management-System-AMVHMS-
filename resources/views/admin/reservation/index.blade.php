@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4">All Reservations</h4>
                    <button class="btn btn-sm btn-primary mb-3 me-2" onclick="openQRScanner()">
                        <i class="mdi mdi-camera"></i> Scan QR
                    </button>
                    <a href="{{ route('admin.reservations.export') }}" class="btn btn-sm btn-success mb-3 me-2">
                        Export Excel
                    </a>
                    <div class="table-responsive">
                        <table id="reservationsTable" class="table table-hover align-middle nowrap">
                            <thead>
                            <tr>
                                <th>Visit Date</th>
                                <th class="text-center">Type</th>
                                <th>Full Name</th>
                                <th>Participant</th>
                                <th>Email</th>
                                <th class="text-center">Status</th>
                                <th>Created At</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation['visit_date'] ?? '-'}}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $reservation['registration_type'] }}</span>
                                    </td>
                                    <td>{{ $reservation['full_name'] ?? '-'}}</td>
                                    <td>{{ $reservation['participant_name'] ?? '-'}}</td>
                                    <td>{{ $reservation['participant_email'] ?? '-'}}</td>
                                    <td class="text-center">
                                        @if($reservation['status'] === 'Visited')
                                            <span class="badge bg-success">{{ $reservation['status'] ?? '-'}}</span>
                                        @else
                                            <span class="badge bg-warning">{{ $reservation['status'] ?? '-'}}</span>
                                        @endif
                                    </td>
                                    <td>{{ $reservation['created_at'] ?? '-'}}</td>
                                    <td>{{ $reservation['time_in'] ?? '-' }}</td>
                                    <td>
                                        @if($reservation['time_in'])
                                            {{ $reservation['time_out'] ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-info btn-sm" title="View Details" onclick="viewDetails({{ $reservation['id'] }})">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                            <button class="btn btn-success btn-sm" title="Generate QR" onclick="generateQR({{ $reservation['id'] }})">
                                                <i class="mdi mdi-qrcode"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm" title="Toggle Status" onclick="toggleStatus({{ $reservation['id'] }}, '{{ $reservation['status'] }}')">
                                                <i class="mdi mdi-toggle-switch"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" title="Delete" onclick="deleteReservation({{ $reservation['id'] }})">
                                                <i class="mdi mdi-trash-can"></i>
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
    </div>

    <!-- QR Scanner Modal (unchanged except icon) -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScannerModalLabel">
                        <i class="mdi mdi-camera me-2"></i>QR Code Scanner
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let html5QrcodeScanner = null;

       $(document).ready(function() {
                $('#reservationsTable').DataTable({
                    pageLength: 25,
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
                        this.api().columns.adjust();

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

            // Clean up scanner when modal is closed
            $('#qrScannerModal').on('hidden.bs.modal', function () {
                stopQRScanner();
            });
        });

        window.openQRScanner = function() {
            const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
            modal.show();

            setTimeout(() => {
                startQRScanner();
            }, 500); // Delay to ensure modal is fully shown
        }

        function startQRScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                {
                    fps: 10,
                    qrbox: 250,
                    aspectRatio: 1.0
                },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }

        function stopQRScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(error => {
                    console.error("Failed to clear QR scanner.", error);
                });
                html5QrcodeScanner = null;
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning
            stopQRScanner();

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
            modal.hide();

            // Check if the scanned URL is a visit marking URL
            if (decodedText.includes('/api/visits/') && decodedText.includes('/mark-visited')) {
                // Extract ID from URL
                const urlParts = decodedText.split('/');
                const idIndex = urlParts.indexOf('visits') + 1;
                const id = urlParts[idIndex];

                if (id) {
                    Swal.fire({
                        title: 'QR Code Scanned!',
                        text: `Found reservation ID: ${id}. Mark as visited?`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, mark as visited!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the scanned URL
                            window.open(decodedText, '_blank');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid QR Code',
                        text: 'Could not extract reservation ID from QR code.'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Unrecognized QR Code',
                    text: 'This QR code is not a valid reservation visit link.'
                });
            }
        }

        function onScanFailure(error) {
            // Handle scan failure, usually better to ignore it
            console.warn(`QR scan error: ${error}`);
        }

        window.refreshTable = function() {
            location.reload();
        }

        window.generateQR = function(id) {
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

                        // Add feedback answers if present
                        if (data.reservation.feedback_answers && data.reservation.feedback_answers.length > 0) {
                            detailsHtml += `
                                <hr class="my-3">
                                <h5 class="mb-3 text-success">Feedback Answers</h5>
                                <div class="mb-2">
                            `;
                            data.reservation.feedback_answers.forEach(function(answer) {
                                detailsHtml += `
                                    <div class="row mb-1">
                                        <div class="col-6"><strong>${answer.question}:</strong></div>
                                        <div class="col-6">${answer.value}</div>
                                    </div>
                                `;
                            });
                            detailsHtml += `</div>`;
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
