<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visitor Information</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            margin: 30px auto;
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
        .welcome-text {
            text-align: center;
            color: #555;
            margin-bottom: 30px;
            font-size: 1.1rem;
            line-height: 1.6;
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
        .survey-container {
            background: rgba(248, 249, 250, 0.7);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }
        .museum-icon {
            font-size: 3rem;
            color: #27ae60;
            margin-bottom: 20px;
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
        .demographics-section {
            display: none;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 custom-background">
            <div class="text-center">
                <i class="fas fa-building museum-icon"></i>
                <h2 class="page-title">
                    Visitor Information
                </h2>
                <p class="welcome-text">
                    Welcome to Alberto Mansion Museum! Please share your information with us
                    to help us provide you with the best possible experience.
                </p>
            </div>

            <form method="POST" action="{{ route('guest.survey.store') }}" id="surveyForm">
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

                <div class="survey-container">
                    <!-- Hidden fields for registration type and visit datetime -->
                    <input type="hidden" name="registration_type" id="registration_type" required>
                    <input type="hidden" name="visit_datetime" id="visit_datetime" required>

                    <h3 class="section-title">
                        <i class="fas fa-user mr-2"></i>Personal Information
                    </h3>

                    <div class="form-group">
                        <label for="cn_bus_number">C.N. Bus Number</label>
                        <input type="text" class="form-control" id="cn_bus_number" name="cn_bus_number" required>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label for="address_affiliation">Address/Affiliation</label>
                        <input type="text" class="form-control" id="address_affiliation" name="address_affiliation" required>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" required>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="Male" required>
                                <label class="form-check-label" for="gender_male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="Female" required>
                                <label class="form-check-label" for="gender_female">Female</label>
                            </div>
                        </div>
                    </div>

                    <div class="demographics-section">
                        <h3 class="section-title">
                            <i class="fas fa-chart-bar mr-2"></i>Demographics
                        </h3>

                        <div class="form-group">
                            <label for="grade_school_students">No. of Students / Grade School</label>
                            <input type="number" class="form-control" id="grade_school_students" name="grade_school_students" min="0">
                        </div>

                        <div class="form-group">
                            <label for="high_school_students">No. of Students / High School</label>
                            <input type="number" class="form-control" id="high_school_students" name="high_school_students" min="0">
                        </div>

                        <div class="form-group">
                            <label for="college_students">No. of Students / College / GradSchool</label>
                            <input type="number" class="form-control" id="college_students" name="college_students" min="0">
                        </div>

                        <div class="form-group">
                            <label for="pwd">PWD</label>
                            <input type="text" class="form-control" id="pwd" name="pwd">
                        </div>

                        <div class="form-group">
                            <label for="age_17_below">17 y/o and below</label>
                            <input type="number" class="form-control" id="age_17_below" name="age_17_below" min="0">
                        </div>

                        <div class="form-group">
                            <label for="age_18_30">18-30 y/o</label>
                            <input type="number" class="form-control" id="age_18_30" name="age_18_30" min="0">
                        </div>

                        <div class="form-group">
                            <label for="age_31_45">31-45 y/o</label>
                            <input type="number" class="form-control" id="age_31_45" name="age_31_45" min="0">
                        </div>

                        <div class="form-group">
                            <label for="age_60_above">60 y/o and above</label>
                            <input type="number" class="form-control" id="age_60_above" name="age_60_above" min="0">
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Information
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show registration type selection on page load
    Swal.fire({
        title: 'Registration Type',
        text: 'Please select your registration type:',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Individual',
        cancelButtonText: 'Group',
        confirmButtonColor: '#27ae60',
        cancelButtonColor: '#3498db',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        let registrationType = result.isConfirmed ? 'Individual' : 'Group';

        // Set the registration type in the hidden field
        document.getElementById('registration_type').value = registrationType;

        // Show/hide demographics section based on registration type
        let demographicsSection = document.querySelector('.demographics-section');
        let demographicsInputs = demographicsSection.querySelectorAll('input');

        if (registrationType === 'Individual') {
            // Hide demographics for Individual registration
            demographicsSection.style.display = 'none';
            // Remove required validation for demographics fields when hidden
            demographicsInputs.forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            // Show demographics for Group registration
            demographicsSection.style.display = 'block';
            // Add required validation for demographics fields when shown
            demographicsInputs.forEach(input => {
                if (input.type === 'number' || input.name === 'pwd') {
                    input.setAttribute('required', 'required');
                }
            });
        }

        // Show visit date/time modal
        showVisitDateModal();
    });

    function showVisitDateModal() {
        Swal.fire({
            title: 'Visit Date & Time',
            html: `
                <div class="form-group text-left">
                    <label for="visitDateTime" class="form-label">Select your preferred visit date and time:</label>
                    <input type="datetime-local" id="visitDateTime" class="form-control" min="${new Date().toISOString().slice(0, 16)}">
                </div>
            `,
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#27ae60',
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                const visitDateTime = document.getElementById('visitDateTime').value;
                if (!visitDateTime) {
                    Swal.showValidationMessage('Please select a visit date and time');
                    return false;
                }
                return visitDateTime;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Set the visit date/time in the hidden field
                document.getElementById('visit_datetime').value = result.value;
            }
        });
    }
});
</script>

</body>
</html>
