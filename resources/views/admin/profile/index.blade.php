@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Profile</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Profile</h4>
                    <form class="forms-sample" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
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
                            @if($user->profile_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" width="80">
                                </div>
                            @endif
                            <input type="file" name="profile_image" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-dark">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
