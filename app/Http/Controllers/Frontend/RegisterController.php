<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register()
    {
        if (auth()->guard('customer')->check()) {
            return redirect(route('front.index'));
        }

        return view('frontend.auth.pages.register.index');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email,id',
            'password' => 'required|string|min:8',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ]);

        if (!auth()->guard('customer')->check()) {
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 1
            ]);
        }

        $credentials = $request->only('name', 'email', 'password', 'phone', 'address') + ['status' => 1];

        if (auth()->guard('customer')->attempt($credentials)) {
            return redirect()->intended(route('front.index'));
        }

        return redirect()->back();
    }
}
