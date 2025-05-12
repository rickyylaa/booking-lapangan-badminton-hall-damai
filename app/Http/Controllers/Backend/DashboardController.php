<?php

namespace App\Http\Controllers\Backend;

use App\Models\Field;
use App\Models\Member;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\Notification;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::selectRaw('
            COALESCE(sum(CASE WHEN status IN (1, 2, 3, 6) THEN transaction_details.amount END), 0) as turnover,
            COALESCE(count(CASE WHEN status = 0 THEN transaction_details.amount END), 0) as new,
            COALESCE(count(CASE WHEN status = 1 THEN transaction_details.amount END), 0) as pending,
            COALESCE(count(CASE WHEN status = 2 THEN transaction_details.amount END), 0) as playing,
            COALESCE(count(CASE WHEN status = 3 THEN transaction_details.amount END), 0) as complete,
            COALESCE(count(CASE WHEN status = 4 THEN transaction_details.amount END), 0) as cancel')
        ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->get();

        $field = Field::get();
        $member = Member::get();
        $customer = Customer::get();
        $notification = Notification::getNotifications();
        $transaction = Transaction::where('status', 3)->get();
        return view('backend.main.pages.dashboard.index', compact('transactions', 'field', 'member', 'customer', 'transaction', 'notification'));
    }

    public function apiTransactions()
    {
        $transactions = Transaction::whereNotNull('date')->get();
        return response()->json($transactions);
    }
}
