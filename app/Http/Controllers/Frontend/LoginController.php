<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function login()
    {
        if (auth()->guard('customer')->check()) {
            return redirect(route('front.index'));
        }

        return view('frontend.auth.pages.login.index');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $customer = \App\Models\Customer::where('email', $request->email)->first();

        if ($customer && $customer->status == 1 && password_verify($request->password, $customer->password)) {
            auth()->guard('customer')->login($customer);
            return redirect()->intended(route('front.index'));
        } elseif ($customer && $customer->status == 0) {
            Alert::toast('<span class="px-lg-4 ms-2">Account is inactive. Please contact support.</span>')->hideCloseButton()->width('526px')->padding('25px')->toHtml();
        } else {
            Alert::toast('<span class="px-lg-4 ms-2">Email or Password is Incorrect</span>')->hideCloseButton()->width('416px')->padding('25px')->toHtml();
        }

        return redirect()->back();
    }

    public function logout()
    {
        auth()->guard('customer')->logout();
        return redirect()->intended(route('front.index'));
    }
}
