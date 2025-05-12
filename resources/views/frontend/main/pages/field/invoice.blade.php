@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
	<div class="container">
		<div class="row g-4">
			<div class="col-xl-6">
                <div class="card rounded-0">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="small fw-medium h6 mb-0">INVOICE</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid mb-4">
                            <span class="fw-light h6 mb-1">Invoice ID:</span>
                            <span class="fw-light mb-0">{{ $transactionData->invoice }}</span>
                        </div>
                        <div class="d-grid">
                            <span class="fw-light h6 mb-1">Your information:</span>
                            <span class="fw-light mb-0">{{ ucwords($transactionData->customer->name) }}</span>
                            <span class="fw-light mb-0">{{ chunk_split($transactionData->customer['phone'], 4); }}</span>
                            <span class="fw-light mb-0">{{ ucwords($transactionData->customer->address) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card rounded-0">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="small fw-medium h6 mb-0">ORDER INFORMATION</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($transactionData->time == NULL)
                            <div class="d-grid mb-4">
                                @php
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $transactionData->detail->time);
                                    $endTime = $startTime->copy()->addHours($transactionData->detail->hour);
                                @endphp
                                <span class="fw-light h6 mb-1">Your booking:</span>
                                <span class="fw-light mb-0">{{ ucwords($transactionData->field->title) }} on {{ $transactionData->date }} from {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }} ({{ $transactionData->hour }} Hour).</span>
                            </div>
                        @else
                            <div class="d-grid mb-4">
                                @php
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $transactionData->time);
                                    $endTime = $startTime->copy()->addHours($transactionData->hour);
                                @endphp
                                <span class="fw-light h6 mb-1">Your booking:</span>
                                <span class="fw-light mb-0">{{ ucwords($transactionData->field->title) }} on {{ $transactionData->date }} from {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }} ({{ $transactionData->hour }} Hour).</span>
                            </div>
                        @endif
                        <div class="d-grid mb-4">
                            <span class="fw-light h6 mb-1">Payment:</span>
                            <span class="fw-light mb-0">{{ Str::upper($transactionData->detail->account_name) }}</span>
                            <span class="fw-light mb-0">{{ Str::upper($transactionData->detail->bank_name) }}</span>
                        </div>
                        <div class="d-grid">
                            <span class="fw-light h6 mb-1">Amount:</span>
                            <span class="fw-light mb-0">IDR {{ number_format($transactionData->detail->amount) }} - {{ $priceText }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-end align-items-center">
                    <a href="{{ route('front.index') }}" class="btn btn-secondary rounded-0 me-1">BACK</a>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-dark rounded-0">DASHBOARD</a>
                    {{-- <a href="{{ route('customer.bookingPDF', $transactionData->invoice) }}" target="_blank" class="btn btn-dark rounded-0">DOWNLOAD</a> --}}
                </div>
            </div>
		</div>
	</div>
</section>
@endsection
