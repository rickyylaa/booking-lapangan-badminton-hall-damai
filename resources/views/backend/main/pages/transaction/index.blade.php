@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-misc-transaction', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">TRANSACTIONS</span>
                <button type="button" class="btn btn-dark w-100px rounded-0" data-bs-toggle="modal" data-bs-target="#transactionModal">CREATE</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('transaction.index') }}" class="d-flex justify-content-between mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent">
                                <select name="status" class="form-control js-choice h6">
                                    <option value="">Please choose one</option>
                                    <option value="0">New</option>
                                    <option value="1">Pending</option>
                                    <option value="2,6">Playing</option>
                                    <option value="3">Complete</option>
                                    <option value="4">Waiting</option>
                                    <option value="5">Cancel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent h6">
                                <div class="input-group float-right">
                                    <input type="text" name="q" class="form-control rounded pe-5" value="{{ request()->q }}" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn border-0 px-3 py-0 position-absolute top-50 end-0 translate-middle-y">
                                            <i class="fas fa-search fs-6"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">INVOICE</th>
                                    <th scope="col" class="border-0">CUSTOMER</th>
                                    <th scope="col" class="border-0">FIELD</th>
                                    <th scope="col" class="border-0">PAYMENT</th>
                                    <th scope="col" class="border-0">TIME</th>
                                    <th scope="col" class="border-0">FOR</th>
                                    <th scope="col" class="border-0">STATUS</th>
                                    <th scope="col" class="border-0">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($transaction) > 0)
                                    @foreach ($transaction as $row)
                                        <tr>
                                            <td> </td>
                                            <td> <span class="fw-normal h6">{{ $row->invoice }}</span> </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6 mb-1">Name: {{ ucwords($row->customer->name) }}</span>
                                                    <span class="fw-normal small h6 mb-1">Phone: {{ chunk_split($row->customer['phone'], 4) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6 mb-1">Field: {{ ucwords($row->field->title) }}</span>
                                                    @if ($row->time == NULL)
                                                        @if ($row->day == NULL)
                                                            <span class="fw-normal small h6 mb-1">Date: {{ $row->detail->date }}</span>
                                                        @else
                                                            <span class="fw-normal small h6 mb-1">Day: {{ ucwords($row->detail->day) }}</span>
                                                        @endif
                                                    @else
                                                        @if ($row->day == NULL)
                                                            <span class="fw-normal small h6 mb-1">Date: {{ $row->date }}</span>
                                                        @else
                                                            <span class="fw-normal small h6 mb-1">Day: {{ ucwords($row->day) }}</span>
                                                        @endif
                                                    @endif
                                                    <span class="fw-normal small h6 mb-1">Per Hour: {{ $row->hour }} Hour</span>
                                                    <span class="fw-normal small h6 mb-0">Price: IDR {{ number_format($row->price) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    @if ($row->detail->account_name)
                                                        <span class="fw-normal small h6 mb-1">Account Name: {{ Str::upper($row->detail->account_name) }}</span>
                                                        <span class="fw-normal small h6 mb-1">Bank Name: {{ Str::upper($row->detail->bank_name) }}</span>
                                                        <span class="fw-normal small h6 mb-1">Account Number: {{ chunk_split($row->detail['account_number'], 4) }}</span>
                                                    @endif
                                                    <span class="fw-normal small h6 mb-1">Amount: IDR {{ number_format($row->detail->amount) }}</span>
                                                    <span class="fw-normal small h6 mb-0">
                                                        @if ($row->detail->amount < $row->price)
                                                            Outstanding Balance: IDR {{ number_format($row->price - $row->detail->amount) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($row->time == NULL)
                                                    <span class="fw-normal small h6 mb-1">{{ \Carbon\Carbon::createFromFormat('H:i', $row->detail->time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i', $row->detail->time)->addHours($row->detail->hour)->format('H:i') }}</span>
                                                @else
                                                    <span class="fw-normal small h6 mb-1">{{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i', $row->time)->addHours($row->hour)->format('H:i') }}</span>
                                                @endif
                                            </td>
                                            <td> {!! $row->member_label !!} </td>
                                            <td> {!! $row->status_label !!} </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <input type="hidden" name="transaction_id" value="{{ $row->id }}">
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        @if ($row->status == 0)
                                                            <form method="POST" action="{{ route('transaction.accept', $row->invoice) }}">
                                                                @csrf
                                                                <li>
                                                                    <button type="submit" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-solid fa-check me-2"></i>Accept
                                                                    </button>
                                                                </li>
                                                            </form>
                                                            <form method="POST" action="{{ route('transaction.cancelStore', $row->invoice) }}">
                                                                @csrf
                                                                <li>
                                                                    <button type="submit" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-regular fa-xmark me-2"></i>Cancel
                                                                    </button>
                                                                </li>
                                                            </form>
                                                        @endif
                                                        @if ($row->status == 1)
                                                            <form method="POST" action="{{ route('transaction.playing', $row->invoice) }}">
                                                                @csrf
                                                                <li>
                                                                    <button type="submit" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-regular fa-shuttlecock me-2"></i>Playing
                                                                    </button>
                                                                </li>
                                                            </form>
                                                        @endif
                                                        @if ($row->status == 2 || $row->status == 6)
                                                            <form method="POST" action="{{ route('transaction.complete', $row->invoice) }}">
                                                                @csrf
                                                                <li>
                                                                    <button type="submit" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-regular fa-octagon-check me-2"></i>Complete
                                                                    </button>
                                                                </li>
                                                            </form>
                                                        @endif
                                                        @if ($row->cancel && $row->cancel->status == 0)
                                                            <li>
                                                                <a href="{{ route('transaction.cancel', $row->invoice) }}" class="dropdown-item bg-dark-soft-hover">
                                                                    <i class="fa-sharp fa-regular fa-xmark me-2"></i>Cancel
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <form method="POST" action="{{ route('transaction.destroy', $row->id) }}">
                                                            @csrf @method('DELETE')
                                                            @if ($row->detail->proof)
                                                                <li>
                                                                    <a href="{{ asset('storage/proofs/'. $row->detail->proof) }}" target="_blank" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-regular fa-expand me-2"></i>View
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if ($row->status == 0)
                                                                <li>
                                                                    <a href="{{ route('transaction.edit', $row->id) }}" class="dropdown-item">
                                                                        <i class="fa-sharp fa-regular fa-pen-to-square me-2"></i>Edit
                                                                    </a>
                                                                </li>
                                                            @endif
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
                @if (count($transaction) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">SHOWING {{ $transaction->firstItem() }} TO {{ $transaction->lastItem() }} OF {{ $transaction->total() }} ENTRIES</span>
                            {!! $transaction->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modal')
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border rounded-0">
            <div class="modal-header border-0">
                <span class="fw-normal text-center h5 mb-0">CREATE TRANSACTION</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('transaction.store') }}" enctype="multipart/form-data">
                    @csrf <input type="hidden" id="transactionData" value="{{ json_encode($transactionData) }}">
                    <div class="row px-xxl-2">
                        <div class="col-md-6">
                            <label for="customer_id" class="fw-medium small h6">CUSTOMER</label>
                            <div class="form-border-bottom form-control-transparent">
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Please choose one</option>
                                    @foreach ($customer as $row)
                                        <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
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
                                        <option value="{{ $row->id }}">{{ ucwords($row->title) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-danger small">{{ $errors->first('field_id') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label for="date" class="fw-medium small h6">DATE</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="text" name="date" id="date" class="form-control flatpickr" data-date-format="d F Y" placeholder="Select pickup date" required>
                            </div>
                            <p class="text-danger small">{{ $errors->first('date') }}</p>
                        </div>
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
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-12">
                            <label for="price" class="small fw-medium h6">PRICE</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="text" name="price" id="price" class="form-control" value="0" readonly>
                            </div>
                            <p class="text-danger small">{{ $errors->first('price') }}</p>
                        </div>
                        <div class="col-12">
                            <label for="amount" class="small fw-medium h6">AMOUNT</label>
                            <div class="form-border-bottom form-control-transparent h6">
                                <input type="text" name="amount" id="amount" class="form-control" value="0">
                            </div>
                            <p class="text-danger small">{{ $errors->first('amount') }}</p>
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
