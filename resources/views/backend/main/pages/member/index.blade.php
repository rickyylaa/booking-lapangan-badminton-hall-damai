@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-menu-member', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">MEMBERS</span>
                <button type="button" class="btn btn-dark w-100px rounded-0" data-bs-toggle="modal" data-bs-target="#memberModal">CREATE</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('member.index') }}" class="d-flex justify-content-between mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent">
                                <select name="status" class="form-control js-choice h6">
                                    <option value="">Please choose one</option>
                                    <option value="0">Playing</option>
                                    <option value="1">Complete</option>
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
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">CUSTOMER</th>
                                    <th scope="col" class="border-0">FIELD</th>
                                    <th scope="col" class="border-0">PAYMENT</th>
                                    <th scope="col" class="border-0">TIME</th>
                                    <th scope="col" class="border-0">STATUS</th>
                                    <th scope="col" class="border-0">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($member) > 0)
                                    @foreach ($member as $row)
                                        <tr>
                                            <td> </td>
                                            <td>
                                                <div class="card card-element-hover card-overlay-hover overflow-hidden avatar-xl rounded-0">
                                                    <img src="{{ asset('storage/customers/'. $row->customer->image) }}" alt="avatar" class="avatar-img bg-light shadow rounded-0">
                                                    <a href="{{ asset('storage/customers/'. $row->customer->image) }}" class="hover-element position-absolute w-100 h-100" data-glightbox data-gallery="gallery">
                                                        <i class="bi bi-fullscreen fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-0 p-2 lh-1"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6 mb-1">Name: {{ ucwords($row->customer->name) }}</span>
                                                    <span class="fw-normal small h6 mb-1">Phone: {{ chunk_split($row->customer['phone'], 4) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6 mb-1">Field: {{ ucwords($row->field->title) }}</span>
                                                    <span class="fw-normal small h6 mb-1">Day: {{ ucwords($row->day) }}</span>
                                                    <span class="fw-normal small h6 mb-1">Per Hour: {{ $row->hour }} Hour</span>
                                                    <span class="fw-normal small h6 mb-0">Price: IDR {{ number_format($row->price) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-grid justify-content-start">
                                                    <span class="fw-normal small h6 mb-1">Amount: IDR {{ number_format($row->amount) }}</span>
                                                    <span class="fw-normal small h6 mb-0">
                                                        @if ($row->amount < $row->price)
                                                            Outstanding Balance: IDR {{ number_format($row->price - $row->amount) }}
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
                                            <td> {!! $row->status_label !!} </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        @if ($row->amount < $row->price)
                                                            <form method="POST" action="{{ route('member.accept', $row->id) }}">
                                                                @csrf
                                                                <li>
                                                                    <button type="submit" class="dropdown-item bg-dark-soft-hover">
                                                                        <i class="fa-sharp fa-solid fa-check me-2"></i>Accept
                                                                    </button>
                                                                </li>
                                                            </form>
                                                        @endif
                                                        <form method="POST" action="{{ route('member.destroy', $row->id) }}">
                                                            @csrf @method('DELETE')
                                                            @if ($row->status == 0)
                                                                <li>
                                                                    <a href="{{ route('member.edit', $row->id) }}" class="dropdown-item">
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
                @if (count($member) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">SHOWING {{ $member->firstItem() }} TO {{ $member->lastItem() }} OF {{ $member->total() }} ENTRIES</span>
                            {!! $member->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modal')
<div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border rounded-0">
            <div class="modal-header border-0">
                <span class="fw-normal text-center h5 mb-0">CREATE MEMBER</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('member.store') }}" enctype="multipart/form-data">
                    @csrf <input type="hidden" id="memberData" value="{{ json_encode($memberData) }}">
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
                            <label for="day" class="fw-medium small h6">DAY</label>
                            <div class="form-border-bottom form-control-transparent">
                                <select name="day" id="day" class="form-control" required>
                                    <option value="sunday">Sunday</option>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                </select>
                            </div>
                            <p class="text-danger small">{{ $errors->first('day') }}</p>
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
                        <button type="submit" class="btn btn-dark w-50 rounded-0 mb-0">CONTINUE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/choices/css/choices.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/css/glightbox.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/css/flatpickr.min.css') }}" type="text/css">
@endsection

@section('js')
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/js/flatpickr.min.js') }}"></script>
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
