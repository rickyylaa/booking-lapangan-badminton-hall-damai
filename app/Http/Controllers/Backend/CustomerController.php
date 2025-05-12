<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::orderBy('id', 'ASC')->paginate(10);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.customer.index', compact('customer', 'notification'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:customers,email',
            'password' => 'required|string',
            'image' => 'required|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/customers', $filename);
            }

            $customer->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'image' => $filename,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 1
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Customer added successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('customer.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while adding the customer</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('customer.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.customer.edit', compact('customer', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:customers,email,' .$id,
            'password' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($id);

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
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'image' => $filename,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 1
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Customer updated successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('customer.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the customer</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('customer.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($id);
            $imagePath = storage_path('app/public/customers/' . $customer->image);
            if ($customer->image !== 'avatar.gif' && File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $customer->delete();

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Customer deleted successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('customer.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the customer</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('customer.index'));
        }
    }
}
