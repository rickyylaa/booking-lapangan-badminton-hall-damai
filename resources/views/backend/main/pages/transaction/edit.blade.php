@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-misc-transaction', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">TRANSACTIONS</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('transaction.update', $transaction->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT') <input type="hidden" id="transactionData" value="{{ json_encode($transactionData) }}">
                        <div class="row px-xxl-2">
                            <div class="col-md-6">
                                <label for="customer_id" class="fw-medium small h6">CUSTOMER</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="customer_id" id="customer_id" class="form-control" required>
                                        <option value="">Please choose one</option>
                                        @foreach ($customer as $row)
                                            <option value="{{ $row->id }}" {{ (($transaction->customer_id == $row->id) ? 'selected' : '') }}>{{ ucwords($row->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('customer_id') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="field_id" class="fw-medium small h6">FIELD</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="field_id" id="field_id" class="form-control" required>
                                        <option value="">Please choose one</option>
                                        @foreach ($field as $row)
                                            <option value="{{ $row->id }}" {{ (($transaction->field_id == $row->id) ? 'selected' : '') }}>{{ ucwords($row->title) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('field_id') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="fw-medium small h6">DATE</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="date" id="date" class="form-control flatpickr" data-date-format="d F Y" placeholder="Select pickup date" value="{{ old('date', $transaction->date) }}" required>
                                </div>
                                <p class="text-danger small">{{ $errors->first('date') }}</p>
                            </div> <input type="hidden" name="original_time" value="{{ $transaction->time }}">
                            <div class="col-md-4">
                                <label for="time" class="fw-medium small h6">TIME</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="time" id="time" class="form-control">
                                        <option value="16:00" {{ old('time', $transaction->time) == '16:00' ? 'selected' : '' }}>16:00</option>
                                        <option value="17:00" {{ old('time', $transaction->time) == '17:00' ? 'selected' : '' }}>17:00</option>
                                        <option value="18:00" {{ old('time', $transaction->time) == '18:00' ? 'selected' : '' }}>18:00</option>
                                        <option value="19:00" {{ old('time', $transaction->time) == '19:00' ? 'selected' : '' }}>19:00</option>
                                        <option value="20:00" {{ old('time', $transaction->time) == '20:00' ? 'selected' : '' }}>20:00</option>
                                        <option value="21:00" {{ old('time', $transaction->time) == '21:00' ? 'selected' : '' }}>21:00</option>
                                        <option value="22:00" {{ old('time', $transaction->time) == '22:00' ? 'selected' : '' }}>22:00</option>
                                        <option value="23:00" {{ old('time', $transaction->time) == '23:00' ? 'selected' : '' }}>23:00</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('time') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label for="hour" class="fw-medium small h6">HOUR</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="hour" id="hour" class="form-control">
                                        <option value="1" {{ old('hour', $transaction->hour) == '1' ? 'selected' : '' }}>1 Hour</option>
                                        <option value="2" {{ old('hour', $transaction->hour) == '2' ? 'selected' : '' }}>2 Hour</option>
                                        <option value="3" {{ old('hour', $transaction->hour) == '3' ? 'selected' : '' }}>3 Hour</option>
                                        <option value="4" {{ old('hour', $transaction->hour) == '4' ? 'selected' : '' }}>4 Hour</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('hour') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="price" class="small fw-medium h6">PRICE</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $transaction->price) }}" readonly>
                                </div>
                                <p class="text-danger small">{{ $errors->first('price') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="amount" class="small fw-medium h6">AMOUNT</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="amount" id="amount" class="form-control" value="{{ old('amount', $transaction->detail->amount) }}">
                                </div>
                                <p class="text-danger small">{{ $errors->first('amount') }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-0">
                            <a href="{{ route('transaction.index') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-3">BACK</a>
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
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/css/flatpickr.min.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/js/flatpickr.min.js') }}"></script>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dateSelect = document.getElementById('date');
        var timeSelect = document.getElementById('time');
        var hourSelect = document.getElementById('hour');
        var priceInput = document.getElementById('price');
        var fieldSelect = document.getElementById('field_id');
        var transactionData = JSON.parse(document.getElementById('transactionData').value);

        function formatDate(date) {
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();

            return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
        }

        function updateDisabledTimeOptions() {
            var selectedDate = dateSelect.value;
            var selectedField = fieldSelect.value;
            var timeOptions = Array.from(timeSelect.options);
            var currentTime = new Date();
            var currentDate = new Date(selectedDate);
            var currentHour = currentDate.getHours();
            var currentDayIndex = currentDate.getDay();
            var currentMinute = currentDate.getMinutes();
            var currentTimeInMinutes = currentHour * 60 + currentMinute;

            var dayNames = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
            var currentDay = dayNames[currentDayIndex];

            timeOptions.forEach(option => {
                option.disabled = false;

                var [optionHour, optionMinute] = option.value.split(':').map(Number);
                var optionTime = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), optionHour, optionMinute);
                var optionTimeInMinutes = optionTime.getHours() * 60 + optionTime.getMinutes();

                if (optionTime < currentTime) {
                    option.disabled = true;
                }
            });

            var transactionsForSelectedDateAndField = transactionData.filter(function(transaction) {
                return (transaction.date === selectedDate && transaction.field_id == selectedField) || (transaction.day === currentDay && transaction.field_id == selectedField);
            });

            transactionsForSelectedDateAndField.forEach(function(transaction) {
                var transactionStartTime = transaction.time;
                var transactionDuration = parseInt(transaction.hour);

                if (transactionStartTime) {
                    var [transactionHour, transactionMinute] = transactionStartTime.split(':').map(Number);
                    if (!isNaN(transactionHour) && !isNaN(transactionMinute)) {
                        var transactionTimeInMinutes = transactionHour * 60 + transactionMinute;
                        var transactionEndTimeInMinutes = transactionTimeInMinutes + transactionDuration * 60;

                        timeOptions.forEach(function(option) {
                            var optionTime = option.value;
                            var [optionHour, optionMinute] = optionTime.split(':').map(Number);
                            var optionTimeInMinutes = optionHour * 60 + optionMinute;

                            if (optionTimeInMinutes >= transactionTimeInMinutes && optionTimeInMinutes < transactionEndTimeInMinutes) {
                                option.disabled = true;
                            }

                            if (transactionEndTimeInMinutes === 23 * 60 && optionTimeInMinutes === 23 * 60) {
                                option.disabled = true;
                            }
                        });
                    }
                }
            });

            if (selectedDate === formatDate(currentTime)) {
                timeOptions.forEach(option => {
                    var [optionHour, optionMinute] = option.value.split(':').map(Number);
                    var optionTimeInMinutes = optionHour * 60 + optionMinute;
                    if (currentTimeInMinutes >= optionTimeInMinutes) {
                        option.disabled = true;
                    }
                });
            }
        }

        function updatePrice() {
            var selectedHour = parseInt(hourSelect.value);
            var fieldPrice = 35000;
            var totalPrice = selectedHour * fieldPrice;
            priceInput.value = totalPrice;
        }

        hourSelect.addEventListener('change', function() {
            updateDisabledTimeOptions();
            updatePrice();
        });

        fieldSelect.addEventListener('change', function() {
            updateDisabledTimeOptions();
            updatePrice();
        });

        dateSelect.addEventListener('change', updateDisabledTimeOptions);
        timeSelect.addEventListener('change', updateDisabledTimeOptions);

        flatpickr('#date', {
            dateFormat: 'd F Y',
            defaultDate: 'today',
            minDate: 'today',
        });

        updatePrice();
        updateDisabledTimeOptions();
    });
</script>
@endsection
