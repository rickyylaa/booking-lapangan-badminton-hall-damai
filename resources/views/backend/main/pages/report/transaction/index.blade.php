@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-misc-report-transaction', 'active')
@section('true-misc-report', 'true')
@section('show-misc-report', 'show')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">REPORT TRANSACTIONS</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('report.transaction') }}" class="d-flex justify-content-between align-items-center mb-3">
                        <div class="col-6 col-xl-3">
                            <div class="form-border-bottom form-control-transparent">
                                <div class="form-fs-md">
                                    <div class="rounded position-relative">
                                        <input type="text" name="date" id="created_at" class="form-control pe-5 bg-secondary bg-opacity-10">
                                        <button type="submit" class="btn btn-link bg-transparent px-2 py-0 position-absolute top-50 end-0 translate-middle-y text-dark-hover">
                                            <i class="fas fa-search fs-6 text-secondary-hover"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3 text-end">
                            <a target="_blank" class="btn btn-dark w-100px rounded-0" id="exportpdf">EXPORT</a>
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
            </div>
        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let start = moment().startOf('month')
        let end = moment().endOf('month')

        $('#exportpdf').attr('href', '/admin/report/transaction/pdf/' + start.format('YYYY-MM-DD') + '+' + end.format('YYYY-MM-DD'))
        $('#created_at').daterangepicker({
            startDate: start,
            endDate: end
        }, function(first, last) {
            $('#exportpdf').attr('href', '/admin/report/transaction/pdf/' + first.format('YYYY-MM-DD') + '+' + last.format('YYYY-MM-DD'))
        })
    })
    </script>
@endsection
