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
        .form-control[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        .form-check-input:disabled + .form-check-label {
            opacity: 0.6;
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
        .bus-number-field {
            display: none;
        }
        .user-info-badge {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 custom-background">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bx bx-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning">
                    <i class="bx bx-error-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif
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






            @if($errors->any())
                    <div class="alert alert-warning">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="survey-container">
                    <!-- Hidden fields for registration type and visit datetime -->
                    <input type="hidden" name="registration_type" id="registration_type" required>
                    <input type="hidden" name="visit_datetime" id="visit_datetime" required>

                    <!-- User info display for individual registration -->
                    @auth
                    <div class="user-info-badge" id="userInfoBadge" style="display: none;">
                        <i class="fas fa-user mr-2"></i>Using your account information: {{ auth()->user()->name }}
                    </div>
                    @endauth

                    <h3 class="section-title">
                        <i class="fas fa-user mr-2"></i>Personal Information
                    </h3>

                    <!-- Bus number field - only for groups -->
                    <div class="form-group bus-number-field">
                        <label for="cn_bus_number">C.N. Bus Number</label>
                        <input type="text" class="form-control" id="cn_bus_number" name="cn_bus_number">
                    </div>

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                               value="{{ old('full_name', auth()->check() ? auth()->user()->name : '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="address_affiliation">Address/Affiliation</label>
                        <input type="text" class="form-control" id="address_affiliation" name="address_affiliation"
                               value="{{ old('address_affiliation', auth()->check() ? auth()->user()->address : '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality"
                               value="{{ old('nationality', auth()->check() ? auth()->user()->nationality : '') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Gender <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="Male"
                                       {{ old('gender', auth()->check() ? auth()->user()->gender : '') === 'Male' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="gender_male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="Female"
                                       {{ old('gender', auth()->check() ? auth()->user()->gender : '') === 'Female' ? 'checked' : '' }} required>
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
                            <input type="number" class="form-control" id="grade_school_students" name="grade_school_students"
                                   value="{{ old('grade_school_students') }}" min="0">
                        </div>

                        <div class="form-group">
                            <label for="high_school_students">No. of Students / High School</label>
                            <input type="number" class="form-control" id="high_school_students" name="high_school_students"
                                   value="{{ old('high_school_students') }}" min="0">
                        </div>

                        <div class="form-group">
                            <label for="college_students">No. of Students / College / GradSchool</label>
                            <input type="number" class="form-control" id="college_students" name="college_students"
                                   value="{{ old('college_students') }}" min="0">
                        </div>

                        <div class="form-group">
                            <label for="pwd">PWD</label>
                            <input type="text" class="form-control" id="pwd" name="pwd" value="{{ old('pwd') }}">
                        </div>

                        <div class="form-group">
                            <label for="age_17_below">17 y/o and below</label>
                            <input type="number" class="form-control" id="age_17_below" name="age_17_below"
                                   value="{{ old('age_17_below') }}" min="0">
                        </div>

                        <div class="form-group">
                            <label for="age_18_30">18-30 y/o</label>
                            <input type="number" class="form-control" id="age_18_30" name="age_18_30"
                                   value="{{ old('age_18_30') }}" min="0">
                        </div>

                        <div class="form-group">
                            <label for="age_31_45">31-45 y/o</label>
                            <input type="number" class="form-control" id="age_31_45" name="age_31_45"
                                   value="{{ old('age_31_45') }}" min="0">
                        </div>

                        <div class="form-group">
                            <label for="age_60_above">60 y/o and above</label>
                            <input type="number" class="form-control" id="age_60_above" name="age_60_above"
                                   value="{{ old('age_60_above') }}" min="0">
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
    const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

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

        // Show/hide sections based on registration type
        let demographicsSection = document.querySelector('.demographics-section');
        let busNumberField = document.querySelector('.bus-number-field');
        let demographicsInputs = demographicsSection.querySelectorAll('input');
        let busNumberInput = document.getElementById('cn_bus_number');
        let userInfoBadge = document.getElementById('userInfoBadge');

        // Get form fields
        let fullNameInput = document.getElementById('full_name');
        let addressInput = document.getElementById('address_affiliation');
        let nationalityInput = document.getElementById('nationality');
        let genderInputs = document.querySelectorAll('input[name="gender"]');

        if (registrationType === 'Individual') {
            // Hide demographics and bus number for Individual registration
            demographicsSection.style.display = 'none';
            busNumberField.style.display = 'none';

            // Remove required validation for demographics
            demographicsInputs.forEach(input => {
                input.removeAttribute('required');
            });
            busNumberInput.removeAttribute('required');

            // If user is logged in, make personal fields readonly and show badge
            if (isLoggedIn) {
                userInfoBadge.style.display = 'block';
                fullNameInput.setAttribute('readonly', 'readonly');
                addressInput.setAttribute('readonly', 'readonly');
                nationalityInput.setAttribute('readonly', 'readonly');

                // Disable gender radio buttons but keep them checked
                genderInputs.forEach(input => {
                    if (input.checked) {
                        input.setAttribute('disabled', 'disabled');
                        // Add a hidden input to ensure the value is submitted
                        let hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'gender';
                        hiddenInput.value = input.value;
                        input.parentNode.appendChild(hiddenInput);
                    }
                });
            } else {
                // Clear values and make editable for guests
                fullNameInput.value = '';
                addressInput.value = '';
                nationalityInput.value = '';
                genderInputs.forEach(input => {
                    input.checked = false;
                    input.removeAttribute('disabled');
                });
            }
        } else {
            // Show demographics and bus number for Group registration
            demographicsSection.style.display = 'block';
            busNumberField.style.display = 'block';
            userInfoBadge.style.display = 'none';

            // Add required validation for demographics
            demographicsInputs.forEach(input => {
                if (input.type === 'number' || input.name === 'pwd') {
                    input.setAttribute('required', 'required');
                }
            });
            busNumberInput.setAttribute('required', 'required');

            // Make personal fields editable regardless of login status
            fullNameInput.removeAttribute('readonly');
            addressInput.removeAttribute('readonly');
            nationalityInput.removeAttribute('readonly');

            // Enable gender radio buttons and remove hidden inputs
            genderInputs.forEach(input => {
                input.removeAttribute('disabled');
                // Remove any hidden gender inputs from previous individual selection
                let hiddenInputs = input.parentNode.querySelectorAll('input[type="hidden"][name="gender"]');
                hiddenInputs.forEach(hidden => hidden.remove());
            });

            // Clear pre-filled values for group registration
            fullNameInput.value = '';
            addressInput.value = '';
            nationalityInput.value = '';
            genderInputs.forEach(input => {
                input.checked = false;
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
