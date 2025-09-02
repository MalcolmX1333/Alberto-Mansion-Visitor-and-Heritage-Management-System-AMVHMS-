@extends('layouts.guest-app')

@section('content')
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white pt-5">
                        My Reservations
                    </h1>
                    <p class="text-white link-nav"><a href="{{ route('home') }}">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="{{ route('guest.reservation.index') }}"> My Reservations</a></p>
                </div>
            </div>
        </div>
    </section>

    <section class="sample-text-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="text-heading">My Reservations</h3>
                    <div class="table-responsive">
                        <table id="reservationsTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Survey</th>
                                    <th>Visit Date</th>
                                    <th>Registration Type</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation['id'] }}</td>
                                    <td>{{ $reservation['survey_name'] }}</td>
                                    <td>{{ $reservation['visit_date'] }}</td>
                                    <td>{{ $reservation['registration_type'] }}</td>
                                    <td>{{ $reservation['full_name'] }}</td>
                                    <td>
                                        @if($reservation['status'] === 'Visited')
                                            <span class="badge badge-success">{{ $reservation['status'] }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $reservation['status'] }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $reservation['created_at'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewDetails({{ $reservation['id'] }})">View</button>
                                        <button class="btn btn-sm btn-warning" onclick="editReservation({{ $reservation['id'] }})">Edit</button>
                                        <button class="btn btn-sm btn-success" onclick="generateQR({{ $reservation['id'] }})">QR</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#reservationsTable').DataTable({
        "responsive": true,
        "pageLength": 10,
        "order": [[ 6, "desc" ]], // Order by created_at column descending
        "columnDefs": [
            { "orderable": false, "targets": 7 } // Disable ordering on Actions column
        ]
    });
});

function generateQR(id) {
    // Generate URL using the web route
    const url = "{{ route('generate.qr', ':id') }}".replace(':id', id);

    console.log('Generated QR URL:', url);

    Swal.fire({
        title: 'Scan QR Code to Mark as Visited',
        html: `
            <div class="text-center">
                <img src="${url}" alt="QR Code" class="img-fluid mb-3" style="max-width: 300px;">
                <p class="text-muted">Scan this QR code to mark the reservation as visited</p>
            </div>
        `,
        width: '500px',
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal-qr'
        }
    });
}


function viewDetails(id) {
    // Show loading
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Generate URL using Laravel route helper with parameter
    const url = "{{ route('guest.reservation.details', ':id') }}".replace(':id', id);

    // Fetch reservation details via AJAX
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Create basic details HTML
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
                `;

                // Add demographic information for group registrations
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

                // Close the basic details and add status/created_at
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
                    width: '700px',
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

function editReservation(id) {
    // Generate URL using Laravel route helper with parameter
    const url = "{{ route('guest.reservation.edit', ':id') }}".replace(':id', id);
    window.location.href = url;
}
</script>

<style>
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
@endpush
