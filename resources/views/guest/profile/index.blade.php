@extends('layouts.guest-app')

@section('content')
    <style>
        .profile-card {
            height: 400px;
            display: flex;
            flex-direction: column;
        }
        .profile-card img {
            height: 120px;
            width: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            align-self: center;
        }
        .profile-card .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .profile-card .primary-btn {
            align-self: flex-start;
            margin-top: auto;
        }
    </style>

    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white pt-5">
                        Profile
                    </h1>
                    <p class="text-white link-nav">
                        <a href="{{ url('/') }}">Home </a>
                        <span class="lnr lnr-arrow-right"></span>
                        <a href="#"> Guest</a>
                        <span class="lnr lnr-arrow-right"></span>
                        <a href="#"> Profile</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <section class="profile-card-area section-gap" id="profile-card">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 col-md-8 mb-4">
                    <div class="card profile-card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Edit Profile</h4>
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image">
                            @endif
                            <form class="forms-sample" method="POST" action="{{ route('guest.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                                </div>
                                <div class="form-group">
                                    <label for="nationality">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality', $user->nationality) }}">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control-file">
                                </div>
                                <button type="submit" class="primary-btn text-uppercase mr-2">Update</button>
                                <a href="{{ url()->previous() }}" class="btn btn-dark">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
