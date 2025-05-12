@extends('backend.main.layouts.app')
@section('title', 'Booking')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">TRANSACTION CANCEL</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <div class="row px-xxl-2">
                        <h5 class="fw-normal">Transaction</h5> <hr>
                        <div class="col-md-6">
                            <div class="d-grid mb-4">
                                <span class="fw-light h6 mb-1">Invoice ID:</span>
                                <span class="fw-light mb-0">{{ $transaction->invoice }}</span>
                            </div>
                            <div class="d-grid mb-4">
                                <span class="fw-light h6 mb-1">Customer Information:</span>
                                <span class="fw-light mb-0">{{ $transaction->customer->name }}</span>
                                <span class="fw-light mb-0">{{ chunk_split($transaction->customer['phone'], 4); }}</span>
                            </div>
                            <div class="d-grid">
                                <a href="{{ asset('storage/proofs/'. $transaction->detail->proof) }}" target="_blank" class="btn btn-dark w-50 rounded-0 me-1">View</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid mb-4">
                                <span class="fw-light h6 mb-1">Information Field:</span>
                                <span class="fw-light mb-0">{{ ucwords($transaction->field->title) }} on {{ $transaction->date }} from {{ \Carbon\Carbon::createFromFormat('H:i', $transaction->time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i', $transaction->time)->addHours($transaction->hour)->format('H:i') }} ({{ $transaction->hour }} Hour).</span>
                                <span class="fw-light mb-0">IDR {{ number_format($transaction->price) }}</span>
                            </div>
                            <div class="d-grid mb-4">
                                <span class="fw-light h6 mb-1">Payment:</span>
                                <span class="fw-light mb-0">{{ Str::upper($transaction->detail->bank_name) }}</span>
                                <span class="fw-light mb-0">{{ Str::upper($transaction->detail->account_name) }}</span>
                                <span class="fw-light mb-0">{{ $transaction->detail->account_number }}</span>
                            </div>
                            <div class="d-grid">
                                <span class="fw-light h6 mb-1">Amount:</span>
                                <span class="fw-light mb-0">IDR {{ number_format($transaction->detail->amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('transaction.cancelStore', $transaction->invoice) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row px-xxl-2">
                            <div class="col-12">
                                <label class="fw-medium small h6">CUSTOMER NAME</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" class="form-control" value="{{ old('name', ucwords($transaction->customer->name)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-medium small h6">BANK NAME</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" class="form-control" value="{{ old('bank_name', Str::upper($transaction->cancel->bank_name)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-medium small h6">ACCOUNT NAME</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" class="form-control" value="{{ old('account_name', Str::upper($transaction->cancel->account_name)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-medium small h6">ACCOUNT NUMBER</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" class="form-control" value="{{ old('account_number', ucwords($transaction->cancel->account_number)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="fw-medium small h6">AMOUNT</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" class="form-control" value="{{ old('amount', ucwords($transaction->cancel->amount)) }}" readonly>
                                </div>
                                <p class="text-danger small">{{ $errors->first('amount') }}</p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="fw-medium small h6">REASON</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <textarea class="form-control" rows="3"readonly>{{ old('reason', ucwords($transaction->cancel->reason)) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-0">
                            <a href="{{ route('transaction.index') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-3">BACK</a>
                            <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">ACCEPT</button>
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
