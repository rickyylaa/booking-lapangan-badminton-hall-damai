<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $notification = Notification::getNotifications();
        return view('backend.main.pages.profile.index', compact('user', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username,'. $id,
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'image' => 'nullable|string|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $filename = $user->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/admins', $filename);

                if ($user->image !== 'default.png') {
                    File::delete(storage_path('app/public/users/' . $user->image));
                }
            }

            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'image' => $filename,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Profile updated successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('profile.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the profile</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('profile.index'));
        }
    }
}
