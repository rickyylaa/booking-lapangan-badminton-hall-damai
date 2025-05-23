<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Report Transaction PDF</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        text-align: left;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        /* border-top: 2px solid #eee; */
        /*font-weight: bold;*/
        text-align: left;
    }

    .text-align-right {
        text-align: right;
    }

    .mb-2 {
        margin-bottom: 2px;
    }

    .mt-2 {
        margin-top: 2px;
    }

    @media  only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                BOOKING
                            </td>
                            <td>
                                Date: {{ $date[0] }} - {{ $date[1] }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Invoice ID</td>
                <td>Customer</td>
                <td>Item</td>
                <td class="text-align-right">Price</td>
            </tr>
            @php $total = 0; @endphp
            @forelse ($transaction as $row)
                <tr class="item">
                    <td> <strong>{{ $row->invoice }}</strong> </td>
                    <td>
                        <label>Name: {{ $row->customer->name }}</label><br>
                        <label>Phone: {{ $row->customer->phone }}</label><br>
                        <label>Address: {{ $row->customer->address }}</label>
                    </td>
                    <td> <label>{{ $row->field->title }}</label><br> </td>
                    <td> <label>IDR {{ number_format($row->field->price) }}/ Hour</label><br> </td>
                </tr>
                @php $total += $row->field->price * $row->hour; @endphp
            @empty
                <tr class="item">
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr class="total">
                <td></td>
                <td></td>
                <td class="text-align-right">
                    <b>Total:</b>
                </td>
                <td colspan="3">
                    <b>IDR {{ number_format($total) }}</b>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
