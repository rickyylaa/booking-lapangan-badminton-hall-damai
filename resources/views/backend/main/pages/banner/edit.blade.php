@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-other-banner', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">BANNERS</span>
                <button type="button" class="btn btn-dark w-100px rounded-0" data-bs-toggle="modal" data-bs-target="#bannerModal">VIEW</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('banner.update', $banner->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row px-xxl-2">
                            <div class="col-md-6">
                                <label for="title" class="fw-medium small h6">TITLE</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
                                </div>
                                <p class="text-danger small">{{ $errors->first('title') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="fw-medium small h6">STATUS</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1" {{ old('status', $banner->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $banner->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('status') }}</p>
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
                                <label for="description" class="fw-medium small h6">DESCRIPTION</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $banner->description) }}</textarea>
                                </div>
                                <p class="text-danger small">{{ $errors->first('description') }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-0">
                            <a href="{{ route('banner.index') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-3">BACK</a>
                            <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">SAVE</button>
                        </div>
                    </form>
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
<div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border rounded-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row px-xxl-2">
                    <div class="col-12">
                        <div class="card bg-transparent text-center p-1 w-100">
                            <img src="{{ asset('storage/banners/'. $banner->image) }}" alt="banner" class="rounded-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
