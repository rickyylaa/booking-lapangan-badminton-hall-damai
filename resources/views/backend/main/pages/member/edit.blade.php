@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-menu-member', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">MEMBERS</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('member.update', $member->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row px-xxl-2">
                            <div class="col-md-4">
                                <label for="customer_id" class="fw-medium small h6">CUSTOMER</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="customer_id" id="customer_id" class="form-control" required>
                                        <option value="">Please choose one</option>
                                        @foreach ($customer as $row)
                                            <option value="{{ $row->id }}" {{ (($member->customer_id == $row->id) ? 'selected' : '') }}>{{ ucwords($row->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('customer_id') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label for="field_id" class="fw-medium small h6">FIELD</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="field_id" id="field_id" class="form-control" required>
                                        <option value="">Please choose one</option>
                                        @foreach ($field as $row)
                                            <option value="{{ $row->id }}" {{ (($member->field_id == $row->id) ? 'selected' : '') }}>{{ ucwords($row->title) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('field_id') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label for="bank_name" class="fw-medium small h6">BANK NAME</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="bank_name" id="bank_name" class="form-control" required>
                                        <option value="bca" {{ old('bank_name', $member->transaction->bank_name) == 'bca' ? 'selected' : '' }}>BCA</option>
                                        <option value="bni" {{ old('bank_name', $member->transaction->bank_name) == 'bni' ? 'selected' : '' }}>BNI</option>
                                        <option value="bri" {{ old('bank_name', $member->transaction->bank_name) == 'bri' ? 'selected' : '' }}>BRI</option>
                                        <option value="dana" {{ old('bank_name', $member->transaction->bank_name) == 'dana' ? 'selected' : '' }}>DANA</option>
                                        <option value="gopay" {{ old('bank_name', $member->transaction->bank_name) == 'gopay' ? 'selected' : '' }}>GOPAY</option>
                                        <option value="cash" {{ old('bank_name', $member->transaction->bank_name) == 'cash' ? 'selected' : '' }}>CASH</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('bank_name') }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="day" class="fw-medium small h6">DAY</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="day" id="day" class="form-control" required>
                                        <option value="Sunday" {{ old('day', $member->day) == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                        <option value="Monday" {{ old('day', $member->day) == 'Monday' ? 'selected' : '' }}>Monday</option>
                                        <option value="Tuesday" {{ old('day', $member->day) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="Wednesday" {{ old('day', $member->day) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                        <option value="Thursday" {{ old('day', $member->day) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                        <option value="Friday" {{ old('day', $member->day) == 'Friday' ? 'selected' : '' }}>Friday</option>
                                        <option value="Saturday" {{ old('day', $member->day) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('day') }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="time_start" class="fw-medium small h6">TIME START</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="time_start" id="time_start" class="form-control" required>
                                        <option value="15:00" {{ old('time_start', $member->time_start) == '15:00' ? 'selected' : '' }}>15:00</option>
                                        <option value="16:00" {{ old('time_start', $member->time_start) == '16:00' ? 'selected' : '' }}>16:00</option>
                                        <option value="17:00" {{ old('time_start', $member->time_start) == '17:00' ? 'selected' : '' }}>17:00</option>
                                        <option value="18:00" {{ old('time_start', $member->time_start) == '18:00' ? 'selected' : '' }}>18:00</option>
                                        <option value="19:00" {{ old('time_start', $member->time_start) == '19:00' ? 'selected' : '' }}>19:00</option>
                                        <option value="20:00" {{ old('time_start', $member->time_start) == '20:00' ? 'selected' : '' }}>20:00</option>
                                        <option value="21:00" {{ old('time_start', $member->time_start) == '21:00' ? 'selected' : '' }}>21:00</option>
                                        <option value="22:00" {{ old('time_start', $member->time_start) == '22:00' ? 'selected' : '' }}>22:00</option>
                                        <option value="23:00" {{ old('time_start', $member->time_start) == '23:00' ? 'selected' : '' }}>23:00</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('time_start') }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="time_end" class="fw-medium small h6">TIME END</label>
                                <div class="form-border-bottom form-control-transparent">
                                    <select name="time_end" id="time_end" class="form-control" required>
                                        <option value="15:00" {{ old('time_end', $member->time_end) == '15:00' ? 'selected' : '' }}>15:00</option>
                                        <option value="16:00" {{ old('time_end', $member->time_end) == '16:00' ? 'selected' : '' }}>16:00</option>
                                        <option value="17:00" {{ old('time_end', $member->time_end) == '17:00' ? 'selected' : '' }}>17:00</option>
                                        <option value="18:00" {{ old('time_end', $member->time_end) == '18:00' ? 'selected' : '' }}>18:00</option>
                                        <option value="19:00" {{ old('time_end', $member->time_end) == '19:00' ? 'selected' : '' }}>19:00</option>
                                        <option value="20:00" {{ old('time_end', $member->time_end) == '20:00' ? 'selected' : '' }}>20:00</option>
                                        <option value="21:00" {{ old('time_end', $member->time_end) == '21:00' ? 'selected' : '' }}>21:00</option>
                                        <option value="22:00" {{ old('time_end', $member->time_end) == '22:00' ? 'selected' : '' }}>22:00</option>
                                        <option value="23:00" {{ old('time_end', $member->time_end) == '23:00' ? 'selected' : '' }}>23:00</option>
                                    </select>
                                </div>
                                <p class="text-danger small">{{ $errors->first('time_end') }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="hour" class="small fw-medium h6">PER HOUR</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="hour" id="hour" class="form-control" value="{{ old('hour', $member->hour) }}" readonly>
                                </div>
                                <p class="text-danger small">{{ $errors->first('hour') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="price" class="small fw-medium h6">PRICE</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $member->price) }}" readonly>
                                </div>
                                <p class="text-danger small">{{ $errors->first('price') }}</p>
                            </div>
                            <div class="col-12">
                                <label for="amount" class="small fw-medium h6">AMOUNT</label>
                                <div class="form-border-bottom form-control-transparent h6">
                                    <input type="text" name="amount" id="amount" class="form-control" value="{{ old('amount', $member->amount) }}">
                                </div>
                                <p class="text-danger small">{{ $errors->first('amount') }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-0">
                            <a href="{{ route('member.index') }}" class="btn btn-secondary w-50 rounded-0 mb-0 me-3">BACK</a>
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

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var daySelect = document.getElementById('day');
        var timeSelect = document.getElementById('time');
        var hourSelect = document.getElementById('hour');
        var priceInput = document.getElementById('price');
        var fieldSelect = document.getElementById('field_id');
        var memberData = JSON.parse(document.getElementById('memberData').value);

        function updateDisabledTimeOptions() {
            var selectedDay = daySelect.value;
            var selectedField = fieldSelect.value;
            var selectedHour = parseInt(hourSelect.value);

            Array.from(timeSelect.options).forEach(function(option) {
                option.disabled = false;
            });

            var membersForSelectedDayAndField = memberData.filter(function(member) {
                return (member.day === selectedDay && member.field_id == selectedField);
            });

            membersForSelectedDayAndField.forEach(function(member) {
                var memberStartTime = member.time;
                var memberDuration = parseInt(member.hour);

                if (memberStartTime) {
                    var [memberHour, memberMinute] = memberStartTime.split(':').map(Number);

                    var memberTimeInMinutes = memberHour * 60 + memberMinute;
                    var memberEndTimeInMinutes = memberTimeInMinutes + memberDuration * 60;

                    Array.from(timeSelect.options).forEach(function(option) {
                        var [optionHour, optionMinute] = option.value.split(':').map(Number);
                        var optionTimeInMinutes = optionHour * 60 + optionMinute;

                        if ((selectedHour === 1 || optionHour === 20 || optionHour === 21) && optionTimeInMinutes >= memberTimeInMinutes && optionTimeInMinutes < memberEndTimeInMinutes) {
                            option.disabled = true;
                        }

                        if (memberEndTimeInMinutes === 23 * 60 && optionTimeInMinutes === 23 * 60) {
                            option.disabled = true;
                        }
                    });
                }
            });
        }

        function updatePrice() {
            var selectedHour = parseInt(hourSelect.value);
            var fieldPrice = 35000;
            var meetingCount = 4;
            var totalPrice = selectedHour * fieldPrice * meetingCount;
            priceInput.value = totalPrice;
        }

        fieldSelect.addEventListener('change', function() {
            updateDisabledTimeOptions();
            updatePrice();
        });

        daySelect.addEventListener('change', function() {
            updateDisabledTimeOptions();
            updatePrice();
        });

        updateDisabledTimeOptions();

        hourSelect.addEventListener('change', function() {
            updateDisabledTimeOptions();
            updatePrice();
        });

        timeSelect.addEventListener('change', function() {
            updatePrice();
        });
    });
</script>
@endsection
