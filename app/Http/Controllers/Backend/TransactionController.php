<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Field;
use App\Models\Member;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\Notification;
use App\Models\TransactionCancel;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    public function transaction()
    {
        $transaction = Transaction::orderBy('id', 'DESC');
        $transactionData = Transaction::get();
        $transactionCancel = TransactionCancel::get();
        $customer = Customer::get();
        $field = Field::get();

        if (request()->q != '') {
            $customer = $customer->where(function($q) {
                $q->where('name', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('email', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('phone', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('address', 'LIKE', '%' . request()->q . '%');
            });
        }

        if (request()->status != '') {
            $statuses = explode(',', request()->status);
            if (count($statuses) == 1) {
                $transaction = $transaction->where('status', $statuses[0]);
            } else {
                $transaction = $transaction->whereIn('status', $statuses);
            }
        } else {
            $transaction = $transaction;
        }

        if (request()->q == '') {
            $transaction = $transaction->whereHas('customer', function($query) {
                $query->whereNotNull('id');
            });
        }

        $transaction = $transaction->paginate(10);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.transaction.index', compact('transaction', 'transactionData', 'transactionCancel', 'customer', 'field', 'notification'));
    }

    public function transactionAccept($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['field', 'detail'])->where('invoice', $invoice)->first();

            if ($transaction->status == 0) {
                $transaction->update([
                    'status' => 1
                ]);

                $transaction->detail()->update([
                    'price' => $transaction->price,
                    'amount' => $transaction->price
                ]);
            }

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction accepted successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('transaction.index', $transaction->invoice));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while accepting the transaction</span>'. $e->getMessage())->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }

    public function transactionPlaying($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['field'])->where('invoice', $invoice)->first();

            if ($transaction->status == 1) {
                $transaction->update([
                    'status' => 2
                ]);
            }

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction updated successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('transaction.index', $transaction->invoice));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the transaction</span>')->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }

    public function transactionComplete($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['field', 'detail'])->where('invoice', $invoice)->first();

            if ($transaction->status == 2) {
                $transaction->detail()->update([
                    'date' => $transaction->date,
                    'time' => $transaction->time,
                    'hour' => $transaction->hour
                ]);

                $transaction->update([
                    'time' => null,
                    'status' => 3
                ]);

                $remainingTransactions = Transaction::where('field_id', $transaction->field_id)->whereNotIn('status', [3, 4, 5])->exists();
                if (!$remainingTransactions) {
                    $field = $transaction->field;
                    if ($transaction->day) {
                        $transactionDay = strtolower($transaction->day);

                        $currentDay = Carbon::now()->format('l');

                        $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                        $currentDayIndex = array_search(strtolower($currentDay), $daysOfWeek);
                        $transactionDayIndex = array_search($transactionDay, $daysOfWeek);

                        if ($currentDayIndex > $transactionDayIndex) {
                            $field->update(['condition' => 0]);
                        }
                    }
                }
            } elseif ($transaction->status == 6) {
                $transaction->detail()->update([
                    'day' => $transaction->day,
                    'date' => $transaction->date,
                    'time' => $transaction->time,
                    'hour' => $transaction->hour
                ]);

                $transaction->update([
                    'time' => null,
                    'status' => 3
                ]);

                if ($transaction->member_id) {
                    $member = Member::find($transaction->member_id);
                    if ($member) {
                        $member->update(['status' => 1]);
                        $member->customer()->update(['role' => 0]);
                    }
                }

                $remainingTransactions = Transaction::where('field_id', $transaction->field_id)->whereNotIn('status', [3, 4, 5])->exists();
                if (!$remainingTransactions) {
                    $field = $transaction->field;
                    if ($transaction->day) {
                        $transactionDay = strtolower($transaction->day);

                        $currentDay = Carbon::now()->format('l');

                        $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                        $currentDayIndex = array_search(strtolower($currentDay), $daysOfWeek);
                        $transactionDayIndex = array_search($transactionDay, $daysOfWeek);

                        if ($currentDayIndex > $transactionDayIndex) {
                            $field->update(['condition' => 0]);
                        }
                    }
                }
            }

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction updated successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('transaction.index', $transaction->invoice));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the transaction</span>'. $e->getMessage())->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }

    public function transactionCancel($invoice)
    {
        if (Transaction::where('invoice', $invoice)->exists()) {
            $transaction = Transaction::with(['cancel', 'customer'])->where('invoice', $invoice)->first();
            $notification = Notification::getNotifications();
            return view('backend.main.pages.transaction.cancel', compact('transaction', 'notification'));
        } else {
            return redirect()->back();
        }
    }

    public function transactionCancelStore($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['field', 'cancel', 'detail'])->where('invoice', $invoice)->first();

            if ($transaction->status == 0 || $transaction->status == 4) {
                $transaction->detail()->update([
                    'date' => $transaction->date,
                    'time' => $transaction->time,
                    'hour' => $transaction->hour,
                    'price' => $transaction->price
                ]);

                $transaction->update([
                    'time' => null,
                    'status' => 5
                ]);

                $remainingTransactions = Transaction::where('field_id', $transaction->field_id)->whereNotIn('status', [3, 4, 5])->exists();
                if (!$remainingTransactions) {
                    $transaction->field()->update(['condition' => 0]);
                }

                $cancelTransaction = TransactionCancel::where('transaction_id', $transaction->id)->first();
                if ($cancelTransaction) {
                    $cancelTransaction->update(['status' => 1]);
                }
            }

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction canceled successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('transaction.index', $transaction->invoice));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while canceling the transaction</span>')->hideCloseButton()->width('500px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }

    public function transactionStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'field_id' => 'required|exists:fields,id',
            'date' => 'required',
            'time' => 'required',
            'hour' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $field = Field::find($request->field_id);
            $status = ($request->price == $request->amount) ? 1 : 0;
            $lastTransaction = Transaction::orderBy('id', 'desc')->first();

            $transactionEndTime = Carbon::createFromFormat('H:i', $request->time)->addHours($request->hour);
            if ($transactionEndTime->greaterThan(Carbon::createFromFormat('H:i', '23:00'))) {
                Alert::toast('<span class="px-lg-4 ms-2">Transaction time cannot exceed 23:00</span>')->hideCloseButton()->width('489px')->padding('25px')->toHtml();
                return redirect()->back();
            }

            $existingTransactions = Transaction::where('field_id', $request->field_id)->get();
            foreach ($existingTransactions as $existingTransaction) {
                $existingStartTime = Carbon::createFromFormat('H:i', $existingTransaction->time);
                $existingEndTime = $existingStartTime->copy()->addHours($existingTransaction->hour);

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

            $detail = TransactionDetail::create([
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'amount' => $request->amount
            ]);

            $transaction = Transaction::create([
                'invoice' => $invoice,
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'date' => $request->date,
                'time' => $request->time,
                'hour' => $request->hour,
                'price' => $request->price,
                'status' => $status
            ]);

            $detail->update(['transaction_id' => $transaction->id]);
            $transaction->update(['detail_id' => $detail->id]);

            $field->update([
                'condition' => 1
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction added successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while adding the transaction</span>'. $e->getMessage())->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }

    public function transactionEdit(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transactionData = Transaction::get();
        $customer = Customer::get();
        $field = Field::get();

        $notification = Notification::getNotifications();
        return view('backend.main.pages.transaction.edit', compact('transaction', 'transactionData', 'customer', 'field', 'notification'));
    }

    public function transactionUpdate(Request $request, string $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'hour' => 'required|numeric',
            'price' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::findOrFail($id);
            $status = ($request->price == $request->amount) ? 1 : 0;

            $transaction->update([
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'date' => $request->date,
                'time' => $request->input('time', $request->input('original_time')),
                'hour' => $request->hour,
                'price' => $request->price,
                'status' => $status
            ]);

            $transaction->detail()->update([
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'amount' => $request->amount
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction updated successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the transaction</span>')->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }

    public function transactionDestroy(string $id)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Transaction deleted successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the transaction</span>')->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('transaction.index'));
        }
    }
}
