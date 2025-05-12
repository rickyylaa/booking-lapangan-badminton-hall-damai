<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $customer = auth()->guard('customer')->user();
        $transactionData = Transaction::where('customer_id', auth()->guard('customer')->user()->id)->orderBy('created_at', 'DESC')->paginate(100);

        return view('frontend.main.pages.dashboard.index', compact('customer', 'transactionData'));
    }

    public function setting()
    {
        $customer = auth()->guard('customer')->user();
        return view('frontend.main.pages.dashboard.setting', compact('customer'));
    }

    public function settingUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'nullable|string|min:5',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $customer = auth()->guard('customer')->user();

            $filename = $customer->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/customers', $filename);

                if ($customer->image !== 'default.png') {
                    File::delete(storage_path('app/public/customers/' . $customer->image));
                }
            }

            $customer->update([
                'name' => $request->name,
                'password' => bcrypt($request->password),
                'image' => $filename,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 1
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Profile updated successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('customer.setting'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the profile</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('customer.setting'));
        }
    }
}
