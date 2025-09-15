@extends('layouts.guest-app')

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
        .gallery-card .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .gallery-card .card-text {
            flex-grow: 1; /* Allows description to take available space */
        }
        .gallery-card .primary-btn {
            align-self: flex-start; /* Keeps button at the bottom */
            margin-top: auto;
        }
    </style>

    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white pt-5">
                        Gallery
                    </h1>
                    <p class="text-white link-nav">
                        <a href="{{ url('/') }}">Home </a>
                        <span class="lnr lnr-arrow-right"></span>
                        <a href="{{ route('gallery') }}"> Gallery</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <section class="gallery-card-area section-gap" id="gallery-card">
        <div class="container">
            <div class="row d-flex justify-content-center">
                @foreach($galleries as $gallery)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card gallery-card">
                            @if($gallery->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $gallery->images->first()->image_path) }}" class="card-img-top" alt="{{ $gallery->name }}" data-image="{{ asset('storage/' . $gallery->images->first()->image_path) }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $gallery->name }}</h5>
                                <p class="card-text">{{ Str::limit($gallery->description, 100) }}</p>
                                <a href="{{ route('gallery.guest.show', $gallery->id) }}" class="primary-btn text-uppercase">View Gallery</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

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
