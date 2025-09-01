@extends('layouts.app')

@section('content')
    <body class="bg-gradient-success">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex bg-register-image" style="
                                background-color: #97CC70;
                                display: flex;
                                flex-direction: column;
                                justify-content: space-between;
                                min-height: 600px;
                                position: relative;
                                overflow: hidden;
                            ">
                                <!-- Image Container -->
                                <div class="register-image" style="
                                    height: 60%;
                                    background: url('{{ asset('BCHATO.webp') }}') center center no-repeat;
                                    background-size: contain;
                                    margin-top: 2rem;
                                    flex-grow: 1;
                                    max-width: 100%;
                                "></div>

                                <!-- Overlay Text -->
                                <div class="register-text text-center w-100" style="
                                    color: white;
                                    font-size: 1.5rem;
                                    font-weight: bold;
                                    padding: 1rem;
                                    background: linear-gradient(to bottom, transparent, #97CC70 20%);
                                ">
                                    <span style="color: white;">Join BCHATO Today!</span> <br>
                                    <span style="color: white;">Discover Heritage & Tourism</span> <br>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <!-- Logo Image on Top of Register -->
                                        <img src="{{ asset('binanlogo.png') }}" alt="Logo" class="img-fluid mb-4" style="max-width: 150px;">

                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>

                                    <!-- Register Form -->
                                    <form method="POST" action="{{ route('register') }}" class="user">
                                        @csrf

                                        <!-- Name Input -->
                                        <div class="form-group mb-4">
                                            <input id="name"
                                                   type="text"
                                                   class="form-control form-control-user @error('name') is-invalid @enderror"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   required
                                                   autocomplete="name"
                                                   autofocus
                                                   placeholder="Full Name">

                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Email Input -->
                                        <div class="form-group mb-4">
                                            <input id="email"
                                                   type="email"
                                                   class="form-control form-control-user @error('email') is-invalid @enderror"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   autocomplete="email"
                                                   placeholder="Email Address">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Password Input -->
                                        <div class="form-group mb-4 position-relative">
                                            <div class="position-relative">
                                                <input id="password"
                                                       type="password"
                                                       class="form-control form-control-user @error('password') is-invalid @enderror"
                                                       name="password"
                                                       required
                                                       autocomplete="new-password"
                                                       placeholder="Password"
                                                       style="padding-right: 40px;">
                                                <span toggle="#password"
                                                      class="fa fa-fw fa-eye field-icon toggle-password"
                                                      style="
                                                          cursor: pointer;
                                                          position: absolute;
                                                          right: 15px;
                                                          top: 50%;
                                                          transform: translateY(-50%);
                                                          z-index: 2;
                                                      ">
                                                </span>
                                            </div>

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password Input -->
                                        <div class="form-group mb-4 position-relative">
                                            <div class="position-relative">
                                                <input id="password-confirm"
                                                       type="password"
                                                       class="form-control form-control-user"
                                                       name="password_confirmation"
                                                       required
                                                       autocomplete="new-password"
                                                       placeholder="Repeat Password"
                                                       style="padding-right: 40px;">
                                                <span toggle="#password-confirm"
                                                      class="fa fa-fw fa-eye field-icon toggle-password-confirm"
                                                      style="
                                                          cursor: pointer;
                                                          position: absolute;
                                                          right: 15px;
                                                          top: 50%;
                                                          transform: translateY(-50%);
                                                          z-index: 2;
                                                      ">
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Register Button -->
                                        <button type="submit" class="btn btn-primary btn-user btn-block mb-4">
                                            Register Account
                                        </button>

                                        <hr class="mb-4">
                                    </form>

                                    <!-- Login Link -->
                                    <div class="text-center">
                                        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle Password Visibility
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordInput = document.querySelector('#password');
            const passwordType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', passwordType);
            this.classList.toggle('fa-eye-slash');
        });

        // Toggle Confirm Password Visibility
        document.querySelector('.toggle-password-confirm').addEventListener('click', function () {
            const passwordInput = document.querySelector('#password-confirm');
            const passwordType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', passwordType);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    </body>
@endsection
