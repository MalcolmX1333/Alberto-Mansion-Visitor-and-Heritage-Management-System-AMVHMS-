@extends('layouts.app')

@section('content')
    <style>
        .gallery-card {
            height: 400px; /* Set a fixed height for all cards */
            display: flex;
            flex-direction: column;
        }
        .gallery-card img {
            height: 250px; /* Fixed height for images */
            object-fit: cover; /* Ensures image covers the area without distortion */
            cursor: pointer; /* Make the image look clickable */
        }
    </style>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('gallery.create') }}" class="btn btn-success">Add New Gallery</a>
            </div>
        </div>
        <div class="row">
            @if($galleries->isEmpty())
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        No galleries available.
                    </div>
                </div>
            @else
                @foreach($galleries as $gallery)
                    <div class="col-md-4 mb-4">
                        <div class="card gallery-card">
                            @if($gallery->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $gallery->images->first()->image_path) }}" class="card-img-top" alt="{{ $gallery->name }}" data-image="{{ asset('storage/' . $gallery->images->first()->image_path) }}">
                            @endif
                            <div class="card-body d-flex flex-column justify-between">
                                <h5 class="card-title">{{ $gallery->name }}</h5>
                                <p class="card-text">{{ Str::limit($gallery->description, 100) }}</p>
                                <div class="mt-auto d-flex justify-between">
                                    <a href="{{ route('gallery.show', $gallery->id) }}" class="btn btn-primary btn-sm w-50 mr-1">View Gallery</a>
                                    <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST" class="d-inline w-50">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.gallery-card img');
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
    </script>
@endsection
