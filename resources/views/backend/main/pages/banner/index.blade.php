@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-other-banner', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">BANNERS</span>
                <button type="button" class="btn btn-dark w-100px rounded-0" data-bs-toggle="modal" data-bs-target="#bannerModal">CREATE</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">BANNER</th>
                                    <th scope="col" class="border-0">TITLE</th>
                                    <th scope="col" class="border-0">DESCRIPTION</th>
                                    <th scope="col" class="border-0">STATUS</th>
                                    <th scope="col" class="border-0">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($banner) > 0)
                                    @foreach ($banner as $row)
                                        <tr>
                                            <td> </td>
                                            <td> <span class="fw-normal h6">{{ $row->id }}</span> </td>
                                            <td>
                                                <div class="card card-element-hover card-overlay-hover overflow-hidden avatar-xl rounded-0">
                                                    <img src="{{ asset('storage/banners/'. $row->image) }}" alt="avatar" class="avatar-img bg-light shadow rounded-0">
                                                    <a href="{{ asset('storage/banners/'. $row->image) }}" class="hover-element position-absolute w-100 h-100" data-glightbox data-gallery="gallery">
                                                        <i class="bi bi-fullscreen fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-0 p-2 lh-1"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td> <span class="fw-normal h6">{{ $row->title }}</span> </td>
                                            <td> <span class="fw-normal h6">{{ $row->description }}</span> </td>
                                            <td> {!! $row->status_label !!} </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        <form method="POST" action="{{ route('banner.destroy', $row->id) }}">
                                                            @csrf @method('DELETE')
                                                            <li class="mb-1">
                                                                <a href="{{ route('banner.edit', $row->id) }}" class="dropdown-item">
                                                                    <i class="fa-sharp fa-regular fa-pen-to-square me-2"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <button type="submit" class="dropdown-item bg-danger-soft-hover">
                                                                    <i class="fa-sharp fa-regular fa-trash me-2"></i>Delete
                                                                </button>
                                                            </li>
                                                        </form>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            <div class="col-12">
                                                <div class="text-center mt-4">
                                                    <h6 class="fw-lighter text-secondary small mb-2">You have no data in this table</h6>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (count($banner) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">SHOWING {{ $banner->firstItem() }} TO {{ $banner->lastItem() }} OF {{ $banner->total() }} ENTRIES</span>
                            {!! $banner->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/css/glightbox.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
@endsection

@section('modal')
<div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border rounded-0">
            <div class="modal-header border-0">
                <span class="fw-normal text-center h5 mb-0">CREATE BANNER</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('banner.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row px-xxl-2">
                        <div class="col-md-6">
                            <label for="title" class="fw-medium small h6">TITLE</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <p class="text-danger small">{{ $errors->first('title') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="fw-medium small h6">STATUS</label>
                            <div class="form-border-bottom form-control-transparent">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
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
                                <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                            </div>
                            <p class="text-danger small">{{ $errors->first('description') }}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mb-0">
                        <button type="button" class="btn btn-secondary w-50 rounded-0 mb-0 me-3" data-bs-dismiss="modal" aria-label="Close">BACK</button>
                        <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

