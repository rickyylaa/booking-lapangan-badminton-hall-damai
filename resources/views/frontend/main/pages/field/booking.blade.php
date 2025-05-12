@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
    <div class="container">
        <div class="row g-4 mb-5">
			<div class="col-xl-10 mb-3">
				<h1>{{ $field->title }}</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
            <div class="col-12">
                <div class="h-sm-400px rounded-0 mb-0" style="background-image: url({{ asset('storage/fields/' . $field->image) }}); background-position: center center; background-repeat: no-repeat; background-size: cover;"></div>
            </div>
		</div>
        <div class="row g-4 mb-5">
            <div class="col-12">
				<div class="card card-body bg-dark bg-opacity-10 text-center align-items-center rounded-0 h-100">
					<div class="icon-lg bg-dark bg-opacity-10 text-dark rounded-circle mb-2">
                        <i class="fa-sharp fa-regular fa-calendar fs-5"></i>
                    </div>
					<h5>SCHEDULE</h5>
                    @if ($field->condition == 1)
                        <ul class="list-inline mb-0">
                            @foreach ($transactionData as $row)
                                @if ($row->status == 0 || $row->status == 1 || $row->status == 2 || ($row->status == 6 && strtolower(\Carbon\Carbon::now()->format('l')) == strtolower($row->day)))
                                    <li class="list-inline-item me-3 ms-3">
                                        <span class="fw-normal h6 mb-0">{{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->addHours($row->hour)->format('H:i') }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                @php
                                    $currentTime = now()->format('H:i');
                                    $isFull = false;
                                    $isClosed = false;

                                    $transactions = \App\Models\Transaction::where('field_id', $field->id)->get();

                                    foreach ($transactions as $transaction) {
                                        if (isset($transaction->time)) {
                                            $transactionStartTime = \Carbon\Carbon::createFromFormat('H:i', $transaction->time)->format('H:i');
                                            $transactionEndTime = \Carbon\Carbon::createFromFormat('H:i', $transaction->time)->addHours($transaction->hour)->format('H:i');

                                            if ($currentTime >= $transactionStartTime && $currentTime <= $transactionEndTime) {
                                                $isFull = true;
                                                break;
                                            }

                                            if ($transactionEndTime >= '23:00' || $transactionEndTime <= '04:00') {
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
                                @if ($isFull)
                                    <span class="fw-normal h6 mb-0">Full</span>
                                @elseif ($isClosed)
                                    <span class="fw-normal h6 mb-0">Closed</span>
                                @else
                                    <span class="fw-normal h6 mb-0">Available</span>
                                @endif
                            </li>
                        </ul>
                    @endif
				</div>
			</div>
            @if (auth()->guard('customer')->check())
                <div class="col-12">
                    <div class="card bg-dark bg-opacity-10 rounded-0 p-4">
                        <div class="card-body p-0">
                            <form method="POST" action="{{ route('customer.bookingStore', $field->slug) }}" enctype="multipart/form-data">
                                @csrf @method('POST') <input type="hidden" id="transactionData" value="{{ json_encode($transactionData) }}">
                                <div class="row">
                                    <div class="d-grid justify-content-center align-items-center mb-4">
                                        <span class="fw-normal text-center h5 mb-0">BOOKED NOW</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="date" class="fw-medium small h6">DATE</label>
                                        <div class="form-border-bottom form-control-transparent h6">
                                            <input type="text" name="date" id="date" class="form-control flatpickr" data-date-format="d F Y" placeholder="Select pickup date" required>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('date') }}</p>
                                    </div> <input type="hidden" name="field_id" value="{{ $field['id'] }}">
                                    <div class="col-md-4">
                                        <label for="time" class="fw-medium small h6">TIME</label>
                                        <div class="form-border-bottom form-control-transparent">
                                            <select name="time" id="time" class="form-control" required>
                                                <option value="16:00">16:00</option>
                                                <option value="17:00">17:00</option>
                                                <option value="18:00">18:00</option>
                                                <option value="19:00">19:00</option>
                                                <option value="20:00">20:00</option>
                                                <option value="21:00">21:00</option>
                                                <option value="22:00">22:00</option>
                                                <option value="23:00">23:00</option>
                                            </select>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('time') }}</p>
                                    </div> <input type="hidden" name="customer_id" value="{{ $customer['id'] }}">
                                    <div class="col-md-4 mb-3">
                                        <label for="hour" class="fw-medium small h6">PER HOUR</label>
                                        <div class="form-border-bottom form-control-transparent">
                                            <select name="hour" id="hour" class="form-control" required>
                                                <option value="1">1 Hour</option>
                                                <option value="2">2 Hour</option>
                                                <option value="3">3 Hour</option>
                                                <option value="4">4 Hour</option>
                                            </select>
                                        </div>
                                        <p class="text-danger small">{{ $errors->first('hour') }}</p>
                                    </div>
                                </div> <input type="hidden" name="price" id="price" value="0">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">CONTINUE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row g-4">
            <div class="col-12">
                <div class="accordion accordion-icon accordion-bg-light open" id="accordionExample2">
                    <div class="accordion-item mb-3">
                        <h6 class="accordion-header font-base" id="heading-2">
                            <button class="accordion-button rounded d-inline-block collapsed d-block pe-5 rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="true" aria-controls="collapse-2">
                                <span class="lead me-1 fw-normal">REVIEW</span>
                            </button>
                        </h6>
                        <div id="collapse-2" class="accordion-collapse collapse show" aria-labelledby="heading-2" data-bs-parent="#accordionExample2">
                            <div class="accordion-body mt-3">
                                <div class="card bg-transparent">
                                    <div class="card-body p-0">
                                        <div class="card bg-light p-4 mb-4 rounded-0">
                                            <div class="row g-4 align-items-center">
                                                <div class="col-md-12">
                                                    <div class="text-center">
                                                        <h2 class="mb-0">{{ number_format($field->getReview->avg('rate'), 1) }}</h2>
                                                        <p class="mb-2">Based on {{ $field->getReview->count() }} Reviews</p>
                                                        <ul class="list-inline mb-0">
                                                            @php
                                                                $avgRating = number_format($field->getReview->avg('rate'), 1);
                                                            @endphp
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($avgRating >= $i)
                                                                    <li class="list-inline-item me-0">
                                                                        <i class="fa-sharp fa-solid fa-star-sharp text-warning"></i>
                                                                    </li>
                                                                @elseif ($avgRating >= ($i - 0.5))
                                                                    <li class="list-inline-item me-0">
                                                                        <i class="fa-sharp fa-solid fa-star-sharp-half-stroke text-warning"></i>
                                                                    </li>
                                                                @else
                                                                    <li class="list-inline-item me-0">
                                                                        <i class="fa-sharp fa-regular fa-star-sharp text-warning"></i>
                                                                    </li>
                                                                @endif
                                                            @endfor
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('review.store', $field->slug) }}" class="mb-5">
                                            @csrf
                                            <div class="form-control-bg-light mb-3">
                                                <select name="rate" id="rate" class="form-control rounded-0">
                                                    <option value="5" selected>★★★★★ (5/5)</option>
                                                    <option value="4">★★★★☆ (4/5)</option>
                                                    <option value="3">★★★☆☆ (3/5)</option>
                                                    <option value="2">★★☆☆☆ (2/5)</option>
                                                    <option value="1">★☆☆☆☆ (1/5)</option>
                                                </select>
                                                <div class="form-text text-danger fw-bold">{{ $errors->first('rate') }}</div>
                                            </div>
                                            <div class="form-control-bg-light mb-3">
                                                <textarea class="form-control rounded-0" name="review" id="review" placeholder="Your review" rows="5"></textarea>
                                                <div class="form-text text-danger fw-bold">{{ $errors->first('review') }}</div>
                                            </div>
                                            <button type="submit" class="btn btn-lg btn-dark mb-0 rounded-0">SUBMIT</button>
                                        </form>
                                        <div class="scrollbar scrollbar-secondary">
                                            @foreach($field['getReview'] as $data)
                                                <div class="d-lg-flex my-4">
                                                    <div class="avatar avatar-lg me-3 flex-shrink-0">
                                                        <img src="{{ asset('storage/customers/' . $data->customer->image) }}" alt="avatar" class="avatar-img rounded-circle">
                                                    </div>
                                                    <div>
                                                        <div class="d-flex justify-content-between mt-1 mt-md-0">
                                                            <div>
                                                                <h6 class="me-3 mb-0">{{ $data->customer['name'] }}</h6>
                                                                <ul class="nav nav-divider small mb-0">
                                                                    <li class="nav-item">Stayed {{ $data->customer->created_at->format('d M Y') }}</li>
                                                                </ul>
                                                                <ul class="list-inline mb-2">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($data->rate >= $i)
                                                                            <li class="list-inline-item me-0">
                                                                                <i class="fa-sharp fa-solid fa-star-sharp text-warning"></i>
                                                                            </li>
                                                                        @else
                                                                            <li class="list-inline-item me-0">
                                                                                <i class="fa-sharp fa-regular fa-star-sharp text-warning"></i>
                                                                            </li>
                                                                        @endif
                                                                    @endfor
                                                                    <li class="list-inline-item me-0 small">(<span>{{ $data->rate }}</span>)</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <p class="mb-0">{{ $data->comment }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dateSelect = document.getElementById('date');
        var timeSelect = document.getElementById('time');
        var hourSelect = document.getElementById('hour');
        var priceInput = document.getElementById('price');
        var transactionData = JSON.parse(document.getElementById('transactionData').value);

        function formatDate(date) {
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();

            return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
        }

        function updateDisabledTimeOptions() {
            var selectedDate = dateSelect.value;
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
                return (transaction.date === selectedDate || transaction.day === currentDay);
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

            if (selectedDate === formatDate(currentTime) && currentDay === formatDate(currentTime, true)) {
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
            var fieldPrice = {{ $field['price'] }};
            var totalPrice = selectedHour * fieldPrice;
            priceInput.value = totalPrice;
        }

        hourSelect.addEventListener('change', function() {
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
