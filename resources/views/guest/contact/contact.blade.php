@extends('layouts.guest-app')

@section('content')
    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white pt-5">
                        Contact Us
                    </h1>
                    <p class="text-white link-nav">
                        <a href="{{ url('/') }}">Home </a>
                        <span class="lnr lnr-arrow-right"></span>
                        <a href="{{ route('contact') }}"> Contact</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <section class="contact-area py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 text-center">
                    <h2 class="mb-3">Contact Us</h2>
                    <p class="lead text-muted">
                        For inquiries, please fill out the form below or email us at
                        <a href="mailto:info@albertomansion.ph">info@albertomansion.ph</a>.
                    </p>
                </div>
            </div>
            <div class="row">
                <!-- Google Map -->
                <div class="col-lg-6 mb-4">
                    <div class="map-responsive rounded shadow-sm">
                        <iframe
                            src="https://www.google.com/maps?q=Alberto+Mansion,+Biñan&output=embed"
                            width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="primary-btn text-uppercase">Send Inquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
    .map-responsive {
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
    }
    .map-responsive iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
    }
    </style>
@endsection
