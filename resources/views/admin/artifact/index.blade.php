@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">All Artifacts</h4>
                <button class="btn btn-sm btn-primary mb-3 me-2" onclick="openArtifactModal()">Add Artifact</button>
                <div class="table-responsive">
                    <table id="artifactsTable" class="table table-hover align-middle nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Control No</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Condition</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($artifacts as $artifact)
                            <tr>
                                <td>{{ $artifact->id }}</td>
                                <td>{{ $artifact->control_no }}</td>
                                <td>{{ $artifact->item_name }}</td>
                                <td>{{ $artifact->quantity }}</td>
                                <td>{{ $artifact->location }}</td>
                                <td>
                                    <span class="badge {{ $artifact->status === 'Available' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $artifact->status }}
                                    </span>
                                </td>
                                <td>{{ $artifact->condition }}</td>
                                <td>{{ $artifact->created_at }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-info btn-sm" title="View" onclick="viewArtifact({{ $artifact->id }})">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" title="Edit" onclick="editArtifact({{ $artifact->id }})">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" title="Delete" onclick="deleteArtifact({{ $artifact->id }})">
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

<!-- Artifact Modal -->
<div class="modal fade" id="artifactModal" tabindex="-1" aria-labelledby="artifactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="artifactForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="artifactModalLabel">Artifact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="artifact_id" name="id">
                    <div class="mb-3">
                        <label for="control_no" class="form-label">Control No</label>
                        <input type="text" class="form-control" id="control_no" name="control_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="condition" class="form-label">Condition</label>
                        <select class="form-select" id="condition" name="condition">
                            <option value="">Select condition</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                            <option value="Damaged">Damaged</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#artifactsTable').DataTable({
            pageLength: 25,
            autoWidth: false,
            order: [[ 0, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": 8 }
            ],
            dom: '<"row"<"col-12 col-md-6"l><"col-12 col-md-6"f>>rtip',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search artifacts...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
    });

    window.openArtifactModal = function() {
        $('#artifactForm')[0].reset();
        $('#artifact_id').val('');
        $('#artifactModalLabel').text('Add Artifact');
        var modal = new bootstrap.Modal(document.getElementById('artifactModal'));
        modal.show();
    }

    window.editArtifact = function(id) {
        fetch(`/admin/artifacts/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#artifact_id').val(data.artifact.id);
                    $('#control_no').val(data.artifact.control_no);
                    $('#item_name').val(data.artifact.item_name);
                    $('#quantity').val(data.artifact.quantity);
                    $('#location').val(data.artifact.location);
                    $('#status').val(data.artifact.status);
                    $('#condition').val(data.artifact.condition);
                    $('#artifactModalLabel').text('Edit Artifact');
                    var modal = new bootstrap.Modal(document.getElementById('artifactModal'));
                    modal.show();
                }
            });
    }

    $('#artifactForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#artifact_id').val();
        let url = id ? `/admin/artifacts/${id}` : `/admin/artifacts`;
        let method = id ? 'PUT' : 'POST';
        let data = {
            control_no: $('#control_no').val(),
            item_name: $('#item_name').val(),
            quantity: $('#quantity').val(),
            location: $('#location').val(),
            status: $('#status').val(),
            condition: $('#condition').val()
        };
        if (id) data.id = id;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'Artifact saved successfully', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', 'Failed to save artifact', 'error');
            }
        });
    });

    window.deleteArtifact = function(id) {
        Swal.fire({
            title: 'Delete Artifact',
            text: 'Are you sure you want to delete this artifact?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/artifacts/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Artifact deleted.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', 'Failed to delete artifact', 'error');
                    }
                });
            }
        });
    }

    window.viewArtifact = function(id) {
        fetch(`/admin/artifacts/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let artifact = data.artifact;
                    Swal.fire({
                        title: 'Artifact Details',
                        html: `
                            <div class="text-left">
                                <div><strong>ID:</strong> ${artifact.id}</div>
                                <div><strong>Control No:</strong> ${artifact.control_no}</div>
                                <div><strong>Item Name:</strong> ${artifact.item_name}</div>
                                <div><strong>Quantity:</strong> ${artifact.quantity}</div>
                                <div><strong>Location:</strong> ${artifact.location}</div>
                                <div><strong>Status:</strong> ${artifact.status}</div>
                                <div><strong>Condition:</strong> ${artifact.condition ?? ''}</div>
                                <div><strong>Created At:</strong> ${artifact.created_at}</div>
                            </div>
                        `,
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                }
            });
    }
</script>
@endsection
