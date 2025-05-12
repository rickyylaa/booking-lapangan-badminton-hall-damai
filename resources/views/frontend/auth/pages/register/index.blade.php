@extends('backend.auth.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-5 pb-5">
	<div class="container-fluid px-5 pt-5 pb-5">
        <div class="row">
            <div class="col-xl-5 mx-auto">
                <div class="card border rounded-0">
                    <div class="card-body px-5">
                        <form method="POST" action="{{ route('customer.processRegister') }}">
                            @csrf
                            <div class="row">
                                <div class="d-grid justify-content-center align-items-center mb-3">
                                    <span class="fw-normal text-center h5 mb-2">CREATE AN ACCOUNT</span>
                                    <span class="fw-normal text-center small mb-0">Looks like you're new here, we need a little more info:</span>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="fw-medium small h6">CREATE EMAIL ADDRESS<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="email" name="email" id="email" class="form-control" autocomplete="email" required autofocus>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="fw-medium small h6">FULL NAME<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" name="name" id="name" class="form-control" autocomplete="name" required>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="fw-medium small h6">PHONE<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" name="phone" id="phone" class="form-control" autocomplete="phone" required>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('phone') }}</p>
                                </div>
                                <div class="col-12 position-relative">
                                    <label for="password" class="fw-medium small h6">PASSWORD<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="password" name="password" id="password" class="form-control fakepassword" autocomplete="current-password" required>
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
                                        </span>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('password') }}</p>
                                </div>
                                <div class="col-12">
                                    <label for="address" class="fw-medium small h6">ADDRESS<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <textarea name="address" id="address" class="form-control" rows="2" autocomplete="address" required></textarea>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('address') }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-middle mb-3">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input me-2 rounded-0" {{ old('remember') ? 'checked' : '' }} required>
                                <label class="form-check-label fw-normal small" for="remember">
                                    I agree with the Privacy Policy.
                                </label>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-2 mt-3">
                                <button type="submit" class="btn btn-dark w-50 rounded-0">CONTINUE</button>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="fw-semibold small mb-0 me-2">Already created a email?</span>
                                <a href="{{ route('customer.login') }}" class="fw-normal small h6 text-decoration-underline mb-0">
                                    Try another email
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
