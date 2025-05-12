@extends('backend.main.layouts.app')
@section('title', 'Booking')
@section('active-other-message', 'active')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium h4 mb-0">MESSAGE</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-0 h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-shrink table-borderless align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="border-0"></th>
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">NAME</th>
                                    <th scope="col" class="border-0">EMAIL</th>
                                    <th scope="col" class="border-0">PHONE</th>
                                    <th scope="col" class="border-0">MESSAGE</th>
                                    <th scope="col" class="border-0">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @if (count($message) > 0)
                                    @foreach ($message as $row)
                                        <tr>
                                            <td> </td>
                                            <td> <span class="fw-normal h6">{{ $row->id }}</span> </td>
                                            <td> <span class="fw-normal h6">{{ ucwords($row->name) }}</span> </td>
                                            <td> <span class="fw-normal h6">{{ $row->email }}</span> </td>
                                            <td> <span class="fw-normal h6">{{ $row->phone }}</span> </td>
                                            <td> <span class="fw-normal h6">{{ ucwords($row->message) }}</span> </td>
                                            <td>
                                                <div class="ms-4">
                                                    <a href="#" class="text-dark" role="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto border rounded-0" aria-labelledby="actionDropdown">
                                                        <form method="POST" action="{{ route('message.destroy', $row->id) }}">
                                                            @csrf @method('DELETE')
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
                @if (count($message) > 0)
                    <div class="card-footer border-top pt-2 pb-2">
                        <div class="d-flex justify-content-sm-between align-items-sm-center px-xxl-3">
                            <span class="fw-normal small mb-0">SHOWING {{ $message->firstItem() }} TO {{ $message->lastItem() }} OF {{ $message->total() }} ENTRIES</span>
                            {!! $message->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
