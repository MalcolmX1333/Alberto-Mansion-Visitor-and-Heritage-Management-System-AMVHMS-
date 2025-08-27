@extends('layouts.app')

@section('content')
    <body class="bg-gradient-success">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex bg-login-image" style="
                                background-color: #97CC70;
                                display: flex;
                                flex-direction: column;
                                justify-content: space-between;
                                min-height: 500px;
                                position: relative;
                                overflow: hidden;
                            ">
                                <!-- Image Container -->
                                <div class="login-image" style="
                                    height: 60%;
                                    background: url('{{ asset('BCHATO.webp') }}') center center no-repeat;
                                    background-size: contain;
                                    margin-top: 2rem;
                                    flex-grow: 1;
                                    max-width: 100%;
                                "></div>

                                <!-- Overlay Text -->
                                <div class="login-text text-center w-100" style="
                                    color: white;
                                    font-size: 1.5rem;
                                    font-weight: bold;
                                    padding: 1rem;
                                    background: linear-gradient(to bottom, transparent, #97CC70 20%);
                                ">
                                    <span style="color: white;">BCHATO: Preserving the</span> <br>
                                    <span style="color: white;">Heritage, Boosting Tourism</span> <br>
{{--                                    <span style="color: #007A33;">where the past meets the future</span>--}}
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <!-- Logo Image on Top of Login -->
                                        <img src="{{ asset('binanlogo.png') }}" alt="Logo" class="img-fluid mb-4" style="max-width: 150px;">

                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>

                                    <!-- Login Form -->
                                    <form method="POST" action="{{ route('login') }}" class="user">
                                        @csrf

                                        <!-- Email Input -->
                                        <div class="form-group mb-4">
                                            <input id="email"
                                                   type="email"
                                                   class="form-control form-control-user @error('email') is-invalid @enderror"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   autocomplete="email"
                                                   autofocus
                                                   placeholder="Enter Email Address...">

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
                                                       autocomplete="current-password"
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

                                        <!-- Remember Me Checkbox -->
                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       id="customCheck"
                                                       name="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>

                                        <!-- Login Button -->
                                        <button type="submit" class="btn btn-primary btn-user btn-block mb-4">
                                            Login
                                        </button>

                                        <hr class="mb-4">
                                    </form>

                                    <!-- Forgot Password Link -->
                                    <div class="text-center">
                                        @if (Route::has('password.request'))
                                            <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordInput = document.querySelector('#password');
            const passwordType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', passwordType);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    </body>
@endsection
