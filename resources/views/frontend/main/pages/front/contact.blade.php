@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
	<div class="container">
		<div class="row mb-5">
			<div class="col-xl-10">
				<h1>Have Any Question?</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="row g-4">
                    <div class="col-12">
                        <div class="card card-body bg-dark bg-opacity-10 text-center align-items-center rounded-0 h-100">
                            <div class="icon-lg bg-dark bg-opacity-10 text-dark rounded-circle mb-2">
                                <i class="fa-sharp fa-regular fa-location-dot fs-5"></i>
                            </div>
                            <h5>ADDRESS</h5>
                            <div class="d-grid gap-3 d-sm-block">
                                <a href="javascript:;" class="fw-normal text-dark-hover h6 small mb-0">
                                    NYC - 9th Avenue
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-body bg-dark bg-opacity-10 text-center align-items-center rounded-0 h-100">
                            <div class="icon-lg bg-dark bg-opacity-10 text-dark rounded-circle mb-2">
                                <i class="fa-sharp fa-regular fa-mobile fs-5"></i>
                            </div>
                            <h5>CONTACT</h5>
                            <div class="d-grid gap-3 d-sm-block">
                                <a href="javascript:;" class="fw-normal text-dark-hover h6 small mb-0">
                                    +62 812 3456 7890
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-body bg-dark bg-opacity-10 text-center align-items-center rounded-0 h-100">
                            <div class="icon-lg bg-dark bg-opacity-10 text-dark rounded-circle mb-2">
                                <i class="fa-sharp fa-regular fa-envelope fs-5"></i>
                            </div>
                            <h5>EMAIL</h5>
                            <div class="d-grid gap-3 d-sm-block">
                                <a href="javascript:;" class="fw-normal text-dark-hover h6 small mb-0">
                                    example@gmail.com
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            <div class="col-md-8">
				<div class="card bg-dark bg-opacity-10 rounded-0 p-4">
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('front.contactStore') }}" enctype="multipart/form-data">
                            @csrf @method('POST')
                            <div class="row">
                                <div class="d-grid justify-content-center align-items-center mb-4">
                                    <span class="fw-normal text-center h5 mb-0">SEND US MESSAGE</span>
                                    <p>Please complete the details below and then click on Submit and we’ll be in contact</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="fw-medium small h6">NAME<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="fw-medium small h6">EMAIL<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="col-12">
                                    <label for="phone" class="fw-medium small h6">PHONE<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="number" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('phone') }}</p>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="fw-medium small h6">MESSAGE<span class="fw-medium smaller h6">*</span></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <textarea name="message" id="message" class="form-control" rows="5">{{ old('message') }}</textarea>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('message') }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">CONTINUE</button>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>
<section class="pt-0 pt-lg-5">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<iframe class="w-100 h-300px grayscale rounded-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878428698!3d40.74076684379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sin!4v1586000412513!5m2!1sen!2sin" height="500" style="border:0;" aria-hidden="false" tabindex="0"></iframe>
			</div>
		</div>
	</div>
</section>
@endsection
