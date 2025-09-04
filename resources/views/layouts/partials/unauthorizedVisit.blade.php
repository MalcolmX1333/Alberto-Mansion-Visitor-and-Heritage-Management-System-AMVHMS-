@extends('layouts.guest-app')

@section('content')
    <style>
        /* Modern warning color palette */
        :root {
            --warning-red: #dc3545;
            --warning-orange: #fd7e14;
            --warning-yellow: #ffc107;
            --warning-dark: #212529;
            --warning-light: #f8f9fa;
            --warning-cream: #fff3cd;
            --modern-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --warning-shadow: 0 8px 25px rgba(220, 53, 69, 0.15);
        }

        /* Warning Animation */
        @keyframes warning-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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

        /* Warning Area */
        .warning-area {
            background: linear-gradient(135deg, var(--warning-cream) 0%, #ffffff 100%);
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .warning-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--warning-red), transparent);
        }

        .warning-container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: var(--modern-shadow);
            border: 2px solid rgba(220, 53, 69, 0.1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
        }

        .warning-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--warning-red), var(--warning-orange));
        }

        .warning-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, var(--warning-red), var(--warning-orange));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: warning-pulse 2s ease-in-out infinite;
            box-shadow: var(--warning-shadow);
        }

        .warning-icon i {
            font-size: 60px;
            color: white;
            animation: shake 0.5s ease-in-out;
        }

        .warning-title {
            color: var(--warning-dark);
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
        }

        .warning-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--warning-red), var(--warning-orange));
            border-radius: 2px;
        }

        .warning-message {
            color: var(--warning-dark);
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .warning-details {
            background: var(--warning-cream);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid var(--warning-orange);
        }

        .warning-details h4 {
            color: var(--warning-dark);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .warning-details ul {
            text-align: left;
            color: var(--warning-dark);
            margin-bottom: 0;
        }

        .warning-details li {
            margin-bottom: 8px;
        }

        .warning-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .warning-btn {
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .warning-btn.primary {
            background: linear-gradient(135deg, var(--warning-red), var(--warning-orange));
            color: white;
            border: 2px solid transparent;
        }

        .warning-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
            color: white;
            text-decoration: none;
        }

        .warning-btn.secondary {
            background: transparent;
            color: var(--warning-red);
            border: 2px solid var(--warning-red);
        }

        .warning-btn.secondary:hover {
            background: var(--warning-red);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .warning-container {
                padding: 40px 30px;
            }

            .warning-title {
                font-size: 28px;
            }

            .warning-actions {
                flex-direction: column;
                align-items: center;
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
                        Access Denied
                    </h1>
                    <p class="text-white link-nav"><a href="{{ route('home') }}">Home</a> <span class="lnr lnr-arrow-right"></span> <span>Unauthorized Access</span></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!-- Start Warning Area -->
    <section class="warning-area pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="warning-container">
                        <div class="warning-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>

                        <h1 class="warning-title">Access Restricted</h1>

                        <p class="warning-message">
                            Sorry, you don't have the required permissions to mark this visit. This action is restricted to authorized museum administrators only.
                        </p>

                        <div class="warning-details">
                            <h4>To mark a visit, you need to:</h4>
                            <ul>
                                <li>Be logged in to your account</li>
                                <li>Have Administrator privileges</li>
                                <li>Be authorized by the museum management</li>
                            </ul>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="warning-actions">
                            @guest
                                <a href="{{ route('login') }}" class="warning-btn primary">
                                    <i class="fa fa-sign-in"></i>
                                    Login to Continue
                                </a>
                            @else
                                <a href="{{ route('home') }}" class="warning-btn primary">
                                    <i class="fa fa-home"></i>
                                    Return Home
                                </a>
                            @endguest
                            <a href="#" class="warning-btn secondary">
                                <i class="fa fa-envelope"></i>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Warning Area -->

@endsection
