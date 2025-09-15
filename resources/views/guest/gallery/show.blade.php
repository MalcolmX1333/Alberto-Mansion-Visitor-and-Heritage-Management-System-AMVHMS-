@extends('layouts.guest-app')

@section('content')
    <style>
        .gallery-item {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .gallery-item:hover {
            transform: scale(1.05);
        }
        .gallery-link {
            display: block;
        }
        .gallery-link img {
            height: 250px;
            object-fit: cover;
        }
        .gallery-caption {
            background-color: #f8f9fa;
        }
    </style>

<section class="banner-area relative" id="home">
    <div class="overlay overlay-bg"></div>
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white pt-5">
                    {{ $gallery->name }}
                </h1>
                <p class="text-white link-nav">
                    <a href="{{ url('/') }}">Home </a>
                    <span class="lnr lnr-arrow-right"></span>
                    <a href="{{ route('gallery') }}"> Gallery</a>
                    <span class="lnr lnr-arrow-right"></span>
                    <span>{{ $gallery->name }}</span>
                </p>
            </div>
        </div>
    </div>
</section>


    <!-- Start Gallery Description Area -->
    <section class="gallery-description-area py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="mb-4">{{ $gallery->name }}</h2>
                    <p class="lead text-muted">{{ $gallery->description }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Gallery Description Area -->

    <!-- Start Gallery Images Area -->
    <section class="gallery-images-area pb-5">
        <div class="container">
            <div class="row">
                @foreach($gallery->images as $image)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="gallery-item shadow-sm rounded overflow-hidden" onclick="showImageDetails('{{ asset('storage/' . $image->image_path) }}', '{{ $image->title }}', '{{ $image->description }}')">
                            <a href="javascript:void(0)" class="gallery-link">
                                <img class="img-fluid w-100" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}">
                            </a>
                            <div class="gallery-caption p-3">
                                <h5 class="mb-2">{{ $image->title }}</h5>
                                <p class="text-muted mb-0">{{ $image->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End Gallery Images Area -->
@endsection

@push('scripts')
    <script>
        function showImageDetails(imagePath, title, description) {
            Swal.fire({
                html: `
                    <div class="image-modal-content">
                        <img src="${imagePath}" alt="${title}" class="img-fluid mb-3 rounded">
                        <h2 class="swal2-title mb-2">${title}</h2>
                        <p class="swal2-description text-muted">${description}</p>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                width: '80%',
                background: '#f8f9fa',
                customClass: {
                    popup: 'image-modal-popup',
                    closeButton: 'image-modal-close'
                },
                didOpen: () => {
                    const popup = document.querySelector('.swal2-popup');
                    popup.style.maxWidth = '700px';
                }
            });
        }
    </script>
    <style>
        .image-modal-content img {
            max-height: 70vh;
            object-fit: contain;
            width: 100%;
        }
        .image-modal-popup {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .swal2-close {
            color: #6c757d;
            font-size: 2rem;
            transition: color 0.3s ease;
        }
        .swal2-close:hover {
            color: #495057;
        }
        .swal2-title {
            color: #343a40;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .swal2-description {
            color: #6c757d;
            font-size: 1rem;
        }
    </style>
@endpush
