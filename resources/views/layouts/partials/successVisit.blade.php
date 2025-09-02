@extends('layouts.guest-app')

@section('content')
    <style>
        .fa-star {
            color: #d4af37;
        }

        /* Modern retro color palette */
        :root {
            --retro-gold: #d4af37;
            --retro-cream: #f5f2e8;
            --retro-brown: #8b4513;
            --retro-dark: #2c1810;
            --retro-green: #5e8c00;
            --retro-accent: #b8860b;
            --modern-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --retro-shadow: 0 8px 25px rgba(212, 175, 55, 0.15);
        }

        /* Success Animation */
        @keyframes checkmark {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Area */
        .success-area {
            background: linear-gradient(135deg, var(--retro-cream) 0%, #ffffff 100%);
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .success-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--retro-gold), transparent);
        }

        .success-container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: var(--modern-shadow);
            border: 1px solid rgba(212, 175, 55, 0.1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
        }

        .success-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--retro-gold), var(--retro-accent));
        }

        .success-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, var(--retro-gold), var(--retro-accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 3s ease-in-out infinite;
            box-shadow: var(--retro-shadow);
        }

        .success-icon svg {
            width: 60px;
            height: 60px;
            stroke: white;
            stroke-width: 3;
            fill: none;
            stroke-dasharray: 100;
            animation: checkmark 1s ease-in-out 0.5s forwards;
        }

        .success-title {
            color: var(--retro-dark);
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
        }

        .success-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--retro-gold), var(--retro-accent));
            border-radius: 2px;
        }

        .success-message {
            color: var(--retro-brown);
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .success-details {
            background: var(--retro-cream);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid var(--retro-gold);
        }

        .success-details h4 {
            color: var(--retro-dark);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .success-details p {
            color: var(--retro-brown);
            margin-bottom: 10px;
        }

        .success-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .success-btn {
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .success-btn.primary {
            background: linear-gradient(135deg, var(--retro-gold), var(--retro-accent));
            color: white;
            border: 2px solid transparent;
        }

        .success-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
            color: white;
            text-decoration: none;
        }

        .success-btn.secondary {
            background: transparent;
            color: var(--retro-gold);
            border: 2px solid var(--retro-gold);
        }

        .success-btn.secondary:hover {
            background: var(--retro-gold);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Quote Area Modernization */
        .quote-area {
            background: linear-gradient(135deg, var(--retro-dark) 0%, #1a0f08 100%);
            position: relative;
            overflow: hidden;
        }

        .quote-area::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }

        .quote-left h1 {
            font-size: 32px;
            line-height: 1.4;
            color: white;
            font-weight: 300;
            margin-bottom: 0;
        }

        .quote-left h1 span {
            color: var(--retro-gold);
            font-weight: 600;
            position: relative;
        }

        .quote-left h1 span::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--retro-gold), transparent);
        }

        .quote-right p {
            color: var(--retro-cream);
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .quote-right strong {
            color: white;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .success-container {
                padding: 40px 30px;
            }

            .success-title {
                font-size: 28px;
            }

            .success-actions {
                flex-direction: column;
                align-items: center;
            }

            .quote-left h1 {
                font-size: 24px;
            }
        }
    </style>

    <!-- Start Banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white pt-5">
                        Visit Confirmed!
                    </h1>
                    <p class="text-white link-nav"><a href="{{ route('home') }}">Home</a> <span class="lnr lnr-arrow-right"></span> <span>Visit Success</span></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!-- Start Success Area -->
    <section class="success-area pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="success-container">
                        <div class="success-icon">
                            <svg viewBox="0 0 52 52">
                                <path d="M14,27 L22,35 L38,19"></path>
                            </svg>
                        </div>

                        <h1 class="success-title">Welcome to Alberto Mansion!</h1>

                        <p class="success-message">
                            Your visit has been successfully confirmed! Thank you for choosing to explore the rich heritage and cultural treasures of Alberto Mansion Museum.
                        </p>

                        <div class="success-details">
                            <h4>Your Visit Details</h4>
                            <p><strong>Status:</strong> <span style="color: var(--retro-green);">✓ Visited</span></p>
                            <p><strong>Date:</strong> {{ date('F j, Y') }}</p>
                            <p><strong>Time:</strong> {{ date('g:i A') }}</p>
                        </div>

                        <div class="success-actions">
                            <a href="{{ route('guest.survey.create') }}" class="success-btn primary">
                                <i class="fa fa-star"></i>
                                Share Your Experience
                            </a>
                            <a href="{{ route('home') }}" class="success-btn secondary">
                                <i class="fa fa-home"></i>
                                Return Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Success Area -->

    <!-- Start Quote Area -->
    <section class="quote-area section-gap">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 quote-left">
                    <h1>
                        "Thank you for <span>visiting</span> and being part of our <span>heritage story</span>."
                    </h1>
                </div>
                <div class="col-lg-6 quote-right">
                    <p>
                        <strong>Your visit to Alberto Mansion helps preserve and share the rich cultural heritage of Binan City.
                            We hope you enjoyed exploring our colonial architecture, historical artifacts, and the stories that connect
                            our past with the present. Your experience matters to us and contributes to keeping this
                            important piece of Filipino history alive for future generations.
                        </strong>
                    </p>
                    <p style="color: var(--retro-cream); font-style: italic;">
                        We would love to hear about your experience. Please consider sharing your feedback to help us improve our services.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Quote Area -->

@endsection
