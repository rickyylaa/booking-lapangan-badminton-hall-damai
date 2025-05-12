<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Field;
use App\Models\Customer;
use App\Services\Helper;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\TransactionCancel;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\PDF as PDF;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    public function bookingForm($slug)
    {
        $customer = auth()->guard('customer')->user();
        $field = Field::where('status', 1)->where('slug', $slug)->first();
        $transactionData = Transaction::where('field_id', $field->id)->get();
        return view('frontend.main.pages.field.booking', compact('transactionData', 'customer', 'field'));
    }

    public function bookingStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date_format:d F Y',
            'time' => 'required|date_format:H:i',
            'hour' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $field = Field::findOrFail($request->field_id);
            $lastTransaction = Transaction::orderBy('id', 'DESC')->first();

            $customer = auth()->check() ? auth()->user() : Customer::where('id', $request->customer_id)->first();
            if (!auth()->guard('customer')->check() && $customer) {
                return redirect()->back()->with(['error' => 'Please log in first']);
            }

            $transactionEndTime = Carbon::createFromFormat('H:i', $request->time)->addHours($request->hour);
            if ($transactionEndTime->greaterThan(Carbon::createFromFormat('H:i', '23:00'))) {
                Alert::toast('<span class="px-lg-4 ms-2">Transaction time cannot exceed 23:00</span>')->hideCloseButton()->width('489px')->padding('25px')->toHtml();
                return redirect()->back();
            }

            $existingTransactions = Transaction::where('field_id', $request->field_id)->get();
            foreach ($existingTransactions as $row) {
                $existingStartTime = Carbon::createFromFormat('H:i', $row->time);
                $existingEndTime = $existingStartTime->copy()->addHours($row->hour);

                $newStartTime = Carbon::createFromFormat('H:i', $request->time);
                $newEndTime = $newStartTime->copy()->addHours($request->hour);

                if (($newStartTime >= $existingStartTime && $newStartTime < $existingEndTime) ||
                    ($newEndTime > $existingStartTime && $newEndTime <= $existingEndTime) ||
                    ($newStartTime <= $existingStartTime && $newEndTime >= $existingEndTime)) {
                    Alert::toast('<span class="px-lg-4 ms-2">Transaction time overlaps with existing transaction</span>')->hideCloseButton()->width('489px')->padding('25px')->toHtml();
                    return redirect()->back();
                }
            }

            $lastInvoiceNumber = $lastTransaction ? substr($lastTransaction->invoice, 4) : 0;
            $invoiceNumber = str_pad($lastInvoiceNumber + 1, 3, '0', STR_PAD_LEFT);
            $invoice = 'INV-' . $invoiceNumber;

            $transaction = Transaction::create([
                'invoice' => $invoice,
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'date' => $request->date,
                'time' => $request->time,
                'hour' => $request->hour,
                'price' => $request->price
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction is successfully</span>')->hideCloseButton()->width('389px')->padding('25px')->toHtml();
            return redirect(route('customer.confirmForm', $transaction->invoice));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred when making a transaction</span>'. $e->getMessage())->hideCloseButton()->width('546px')->padding('25px')->toHtml();
            return redirect(route('front.bookingForm', $field->slug));
        }
    }

    public function confirmForm($invoice)
    {
        $transactionData = Transaction::where('invoice', $invoice)->first();

        if ($transactionData) {
            $field = $transactionData->field;

            $customer = auth()->guard('customer')->user();
            $priceText = Helper::convertToWords($transactionData->price);

            if (Gate::forUser($customer)->allows('transaction-view', $transactionData)) {
                return view('frontend.main.pages.field.confirm', compact('customer', 'transactionData', 'field', 'priceText'));
            } else {
                Alert::toast('<span class="px-lg-4 ms-2">You are not allowed to access other people`s transactions</span>')->hideCloseButton()->width('546px')->padding('25px')->toHtml();
                return redirect(route('front.bookingForm', $field->slug));
            }
        } else {
            Alert::toast('<span class="px-lg-4 ms-2">Transaction not found</span>')->hideCloseButton()->width('456px')->padding('25px')->toHtml();
            return redirect()->route('front.index');
        }
    }

    public function confirmStore($invoice)
    {
        try {
            $transaction = Transaction::where('invoice', $invoice)->first();

            if (!$transaction) {
                Alert::toast('<span class="px-lg-4 ms-2">Transaction not found</span>')->hideCloseButton()->width('389px')->padding('25px')->toHtml();
                return redirect()->back();
            }

            $field = $transaction->field;
            $transaction->delete();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction canceled successfully</span>')->hideCloseButton()->width('456px')->padding('25px')->toHtml();
            return redirect(route('front.bookingForm', $field->slug));
        } catch (\Exception $e) {
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while canceling the transaction</span>')->hideCloseButton()->width('546px')->padding('25px')->toHtml();
            return redirect(route('front.bookingForm', $field->slug));
        }
    }

    public function paymentForm($invoice)
    {
        $transactionData = Transaction::where('invoice', $invoice)->first();

        if ($transactionData) {
            $field = $transactionData->field;
            $customer = auth()->guard('customer')->user();

            if (Gate::forUser($customer)->allows('transaction-view', $transactionData)) {
                return view('frontend.main.pages.field.payment', compact('customer', 'transactionData', 'field'));
            } else {
                Alert::toast('<span class="px-lg-4 ms-2">You are not allowed to access other people`s transactions</span>')->hideCloseButton()->width('546px')->padding('25px')->toHtml();
                return redirect(route('front.bookingForm', $field->slug));
            }
        } else {
            Alert::toast('<span class="px-lg-4 ms-2">Transaction not found</span>')->hideCloseButton()->width('456px')->padding('25px')->toHtml();
            return redirect()->route('front.index');
        }
    }

    public function paymentStore(Request $request, $invoice)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|numeric',
            'amount' => 'required|numeric',
            'proof' => 'required|image|mimes:png,jpeg,jpg,gif,webp|max:5000'
        ]);

        try {
            DB::beginTransaction();

            $field = Field::findOrFail($request->field_id);
            $transaction = Transaction::with(['field'])->where('invoice', $invoice)->first();
            $customer = auth()->check() ? auth()->user() : Customer::where('id', $request->customer_id)->first();

            if (!auth()->guard('customer')->check() && $customer) {
                return redirect()->back()->with(['error' => 'Please log in first']);
            }

            if ($request->hasFile('proof')) {
                $file = $request->file('proof');
                $filename = Str::slug($request->account_name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/proofs', $filename);
            }

            $detail = TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
                'proof' => $filename
            ]);

            $transaction->update([
                'detail_id' => $detail->id,
            ]);

            $field->update([
                'condition' => 1
            ]);

            Notification::create([
                'transaction_id' => $transaction->id,
                'detail_id' => $detail->id,
                'customer_id' => $request->customer_id,
                'field_id' => $field->id,
            ]);

            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
            );

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $data = ['customer_id' => $transaction->customer->id];
            $pusher->trigger('my-channel', 'my-event', $data);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction is successful</span>')->hideCloseButton()->width('379px')->padding('25px')->toHtml();
            return redirect(route('customer.bookingInfo', $transaction->invoice));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred when making a transaction</span>')->hideCloseButton()->width('546px')->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function bookingInfo($invoice)
    {
        $transactionData = Transaction::where('invoice', $invoice)->first();

        if ($transactionData) {
            $field = $transactionData->field;

            $customer = auth()->guard('customer')->user();
            $priceText = Helper::convertToWords($transactionData->detail->amount);

            if (Gate::forUser($customer)->allows('transaction-view', $transactionData)) {
                return view('frontend.main.pages.field.invoice', compact('customer', 'transactionData', 'field', 'priceText'));
            } else {
                Alert::toast('<span class="px-lg-4 ms-2">You are not allowed to access other people`s transactions</span>')->hideCloseButton()->width('666px')->padding('25px')->toHtml();
                return redirect(route('front.bookingForm', $field->slug));
            }
        } else {
            Alert::toast('<span class="px-lg-4 ms-2">Transaction not found</span>')->hideCloseButton()->width('456px')->padding('25px')->toHtml();
            return redirect()->route('front.index');
        }
    }

    public function bookingPDF($invoice)
    {
        $transactionData = Transaction::with(['detail', 'detail.field'])->where('invoice', $invoice)->first();

        if (Gate::forUser(auth()->guard('customer')->user())->allows('bookingInfo', $transactionData)) {
            return redirect(route('customer.bookingInfo', $transactionData->invoice));
        }

        $pdf = PDF::loadView('frontend.main.pages.field.pdf', compact('transactionData'));
        return $pdf->stream();
    }

    public function cancelForm($invoice)
    {
        $transactionData = Transaction::where('invoice', $invoice)->first();

        if ($transactionData) {
            $createdAgo = $transactionData->created_at->diffInMinutes(now());
            if ($createdAgo >= 60) {
                Alert::toast('<span class="px-lg-4 ms-2">Cancellation period has expired</span>')->hideCloseButton()->width('486px')->padding('25px')->toHtml();
                return redirect()->route('customer.dashboard');
            }

            if ($transactionData->cancel && $transactionData->cancel->status == 0) {
                Alert::toast('<span class="px-lg-4 ms-2">Cancellation request already in process</span>')->hideCloseButton()->width('486px')->padding('25px')->toHtml();
                return redirect()->route('customer.dashboard');
            }

            $field = $transactionData->field;
            $customer = auth()->guard('customer')->user();

            if (Gate::forUser($customer)->allows('transaction-view', $transactionData)) {
                return view('frontend.main.pages.dashboard.cancel', compact('customer', 'transactionData', 'field'));
            } else {
                Alert::toast('<span class="px-lg-4 ms-2">You are not allowed to access other people`s transactions</span>')->hideCloseButton()->width('546px')->padding('25px')->toHtml();
                return redirect(route('customer.dashboard'));
            }
        } else {
            Alert::toast('<span class="px-lg-4 ms-2">Transaction not found</span>')->hideCloseButton()->width('456px')->padding('25px')->toHtml();
            return redirect()->route('customer.dashboard');
        }
    }

    public function cancelStore(Request $request, $id)
    {
        $this->validate($request, [
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'amount' => 'required|numeric',
            'proof' => 'required|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'reason' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::find($request->transaction_id);

            if ($request->hasFile('proof')) {
                $file = $request->file('proof');
                $filename = Str::slug($transaction->customer->name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/proofs', $filename);
            }

            TransactionCancel::create([
                'transaction_id' => $transaction->id,
                'customer_id' => $transaction->customer_id,
                'field_id' => $transaction->field_id,
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
                'proof' => $filename,
                'reason' => $request->reason
            ]);

            $transaction->update([
                'status' => 4
            ]);

            $remainingTransactions = Transaction::where('field_id', $transaction->field_id)->where('status', '<>', 3)->exists();

            if (!$remainingTransactions) {
                $transaction->field()->update(['condition' => 0]);
            }

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction canceled successfully</span>')->hideCloseButton()->width('531px')->padding('25px')->toHtml();
            return redirect(route('customer.dashboard'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while canceling the transaction</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('customer.transactionCancel', $transaction->invoice));
        }
    }
}
