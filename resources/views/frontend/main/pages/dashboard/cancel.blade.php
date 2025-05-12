@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
    <div class="container">
        <div class="row mb-5">
			<div class="col-xl-10 mb-3">
				<h1>Transaction Cancel</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
		</div>
        <div class="row g-4 g-lg-5">
            <div class="col-12">
                <div class="card bg-dark bg-opacity-10 rounded-0 p-4">
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('customer.cancelStore', $transactionData->invoice) }}" enctype="multipart/form-data">
                            @csrf <input type="hidden" name="transaction_id" value="{{ $transactionData['id'] }}" class="form-control">
                            @if ($transactionData->status == 0)
                                <div class="row">
                                    <div class="d-grid justify-content-center align-items-center mb-4">
                                        <span class="fw-normal text-center h5 mb-0">CANCEL FORM</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="bank_name" class="fw-medium small h6">BANK NAME</label>
                                        <div class="form-border-bottom form-control-transparent">
                                            <select name="bank_name" id="bank_name" class="form-control" required>
                                                <option value="bca">BCA</option>
                                                <option value="bni">BNI</option>
                                                <option value="bri">BRI</option>
                                                <option value="dana">DANA</option>
                                                <option value="gopay">GOPAY</option>
                                            </select>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('bank_name') }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="account_name" class="fw-medium small h6">BANK ACCOUNT NAME</label>
                                        <div class="form-border-bottom form-control-transparent h6">
                                            <input type="text" name="account_name" id="account_name" class="form-control" value="{{ old('account_name', ucwords($customer->name)) }}" required>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('account_name') }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="account_number" class="fw-medium small h6">BANK ACCOUNT NUMBER</label>
                                        <div class="form-border-bottom form-control-transparent h6">
                                            <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number') }}" required>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('account_number') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label for="amount" class="fw-medium small h6">AMOUNT</label>
                                        <div class="form-border-bottom form-control-transparent h6">
                                            <input type="text" name="amount" id="amount" class="form-control" value="0">
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('amount') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label for="proof" class="fw-medium small h6 mb-3">UPLOAD PROOF
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
                                                    <input type="file" name="proof" id="proof" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('proof') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label for="reason" class="fw-medium small h6">REASON</label>
                                        <div class="form-border-bottom form-control-transparent h6">
                                            <textarea name="reason" id="reason" class="form-control" rows="5" required>{{ old('reason') }}</textarea>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('reason') }}</p>
                                    </div>
                                </div>
                            @endif
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
