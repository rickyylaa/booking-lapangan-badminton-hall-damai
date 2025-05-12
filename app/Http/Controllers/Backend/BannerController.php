<?php

namespace App\Http\Controllers\Backend;

use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = Banner::orderBy('id', 'ASC')->paginate(10);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.banner.index', compact('banner', 'notification'));
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
    public function store(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'required|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->title) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/banners', $filename);
            }

            $banner->create([
                'title' => $request->title,
                'image' => $filename,
                'description' => $request->description,
                'status' => $request->status
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Banner added successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('banner.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while adding the banner</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('banner.index'));
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
        $banner = Banner::findOrFail($id);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.banner.edit', compact('banner', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);

            $filename = $banner->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->title) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/banners', $filename);

                if ($banner->image !== 'default.png') {
                    File::delete(storage_path('app/public/banners/' . $banner->image));
                }
            }

            $banner->update([
                'title' => $request->title,
                'image' => $filename,
                'description' => $request->description,
                'status' => $request->status
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Banner updated successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('banner.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the banner</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('banner.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);
            $imagePath = storage_path('app/public/banners/' . $banner->image);
            if ($banner->image !== 'default.png' && File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $banner->delete();

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Banner deleted successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('banner.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the banner</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('banner.index'));
        }
    }
}
