@extends('backend.main.layouts.app')
@section('title', 'Booking')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">MY PROFILES</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-1 col-md-2 col-lg-3 col-xxl-3">
            <div class="card border rounded-0 h-100">
                <div class="dropdown position-absolute top-0 end-0 m-3">
                    <a href="#" class="text-dark" role="button" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fa-sharp fa-regular fa-pen-to-square"></i>
                    </a>
                </div>
                <div class="card-body text-center">
                    <div class="avatar avatar-xl flex-shrink-0 mb-3">
                        <img src="{{ asset('storage/admins/'. $user->image) }}" alt="avatar" class="avatar-img rounded-0">
                    </div>
                    <h6 class="fw-normal mb-0">{{ ucwords($user->name) }}</h6>
                    <span class="fw-normal small">Admin</span>
                </div>
                <div class="card-footer border-top rounded-0">
                    <h6 class="mb-3">Contact Details</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-md rounded-0 bg-dark text-white mb-0">
                            <i class="fa-sharp fa-solid fa-envelope"></i>
                        </div>
                        <div class="ms-2">
                            <small class="fw-normal h6 mb-0">Email</small>
                            <h6 class="fw-normal small mb-0">
                                <a href="mailto:{{ $user->email }}" class="text-secondary text-dark-hover">{{ $user->email }}</a>
                            </h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-md rounded-0 bg-dark text-white mb-0">
                            <i class="fa-sharp fa-solid fa-phone"></i>
                        </div>
                        <div class="ms-2">
                            <small class="fw-normal h6 mb-0">Phone</small>
                            <h6 class="fw-normal small mb-0">
                                <a href="tel:{{ $user->phone }}" class="text-secondary text-dark-hover">{{ chunk_split($user['phone'], 4) }}</a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5 col-md-6 col-lg-7 col-xxl-8">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <div class="row px-xxl-2">
                        <div class="col-md-6">
                            <ul class="list-group list-group-borderless">
                                <li class="list-group-item mb-3">
                                    <span class="fw-normal h6 mb-0">Full Name:</span>
                                    <span class="fw-normal ms-1 mb-0">{{ ucwords(Auth::user()->name) }}</span>
                                </li>
                                <li class="list-group-item mb-3">
                                    <span class="fw-normal h6 mb-0">Mobile Number:</span>
                                    <span class="fw-normal ms-1 mb-0">{{ chunk_split($user['phone'], 4); }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-borderless">
                                <li class="list-group-item mb-3">
                                    <span class="fw-normal h6 mb-0">Email:</span>
                                    <span class="fw-normal ms-1 mb-0">{{ Auth::user()->email }}</span>
                                </li>
                                <li class="list-group-item mb-3">
                                    <span class="fw-normal h6 mb-0">Address:</span>
                                    <span class="fw-normal ms-1 mb-0">{{ ucwords(Auth::user()->address) }}</span>
                                </li>
                                <li class="list-group-item mb-3">
                                    <span class="fw-normal h6 mb-0">Joining Date:</span>
                                    <span class="fw-normal ms-1 mb-0">{{ Auth::user()->created_at->format('d M Y') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
@endsection

@section('modal')
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border rounded-0">
            <div class="modal-header border-0">
                <span class="fw-normal text-center h5 mb-0">PROFILE SETTING</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <div class="row px-xxl-2">
                        <div class="col-12">
                            <label for="email" class="fw-medium small h6">YOUR EMAIL ADDRESS</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                            </div>
                            <p class="text-danger small">{{ $errors->first('email') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="fw-medium small h6">FULL NAME</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
                            </div>
                            <p class="text-danger small">{{ $errors->first('name') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="fw-medium small h6">PHONE</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <p class="text-danger small">{{ $errors->first('phone') }}</p>
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
                        <div class="col-12">
                            <label for="image" class="fw-medium small h6 mb-3">UPLOAD IMAGE
                                <a href="#" class="text-dark-hover h6 mb-0" role="button" id="info" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-sharp fa-solid fa-circle-info"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-w-sm dropdown-menu-start min-w-auto shadow rounded" aria-labelledby="info">
                                    <li>
                                        <div class="d-flex justify-content-between">
                                            <span class="small">Only JPG, JPEG, PNG, and WEBP.</span>
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
                                <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                            </div>
                            <p class="text-danger small">{{ $errors->first('address') }}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mb-0">
                        <button type="button" class="btn btn-secondary w-50 rounded-0 mb-0 me-3" data-bs-dismiss="modal" aria-label="Close">BACK</button>
                        <button type="submit" class="btn btn-dark next-btn w-50 rounded-0 mb-0">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
