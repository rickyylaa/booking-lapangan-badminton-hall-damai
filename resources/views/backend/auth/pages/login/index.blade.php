@extends('backend.auth.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-5 pb-5">
	<div class="container-fluid px-5 pt-5 pb-5">
        <div class="row">
            <div class="col-xl-5 mx-auto">
                <div class="card border rounded-0">
                    <div class="card-body px-5">
                        <form method="POST" action="{{ route('admin.processLogin') }}">
                            @csrf
                            <div class="row">
                                <div class="d-grid justify-content-center align-items-center mb-3">
                                    <span class="fw-normal text-center h5 mb-2">LOG IN</span>
                                    <span class="fw-normal text-center small mb-0">Welcome back. Enter your email and password to continue.</span>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="fw-medium small h6">EMAIL ADDRESS</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="email" name="email" id="email" class="form-control" required autofocus>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="col-12 position-relative">
                                    <label for="password" class="fw-medium small h6">PASSWORD</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="password" name="password" id="password" class="form-control fakepassword" required>
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
                                        </span>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('password') }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-3 mt-3">
                                <button type="submit" class="btn btn-dark w-50 rounded-0">CONTINUE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
