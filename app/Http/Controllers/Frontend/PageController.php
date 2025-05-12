<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Field;
use App\Models\Member;
use App\Models\Message;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class PageController extends Controller
{
    public function schedule()
    {
        $field = Field::get();
        $member = Member::get();
        $customer = Customer::get();
        $transaction = Transaction::get();

        return view('frontend.main.pages.front.schedule', compact('field', 'member', 'customer', 'transaction'));
    }

    public function contact()
    {
        return view('frontend.main.pages.front.contact');
    }

    public function contactStore(Request $request, Message $message)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|numeric',
            'message' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $message->create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Message sended successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('front.contact'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while sending the message</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('front.contact'));
        }
    }
}
