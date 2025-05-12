<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\Notification;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\PDF as PDF;

class ReportController extends Controller
{
    public function transactionReport()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        if (request()->date != '') {
            $date = explode(' - ' ,request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $notification = Notification::getNotifications();
        $transaction = Transaction::with(['customer'])->whereBetween('created_at', [$start, $end])->where('status', 3)->get();
        return view('backend.main.pages.report.transaction.index', compact('transaction', 'notification'));
    }

    public function transactionReportPdf($dateRange)
    {
        $date = explode('+', $dateRange);
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        $transaction = Transaction::with(['customer'])->whereBetween('created_at', [$start, $end])->where('status', 3)->get();

        $pdf = PDF::loadView('backend.main.pages.report.transaction.pdf', compact('transaction', 'date'));
        return $pdf->stream();
    }
}
