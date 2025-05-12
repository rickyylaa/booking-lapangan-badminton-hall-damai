<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Notification
{
    public static function getNotifications()
    {
        $user = Auth::guard('customer')->user();

        if ($user) {
            return DB::table('customers')
                ->leftJoin('notifications', function ($join) use ($user) {
                    $join->on('customers.id', '=', 'notifications.customer_id')
                        ->where('notifications.is_read', '=', 0)
                        ->where('notifications.customer_id', '=', $user->id);
                })
                ->leftJoin('fields', 'fields.id', '=', 'notifications.field_id')
                ->leftJoin('transactions', 'transactions.id', '=', 'notifications.transaction_id')
                ->leftJoin('transaction_details', 'transaction_details.id', '=', 'notifications.detail_id')
                ->select(
                    'fields.title',
                    'customers.name',
                    'customers.id as customer_id',
                    'transactions.time as transactionTime',
                    'transactions.hour as transactionHour',
                    'transaction_details.time as detailTime',
                    'transaction_details.hour as detailHour',
                    'notifications.id as notification_id',
                    DB::raw("DAYNAME(STR_TO_DATE(transactions.date, '%d %b %Y')) as transactionDay"),
                    DB::raw("DAYNAME(STR_TO_DATE(transaction_details.date, '%d %b %Y')) as detailDay"),
                    DB::raw('COUNT(notifications.is_read) as unread')
                )
                ->groupBy('fields.title',  'customers.name','customers.id', 'transactions.time', 'transactions.hour', 'transaction_details.time', 'transaction_details.hour', 'notifications.id', 'transactionDay', 'detailDay')
                ->get();
        } else {
            return [];
        }
    }
}
