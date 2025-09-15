@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <!-- Gallery Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 id="gallery-name" class="display-4">{{ $gallery->name }}</h1>
                <div>
                    <i class="fas fa-pencil-alt ms-3 text-muted" style="cursor: pointer;" onclick="editGalleryName()"></i>
                    <a href="{{ route('gallery.index') }}" class="btn btn-outline-primary ms-3">Back to Galleries</a>
                </div>
            </div>
        </div>

        <!-- Gallery Description -->
        <div class="row mb-5">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <p id="gallery-description" class="lead text-muted">{{ $gallery->description }}</p>
                <i class="fas fa-pencil-alt ms-3 text-muted" style="cursor: pointer;" onclick="editGalleryDescription()"></i>
            </div>
        </div>

        <!-- Gallery Images -->
        <div class="row">
            @if($gallery->images->isEmpty())
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        No images available in this gallery.
                    </div>
                </div>
            @else
                @foreach($gallery->images as $image)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card shadow-sm border-light rounded">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="Image" style="height: 200px; object-fit: cover;" data-image="{{ asset('storage/' . $image->image_path) }}">
                            <div class="card-body position-relative">
                                <h5 class="card-title">{{ $image->title }}</h5>
                                <p class="card-text">{{ $image->description }}</p>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <button class="btn btn-link text-primary p-0" onclick="showEditModal({{ $image->id }}, '{{ $image->title }}', '{{ $image->description }}')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <form action="{{ route('gallery.image.destroy', $image->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Add New Image Button -->
            <div class="col-md-4 col-lg-3 mb-4 d-flex justify-content-center align-items-center">
                <button class="btn btn-outline-primary" onclick="showCreateModal()">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.card-img-top[data-image]');
            images.forEach(image => {
                image.addEventListener('click', function () {
                    const imageUrl = this.getAttribute('data-image');
                    Swal.fire({
                        imageUrl: imageUrl,
                        imageAlt: 'Gallery Image',
                        showCloseButton: true,
                        showConfirmButton: false,
                    });
                });
            });
        });

        // Function to show the create modal
        function showCreateModal() {
            Swal.fire({
                title: 'Create New Image',
                html: `
                    <form id="createForm" action="{{ route('gallery.image.store', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                <input type="file" id="upload_img" name="upload_img" class="form-control mb-2">
                <input type="text" name="title" placeholder="Title" class="form-control mb-2">
                <textarea name="description" placeholder="Description" class="form-control mb-2"></textarea>
            </form>
`,
                showCancelButton: true,
                confirmButtonText: 'Create',
                preConfirm: () => {
                    document.getElementById('createForm').submit();
                }
            });
        }

        // Function to show the edit modal
        function showEditModal(imageId, title, description) {
            Swal.fire({
                title: 'Edit Image',
                html: `
                    <form id="editForm" action="/gallery/image/${imageId}/edit" method="POST" enctype="multipart/form-data">
                        @csrf
                <input type="file" id="edit_img_${imageId}" name="upload_img" class="form-control mb-2">
                        <input type="text" name="title" value="${title}" placeholder="Title" class="form-control mb-2">
                        <textarea name="description" placeholder="Description" class="form-control mb-2">${description}</textarea>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => {
                    document.getElementById('editForm').submit();
                }
            });
        }

        // Edit Gallery Name Function
        function editGalleryName() {
            Swal.fire({
                title: 'Edit Gallery Name',
                input: 'text',
                inputLabel: 'New Gallery Name',
                inputValue: document.getElementById('gallery-name').innerText,
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                preConfirm: (newName) => {
                    return fetch('{{ route('gallery.update', $gallery->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ gallery_name: newName, _method: 'PUT' })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.text();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            );
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Gallery name updated successfully.',
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        // Edit Gallery Description Function
        function editGalleryDescription() {
            Swal.fire({
                title: 'Edit Gallery Description',
                input: 'textarea',
                inputLabel: 'New Gallery Description',
                inputValue: document.getElementById('gallery-description').innerText,
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                preConfirm: (newDescription) => {
                    return fetch('{{ route('gallery.update', $gallery->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ description: newDescription, _method: 'PUT' })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.text();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            );
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Gallery description updated successfully.',
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    </script>
@endsection
