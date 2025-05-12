@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
	<div class="container">
        @foreach($banner as $row)
            <div class="p-3 p-sm-5 rounded-0" style="background-image: url({{ asset('storage/banners/' . $row->image) }}); background-position: center center; background-repeat: no-repeat; background-size: cover;">
                <div class="row">
                    <div class="col-md-10 mx-auto text-center">
                        <h1 class="text-white display-3 pt-sm-5 my-5">{{ $row->title }}</h1>
                    </div>
                </div>
            </div>
        @endforeach
	</div>
</section>
<section class="py-0 pb-5">
	<div class="container">
		<div class="row text-center mb-4">
			<div class="col-12">
				<h4 class="mb-0">BOOKING NOW</h4>
			</div>
		</div>
        <div class="row g-4">
            @if (count($field) > 0)
                @foreach ($field as $row)
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="card bg-dark bg-opacity-10 h-100 p-4 rounded-0">
                            <div class="card-body mt-0 p-0">
                                <span class="text-body mb-1">{{ $row->title }}</span>
                                <h5 class="mb-1">IDR {{ number_format($row->price) }} <span class="smaller">/hour</span> </h5>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    @php
                                        $currentTime = now()->format('H:i');
                                        $isFull = false;
                                        $isClosed = false;
                                        $transactions = \App\Models\Transaction::where('field_id', $row->id)->get();
                                        foreach ($transactions as $transaction) {
                                            if (isset($transaction->time)) {
                                                $transactionStartTime = \Carbon\Carbon::createFromFormat('H:i', $transaction->time)->format('H:i');
                                                $transactionEndTime = \Carbon\Carbon::createFromFormat('H:i', $transaction->time)->addHours($transaction->hour)->format('H:i');

                                                if ($transactionEndTime >= '23:00') {
                                                    $isFull = true;
                                                    break;
                                                }
                                            }
                                        }
                                        if ($currentTime >= '23:00') {
                                            $isClosed = true;
                                        } elseif ($currentTime == '00:00') {
                                            $isClosed = false;
                                        }
                                    @endphp
                                    <div class="bg-dark bg-opacity-10 border border-2 border-dark border-dashed rounded-0 px-3 py-2">
                                        @if ($isFull)
                                            <h5 class="fw-normal user-select-all mb-0">Full</h5>
                                        @elseif ($isClosed)
                                            <h5 class="fw-normal user-select-all mb-0">Closed</h5>
                                        @else
                                            <h5 class="fw-normal user-select-all mb-0">Available</h5>
                                        @endif
                                    </div>
                                    <a href="{{ route('front.bookingForm', $row->slug) }}" class="btn btn-lg btn-dark btn-round btn-light-hover mb-0" title="Details">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card bg-dark bg-opacity-10 h-100 p-4 rounded-0">
                        <div class="card-body text-center mt-0 p-0">
                            <h5 class="mb-1">You have no data in this table</h5>
                        </div>
                    </div>
                </div>
            @endif
        </div>
	</div>
</section>
<section class="py-0 pb-5">
	<div class="container">
        <div class="row text-center mb-4">
			<div class="col-12">
				<h4 class="mb-0">OUR LOCATION</h4>
			</div>
		</div>
		<div class="row g-4">
			<div class="col-md-8">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878428698!3d40.74076684379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sin!4v1586000412513!5m2!1sen!2sin" class="w-100 h-300px grayscale rounded-0" height="500" style="border:0;" aria-hidden="false" tabindex="0"></iframe>
			</div>
            <div class="col-lg-4">
                <div class="card bg-dark bg-opacity-10 h-100 p-4 rounded-0">
					<h5 class="mb-3">New York, USA (HQ)</h5>
					<address class="mb-1">750 Sing Sing Rd, Horseheads, NY, 14845</address>
					<p class="mb-1">Call: 469-537-2410 (Toll-free)</p>
					<p>Support time: Monday to Saturday
						<br>
						9:00 am to 5:30 pm
					</p>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
