<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Field;
use App\Models\Member;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class MemberController extends Controller
{
    public function member()
    {
        $member = Member::orderBy('id', 'DESC');
        $memberData = Member::get();
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
                $member = $member->where('status', $statuses[0]);
            } else {
                $member = $member->whereIn('status', $statuses);
            }
        } else {
            $member = $member;
        }

        if (request()->q == '') {
            $member = $member->whereHas('customer', function($query) {
                $query->whereNotNull('id');
            });
        }

        $member = $member->paginate(10);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.member.index', compact('member', 'memberData', 'customer', 'field', 'notification'));
    }

    public function memberAccept($id)
    {
        try {
            DB::beginTransaction();

            $member = Member::with(['field'])->where('id', $id)->first();
            $transaction = Transaction::find($member->transaction_id);

            if ($member->status == 0) {
                $member->update([
                    'amount' => $member->price,
                ]);

                $transaction->detail->update([
                    'amount' => $member->price
                ]);
            }

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Member accepted successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('member.index', $member->id));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while accepting the member</span>'. $e->getMessage())->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        }
    }

    public function memberStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'field_id' => 'required|exists:fields,id',
            'day' => 'required',
            'hour' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $field = Field::find($request->field_id);
            $customer = Customer::find($request->customer_id);
            $status = ($request->price == $request->amount) ? 1 : 0;

            $lastTransaction = Transaction::orderBy('id', 'DESC')->first();
            $lastInvoiceNumber = $lastTransaction ? substr($lastTransaction->invoice, 4) : 0;
            $invoiceNumber = str_pad($lastInvoiceNumber + 1, 3, '0', STR_PAD_LEFT);
            $invoice = 'INV-' . $invoiceNumber;

            $selectedDay = strtolower($request->day);
            $selectedDate = Carbon::now()->startOfWeek()->next($selectedDay);
            $meetingDates = [];
            for ($i = 0; $i < 4; $i++) {
                $meetingDates[] = $selectedDate->format('d F Y');
                $selectedDate->addWeek();
            }
            $meetingDatesString = implode(', ', $meetingDates);

            $detail = TransactionDetail::create([
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'price' => $request->price,
                'amount' => $request->amount
            ]);

            $transaction = Transaction::create([
                'invoice' => $invoice,
                'detail_id' => $detail->id,
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'day' => $request->day,
                'date' => $meetingDatesString,
                'time' => $request->time,
                'hour' => $request->hour,
                'price' => $request->price,
                'status' => 6
            ]);

            $member = Member::create([
                'transaction_id' => $transaction->id,
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'day' => $request->day,
                'date' => $meetingDatesString,
                'time' => $request->time,
                'hour' => $request->hour,
                'price' => $request->price,
                'amount' => $request->amount,
                'status' => $status
            ]);

            $detail->update(['transaction_id' => $transaction->id, 'member_id' => $member->id]);
            $transaction->update(['member_id' => $member->id, 'day' => $member->day]);
            $field->update(['condition' => 1]);
            $customer->update(['role' => 1]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Member added successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while adding the member</span>'. $e->getMessage())->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        }
    }

    public function memberUpdate(Request $request, string $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'field_id' => 'required|exists:fields,id',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'hour' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $member = Member::findOrFail($id);
            $transaction = Transaction::findOrFail($id);

            $field = Field::find($request->field_id);

            $time_start = $request->filled('time_start') ? $request->time_start : $transaction->time_start;
            $time_end = $request->filled('time_end') ? $request->time_end : $transaction->time_end;

            $transaction->update([
                'invoice' => 'INV' . '-' . rand(0,99999),
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'day' => $request->day,
                'time_start' => $time_start,
                'time_end' => $time_end,
                'hour' => $request->hour,
                'price' => $request->price,
                'amount' => $request->amount
            ]);

            $member->update([
                'transaction_id' => $transaction->id,
                'customer_id' => $request->customer_id,
                'field_id' => $request->field_id,
                'day' => $request->day,
                'time_start' => $time_start,
                'time_end' => $time_end,
                'hour' => $request->hour,
                'price' => $request->price,
                'amount' => $request->amount
            ]);

            $transaction->update(['member_id' => $member->id]);

            $field->update([
                'condition' => 1
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Member updated successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the member</span>')->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        }
    }

    public function memberDestroy(string $id)
    {
        try {
            DB::beginTransaction();

            $member = Member::findOrFail($id);
            $member->delete();

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Member deleted successfully</span>')->hideCloseButton()->width('451px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the member</span>')->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect(route('member.index'));
        }
    }
}
