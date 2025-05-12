@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
    <div class="container">
        <div class="row mb-5">
			<div class="col-xl-10 mb-3">
				<h1>Dashboard</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
		</div>
        <div class="row g-4 g-lg-5">
            <div class="col-md-6">
                <div class="card rounded-0 p-4">
                    <div class="card-body p-0">
                        <h5 class="fw-normal">Your Information</h5> <hr>
                        <div class="d-grid justify-content-start align-items-center mb-3">
                            <span class="fw-normal small h6 mb-0">NAME:</span>
                            <span class="fw-normal small">{{ ucwords($customer->name) }}<span>
                        </div>
                        <div class="d-grid justify-content-start align-items-center mb-3">
                            <span class="fw-normal small h6 mb-0">CONTACT:</span>
                            <span class="fw-normal small mb-0">{{ $customer->email }}</span>
                            <span class="fw-normal small">{{ chunk_split($customer['phone'], 4); }}</span>
                        </div>
                        <div class="d-grid justify-content-start align-items-center mb-1">
                            <span class="fw-normal small h6 mb-0">ADDRESS:</span>
                            <span class="fw-normal small mb-0">{{ ucwords($customer->address) }}</span>
                        </div>
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="fw-normal small">
                                <a href="{{ route('customer.setting') }}" class="fw-normal smaller h6 text-decoration-underline">SETTINGS</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card rounded-0 p-4">
                    <div class="card-body p-0 px-7">
                        <h5 class="fw-normal">Your Booked</h5> <hr>
                        @if (count($transactionData) > 0)
                            <div class="scrollbar scrollbar-secondary">
                                @foreach ($transactionData as $row)
                                    @if ($row->time == NULL)
                                        <div class="d-flex justify-content-start align-items-center mb-3">
                                            @php
                                                $iconClass = '';
                                                switch ($row->status) {
                                                    case 0:
                                                    case 1:
                                                    case 2:
                                                        $iconClass = 'fa-circle-info text-info';
                                                        break;
                                                    case 3:
                                                        $iconClass = 'fa-circle-check text-success';
                                                        break;
                                                    case 5:
                                                        $iconClass = 'fa-circle-xmark text-danger';
                                                        break;
                                                    case 6:
                                                        $iconClass = 'fa-circle-check text-primary';
                                                        break;
                                                    default:
                                                        $iconClass = 'fa-circle-info text-info';
                                                }
                                            @endphp
                                            <i class="fa-sharp fa-regular {{ $iconClass }} fs-5 me-3"></i>
                                            @if ($row->day)
                                                <span class="fw-normal small h6 mb-0">
                                                    You have booked <b>{{ ucwords($row->field->title) }}</b> on <b>{{ ucwords($row->detail->day) }}</b> for 4 weeks from <b>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->detail->time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->detail->time)->addHours($row->detail->hour)->format('H:i') }}</b>.
                                                </span>
                                            @else
                                            <span class="fw-normal small h6 mb-0">
                                                You have booked <b>{{ ucwords($row->field->title) }}</b> on <b>{{ $row->detail->date }}</b> from <b>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->detail->time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->detail->time)->addHours($row->detail->hour)->format('H:i') }}</b>.
                                            </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-start align-items-center mb-0">
                                            @php
                                                $iconClass = '';
                                                switch ($row->status) {
                                                    case 0:
                                                    case 1:
                                                    case 2:
                                                        $iconClass = 'fa-circle-info text-info';
                                                        break;
                                                    case 3:
                                                        $iconClass = 'fa-circle-check text-success';
                                                        break;
                                                    case 5:
                                                        $iconClass = 'fa-circle-xmark text-danger';
                                                        break;
                                                    case 6:
                                                        $iconClass = 'fa-circle-info text-primary opacity-8';
                                                        break;
                                                    default:
                                                        $iconClass = 'fa-circle-info text-info';
                                                }
                                            @endphp
                                            <i class="fa-sharp fa-regular {{ $iconClass }} fs-5 me-3"></i>
                                            @if ($row->day)
                                                <span class="fw-normal small h6 mb-0">
                                                    You have booked <b>{{ ucwords($row->field->title) }}</b> on <b>{{ ucwords($row->day) }}</b> for 4 weeks from <b>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->addHours($row->hour)->format('H:i') }}</b>.
                                                </span>
                                            @else
                                                <span class="fw-normal small h6 mb-0">
                                                    You have booked <b>{{ ucwords($row->field->title) }}</b> on <b>{{ $row->date }}</b> from <b>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->addHours($row->hour)->format('H:i') }}</b>.
                                                </span>
                                            @endif
                                        </div>
                                        @if (!($row->cancel && in_array($row->cancel->status, [0, 1])) && !$row->day)
                                            @php
                                                $createdAgo = $row->created_at->diffInMinutes(now());
                                            @endphp
                                            @if ($createdAgo < 60)
                                                <div class="d-flex justify-content-end align-items-center mb-0">
                                                    <a href="{{ route('customer.cancelForm', $row->invoice) }}" class="fw-normal smaller h6 text-secondary-hover text-decoration-underline mb-0">CANCEL</a>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center mt-4">
                                <h6 class="fw-lighter text-secondary small mb-2">You have no data in this table</h6>
                            </div>
                        @endif
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
