<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visitor Feedback</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #C9F1AA 0%, #A8E6CF 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .custom-background {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .page-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }
        .page-title::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 4px;
            background: linear-gradient(45deg, #4CAF50, #8BC34A);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        .section-title {
            color: #27ae60;
            font-weight: 600;
            font-size: 1.4rem;
            margin: 30px 0 20px 0;
            padding: 10px 20px;
            background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
            border-left: 4px solid #27ae60;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(248, 249, 250, 0.7);
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .form-group:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        .star-rating {
            direction: rtl;
            display: flex;
            justify-content: center;
            padding: 15px 0;
            gap: 5px;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            color: #ddd;
            font-size: 35px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #f39c12;
            transform: scale(1.1);
        }
        .star-rating label:hover {
            text-shadow: 0 0 10px rgba(243, 156, 18, 0.5);
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 custom-background">
            <h2 class="page-title">
                <i class="fas fa-comments mr-3"></i>Visitor Feedback
            </h2>

            <form method="POST" action="{{ route('feedback.survey.store') }}">
                @csrf

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                <!-- Questions for MUSEUM -->
                <h3 class="section-title">
                    <i class="fas fa-building mr-2"></i>MANSION EXPERIENCE
                </h3>

                <div class="form-group">
                    <label for="office_help">
                        <i class="fas fa-hands-helping mr-2"></i>
                        The office was willing to help, assist, and provide prompt service.
                    </label>
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="office_help{{ $i }}" name="office_help" value="{{ $i }}">
                            <label for="office_help{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label for="service_satisfaction">
                        <i class="fas fa-smile mr-2"></i>
                        I am generally satisfied with the service I availed.
                    </label>
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="service_satisfaction{{ $i }}" name="service_satisfaction" value="{{ $i }}">
                            <label for="service_satisfaction{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label for="staff_knowledge">
                        <i class="fas fa-user-graduate mr-2"></i>
                        The staff was capable and knowledgeable to perform their duties.
                    </label>
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="staff_knowledge{{ $i }}" name="staff_knowledge" value="{{ $i }}">
                            <label for="staff_knowledge{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label for="response_clarity">
                        <i class="fas fa-comments mr-2"></i>
                        The responses were clear and easily understood.
                    </label>
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="response_clarity{{ $i }}" name="response_clarity" value="{{ $i }}">
                            <label for="response_clarity{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>

                <h3 class="section-title">
                    <i class="fas fa-heart mr-2"></i>OVERALL EXPERIENCE
                </h3>

                <!-- Existing Questions -->
                <div class="form-group">
                    <label for="rating">
                        <i class="fas fa-star mr-2"></i>
                        How was your visit?
                    </label>
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                            <label for="star{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label for="feedback">
                        <i class="fas fa-pen mr-2"></i>
                        Share your feedback with us
                    </label>
                    <textarea class="form-control" id="feedback" name="feedback" rows="5"
                              placeholder="Tell us about your experience at Alberto Mansion..." required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
