@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
    <div class="container">
        <div class="row mb-5">
			<div class="col-xl-10 mb-3">
				<h1>My Profile</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
		</div>
        <div class="row g-4 g-lg-5">
            <div class="col-12">
                <div class="card bg-dark bg-opacity-10 rounded-0 p-4">
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('customer.settingUpdate') }}" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="d-grid justify-content-center align-items-center mb-4">
                                    <span class="fw-normal text-center h5 mb-0">SETTING FORM</span>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="fw-medium small h6">YOUR EMAIL ADDRESS</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $customer->email) }}" readonly>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="fw-medium small h6">FULL NAME</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="fw-medium small h6">PHONE</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('phone') }}</p>
                                </div>
                                <div class="col-12 position-relative">
                                    <label for="password" class="fw-medium small h6">PASSWORD</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="password" name="password" id="password" class="form-control fakepassword">
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
                                        </span>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('password') }}</p>
                                </div>
                                <div class="col-12">
                                    <label for="image" class="fw-medium small h6 mb-3">UPLOAD IMAGE
                                        <a href="#" class="text-dark-hover h6 mb-0" role="button" id="info" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-sharp fa-solid fa-circle-info"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-w-sm dropdown-menu-start min-w-auto shadow rounded" aria-labelledby="info">
                                            <li>
                                                <div class="d-flex justify-content-between">
                                                    <span class="small">Only JPG, JPEG, PNG, GIF, and WEBP.</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </label>
                                    <div class="form-border-bottom form-control-transparent d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="form-fs-md mb-3 px-lg-3">
                                                <input type="file" name="image" id="image" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('image') }}</p>
                                </div>
                                <div class="col-12">
                                    <label for="address" class="fw-medium small h6">ADDRESS</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <textarea name="address" id="address" class="form-control" rows="2" required>{{ old('address', $customer->address) }}</textarea>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('address') }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-2">BACK</a>
                                <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">CONTINUE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/css/flatpickr.min.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/js/flatpickr.min.js') }}"></script>
@endsection
