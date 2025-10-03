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

        /* Service Area Modernization */
        .service-area {
            background: linear-gradient(135deg, var(--retro-cream) 0%, #ffffff 100%);
            position: relative;
        }

        .service-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--retro-gold), transparent);
        }

        .single-service {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            transition: all 0.4s ease;
            border: 1px solid rgba(212, 175, 55, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: var(--modern-shadow);
            margin-bottom: 30px;
        }

        .single-service:hover {
            transform: translateY(-8px);
            box-shadow: var(--retro-shadow);
            border-color: var(--retro-gold);
        }

        .single-service span {
            display: inline-block;
            width: 80px;
            height: 80px;
            line-height: 80px;
            font-size: 32px;
            color: var(--retro-gold);
            background: linear-gradient(135deg, var(--retro-cream), #ffffff);
            border-radius: 50%;
            margin-bottom: 25px;
            border: 3px solid var(--retro-gold);
            transition: all 0.3s ease;
        }

        .single-service:hover span {
            background: linear-gradient(135deg, var(--retro-gold), var(--retro-accent));
            color: white;
            transform: scale(1.1);
        }

        .single-service h4 {
            color: var(--retro-dark);
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .single-service > p {
            color: var(--retro-brown);
            font-size: 16px;
            font-weight: 500;
            background: var(--retro-cream);
            padding: 12px 20px;
            border-radius: 25px;
            display: inline-block;
        }

        .single-service .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.95), rgba(184, 134, 11, 0.95));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .single-service:hover .overlay {
            opacity: 1;
        }

        .single-service .overlay .text {
            color: white;
            font-weight: 500;
            text-align: center;
            padding: 20px;
            transform: translateY(20px);
            transition: transform 0.4s ease 0.1s;
        }

        .single-service:hover .overlay .text {
            transform: translateY(0);
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

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .quote-left {
            position: relative;
            z-index: 2;
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

        .quote-right {
            position: relative;
            z-index: 2;
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

        .quote-right a {
            color: var(--retro-gold);
            text-decoration: none;
            font-weight: 600;
            padding: 12px 25px;
            border: 2px solid var(--retro-gold);
            border-radius: 25px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .quote-right a:hover {
            background: var(--retro-gold);
            color: var(--retro-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        /* Exhibition Area Modernization */
        .exibition-area {
            background: linear-gradient(135deg, #ffffff 0%, var(--retro-cream) 100%);
            position: relative;
        }

        .exibition-area .title h1 {
            color: var(--retro-dark);
            font-size: 36px;
            font-weight: 600;
            position: relative;
            display: inline-block;
        }

        .exibition-area .title h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--retro-gold), var(--retro-accent));
            border-radius: 2px;
        }

        .single-exibition {
            background: white;
            padding: 35px;
            border-radius: 20px;
            margin: 15px;
            box-shadow: var(--modern-shadow);
            border: 1px solid rgba(212, 175, 55, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .single-exibition::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--retro-gold), var(--retro-accent));
        }

        .single-exibition:hover {
            transform: translateY(-5px);
            box-shadow: var(--retro-shadow);
        }

        .single-exibition h4 {
            color: var(--retro-dark);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            border-left: 4px solid var(--retro-gold);
            padding-left: 15px;
        }

        /* Gallery Area Modernization */
        .gallery-area {
            background: linear-gradient(135deg, var(--retro-dark) 0%, #1a0f08 100%);
            position: relative;
        }

        .gallery-area .title h1 {
            color: white;
            font-size: 36px;
            font-weight: 600;
            position: relative;
            display: inline-block;
        }

        .gallery-area .title h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--retro-gold), white);
            border-radius: 2px;
        }

        .gallery-area .title p {
            color: var(--retro-cream);
            font-size: 16px;
            line-height: 1.8;
            margin-top: 20px;
        }

        .single-gallery {
            display: block;
            margin-bottom: 20px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            transition: all 0.4s ease;
            box-shadow: var(--modern-shadow);
        }

        .single-gallery::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.8), rgba(184, 134, 11, 0.8));
            opacity: 0;
            transition: all 0.4s ease;
        }

        .single-gallery:hover::after {
            opacity: 1;
        }

        .single-gallery:hover {
            transform: scale(1.05);
            box-shadow: var(--retro-shadow);
        }

        .grid-item {
            width: 100%;
            height: auto;
            border-radius: 16px;
            transition: all 0.4s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quote-left h1 {
                font-size: 24px;
            }

            .single-service {
                margin-bottom: 20px;
            }

            .single-exibition {
                margin: 10px 0;
            }
        }
    </style>

    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row fullscreen d-flex align-items-center justify-content-center">
                <div class="banner-content col-lg-8">
                    <h6 class="text-white">Discover the heritage of the Philippines</h6>
                    <h1 class="text-white">
                        Alberto Mansion Museum
                    </h1>
                    <p class="pt-20 pb-20 text-white">
                        A historical landmark showcasing the rich cultural heritage and history of Binan City, Laguna.
                    </p>
                    <a href="{{route('guest.survey.create')}}" class="primary-btn text-uppercase">Visit Now!</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start Service Area -->
    <section class="service-area pt-100 pb-100" id="about">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-60">
                    <h2 style="color: var(--retro-dark); font-size: 32px; font-weight: 600;">Visitor Experience Ratings</h2>
                    <p style="color: var(--retro-brown); font-size: 16px; margin-top: 15px;">Discover what makes Alberto Mansion Museum a memorable destination</p>
                </div>
            </div>
            <div class="row">
                @foreach($averages as $average)
                    <div class="col-lg-3 col-md-6">
                        <div class="single-service">
                            <span class="{{ $average->icon }}"></span>
                            <h4>{{ $average->title }}</h4>
                            <p>
                                {{ number_format($average->average_value, 2) }} ★
                            </p>
                            <div class="overlay">
                                <div class="text">
                                    <p>
                                        Our visitors rated "{{ $average->title }}" with an average score of <strong>{{ number_format($average->average_value, 2) }}</strong> stars.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End Service Area -->

    <!-- Start Quote Area -->
    <section class="quote-area section-gap">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 quote-left">
                    <h1>
                        "<span>Architecture is a visual art</span>, and the buildings speak for themselves through the stories they hold
                        within their <span>historic walls</span>."
                    </h1>
                </div>
                <div class="col-lg-6 quote-right">
                    <p>
                        <strong>The Alberto Mansion stands as one of the finest examples of colonial architecture in Binan City.
                            Built during the Spanish colonial period, this magnificent structure has witnessed generations of Filipino families
                            and serves as a testament to the rich cultural heritage of Laguna province.
                            The mansion showcases traditional Filipino-Spanish architectural elements and houses artifacts that tell the story of local history.
                        </strong>
                    </p>
                    <a href="#" target="_blank">Explore History</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End Quote Area -->

    <!-- Start Exhibition Area -->
    <section class="exibition-area section-gap" id="exhibitions">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-60 col-lg-10">
                    <div class="title text-center">
                        <h1 class="mb-10">Visitor Feedback Stories</h1>
                        <p style="color: var(--retro-green); font-size: 18px; font-weight: 500;">Real experiences from our valued guests who explored the mansion's heritage</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="active-exibition-carusel col-12">
                    @foreach($results as $result)
                        <div class="single-exibition item">
                            <h4>{{ $result->survey_name }}</h4>
                            <p style="color: var(--retro-green); font-weight: 500; font-size: 16px;">{{ $result->question_content }}</p>
                            <p style="color: var(--retro-brown); font-size: 15px; line-height: 1.6; margin: 20px 0;">{!! $result->answer_value !!}</p>
                            <p style="color: var(--retro-accent); font-weight: 600; font-style: italic;">— Anonymous Visitor</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- End Exhibition Area -->

    <!-- Start Gallery Area -->
    <section class="gallery-area section-gap" id="gallery">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-10">
                    <div class="title text-center">
                        <h1 class="mb-10">Alberto Mansion Heritage Gallery</h1>
                        <p>Step into our Historic Gallery, where the architectural beauty and cultural heritage of Alberto Mansion come alive!
                            Here, we proudly showcase the mansion's colonial elegance, antique collections, and the stories of families who once called this place home.
                            Explore the timeless charm that connects Binan City's past with its vibrant present.</p>
                    </div>
                </div>
            </div>
            <div id="grid-container" class="row">
                @for($i = 1; $i <= 12; $i++)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a class="single-gallery" href="{{ asset('asset/welcome/' . $i . '.jpeg') }}">
                            <img class="grid-item" src="{{ asset('asset/welcome/' . $i . '.jpeg') }}" loading="lazy" alt="Gallery Image {{ $i }}">
                        </a>
                    </div>
                @endfor
            </div>

        </div>
    </section>
    <!-- End Gallery Area -->

@endsection


