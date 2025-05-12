@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
    <div class="container">
        <div class="row mb-5">
			<div class="col-xl-10 mb-3">
				<h1>Information Booking</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
		</div>
        <div class="row g-4 g-lg-5">
            <div class="col-12">
                <div class="card bg-dark bg-opacity-10 rounded-0 p-4">
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('customer.confirmStore', $transactionData->invoice) }}" enctype="multipart/form-data">
                            @csrf @method('POST') <input type="hidden" id="transactionData" value="{{ json_encode($transactionData) }}">
                            <div class="row">
                                <div class="d-grid justify-content-center align-items-center mb-4">
                                    <span class="fw-normal text-center h5 mb-0">YOUR BOOKING</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" class="form-control" value="{{ $transactionData->field->title }}" readonly>
                                    </div>
                                </div> <input type="hidden" name="field_id" value="{{ $field['id'] }}">
                                <div class="col-md-4">
                                    <div class="form-border-bottom form-control-transparent h6">
                                        @php
                                            $startTime = \Carbon\Carbon::createFromFormat('H:i', $transactionData->time);
                                            $endTime = $startTime->copy()->addHours($transactionData->hour);
                                        @endphp
                                        <input type="text" class="form-control" value="{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}" readonly>
                                    </div>
                                </div> <input type="hidden" name="customer_id" value="{{ $customer['id'] }}">
                                <div class="col-md-4">
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" class="form-control" value="{{ $transactionData->hour }} Hour" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="fw-medium small h6">PRICE</label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" id="price" class="form-control" value="IDR {{ number_format($transactionData->price) }}" readonly>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="priceText" class="fw-medium small h6"></label>
                                    <div class="form-border-bottom form-control-transparent h6">
                                        <input type="text" class="form-control" value="{{ $priceText }}" readonly>
                                    </div>
                                    <p class="text-danger small">{{ $errors->first('priceText') }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-secondary w-50 rounded-0 mb-0 me-2">CANCEL</button>
                                <a href="{{ route('customer.paymentForm', $transactionData->invoice) }}" class="btn btn-dark w-50 rounded-0 mb-0">CONTINUE</a>
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
