@extends('frontend.main.layouts.app')
@section('title', 'Booking')

@section('content')
<section class="pt-4 pt-md-5">
	<div class="container">
		<div class="row mb-5">
			<div class="col-xl-10">
				<h1>Schedule</h1>
				<p class="lead mb-0">Lorem ipsum dolor sit consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore.</p>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card border rounded-0 h-100">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-shrink table-borderless align-middle mb-0" id="calendar">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="border-0"></th>
                                        <th scope="col" class="border-0">SUNDAY</th>
                                        <th scope="col" class="border-0">MONDAY</th>
                                        <th scope="col" class="border-0">TUESDAY</th>
                                        <th scope="col" class="border-0">WEDNESDAY</th>
                                        <th scope="col" class="border-0">THURSDAY</th>
                                        <th scope="col" class="border-0">FRIDAY</th>
                                        <th scope="col" class="border-0">SATURDAY</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        generateCalendar();
        fetchTransactionData();
    });

    async function fetchTransactionData() {
        try {
            const response = await fetch('/api/transactions');
            const data = await response.json();

            updateCalendarWithData(data);
        } catch (error) {
            console.error('Error fetching transaction data:', error);
        }
    }

    function updateCalendarWithData(data) {
        const spans = document.querySelectorAll('#calendar tbody td span');
        const currentMonth = new Date().getMonth() + 1;

        const dayTransactionMap = {};

        data.forEach(transaction => {
            const transactionDates = transaction.date.split(', ');
            transactionDates.forEach(date => {
                const transactionDate = new Date(date);
                const day = transactionDate.getDate();
                const month = transactionDate.getMonth() + 1;

                if (month === currentMonth) {
                    if (!dayTransactionMap[day]) {
                        dayTransactionMap[day] = [];
                    }

                    dayTransactionMap[day].push(transaction);
                }
            });
        });

        function getTransactionInfoText(transaction) {
            if (transaction.hour !== undefined && transaction.time !== undefined) {
                const [hours, minutes] = transaction.time.split(':').map(Number);

                let formattedStartTime = `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;

                if (transaction.hour && transaction.hour > 0) {
                    let startTime = new Date();
                    startTime.setHours(hours);
                    startTime.setMinutes(minutes);

                    let endTime = new Date(startTime.getTime() + transaction.hour * 60 * 60 * 1000);

                    let formattedEndTime = `${endTime.getHours() < 10 ? '0' : ''}${endTime.getHours()}:${endTime.getMinutes() < 10 ? '0' : ''}${endTime.getMinutes()}`;

                    return `Field 0${transaction.field_id} : ${formattedStartTime} - ${formattedEndTime}`;
                } else {
                    return `Field 0${transaction.field_id} : ${formattedStartTime}`;
                }
            } else {
                console.error('Invalid transaction structure:', transaction);
                return 'Incomplete transaction data';
            }
        }

        spans.forEach(span => {
            const spanDay = parseInt(span.textContent.trim(), 10);

            if (!isNaN(spanDay) && dayTransactionMap[spanDay]) {
                const link = document.createElement('a');
                link.href = '#';
                link.classList.add('text-danger');
                link.setAttribute('role', 'button');
                link.setAttribute('id', 'actionDropdown');
                link.setAttribute('data-bs-toggle', 'dropdown');
                link.setAttribute('aria-expanded', 'false');
                link.textContent = spanDay;

                const dropdownMenu = document.createElement('ul');
                dropdownMenu.className = 'dropdown-menu min-w-auto border rounded-0';
                dropdownMenu.setAttribute('aria-labelledby', 'actionDropdown');

                dayTransactionMap[spanDay].forEach(transaction => {
                    if (transaction.status != 3 && transaction.status != 4 && transaction.status != 5) {
                        const listItem = document.createElement('li');
                        const anchorItem = document.createElement('a');
                        anchorItem.className = 'dropdown-item';
                        anchorItem.href = `http://localhost:8000/booking/field-0${transaction.field_id}`;
                        anchorItem.textContent = `${getTransactionInfoText(transaction)}`;
                        listItem.appendChild(anchorItem);
                        dropdownMenu.appendChild(listItem);
                    }
                });

                if (dropdownMenu.children.length > 0) {
                    span.innerHTML = '';
                    span.appendChild(link);
                    span.appendChild(dropdownMenu);
                }
            }
        });
    }

    function generateCalendar() {
        const calendarBody = document.querySelector('#calendar tbody');
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth();
        const daysInMonth = new Date(currentDate.getFullYear(), currentMonth + 1, 0).getDate();
        const firstDayOfMonth = new Date(currentDate.getFullYear(), currentMonth, 1).getDay();

        let dayCount = 1;

        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');

            for (let j = 0; j < 8; j++) {
                const cell = document.createElement('td');

                const span = document.createElement('span');
                span.className = 'fw-normal h5 mb-1';

                if (j === 0) {
                } else {
                    if (i === 0 && j - 1 < firstDayOfMonth) {
                        span.textContent = '';
                    } else if (dayCount <= daysInMonth) {
                        span.textContent = dayCount;
                        dayCount++;
                    }
                }

                cell.appendChild(span);
                row.appendChild(cell);
            }

            calendarBody.appendChild(row);
        }
    }
</script>
@endsection
