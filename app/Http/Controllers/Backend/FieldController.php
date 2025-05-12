<?php

namespace App\Http\Controllers\Backend;

use App\Models\Field;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $field = Field::orderBy('id', 'ASC')->paginate(10);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.field.index', compact('field', 'notification'));
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
    public function store(Request $request, Field $field)
    {
        $request->validate([
            'title' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->title) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/fields', $filename);
            }

            $field->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'price' =>  $request->price,
                'image' => $filename,
                'description' => $request->description,
                'status' => $request->status
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Field added successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('field.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while adding the field</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('field.index'));
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
        $field = Field::findOrFail($id);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.field.edit', compact('field', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            $field = Field::findOrFail($id);

            $filename = $field->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->title) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/fields', $filename);

                if ($field->image !== 'default.png') {
                    File::delete(storage_path('app/public/fields/' . $field->image));
                }
            }

            $field->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'price' =>  $request->price,
                'image' => $filename,
                'description' => $request->description,
                'status' => $request->status
            ]);

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Field updated successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('field.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the field</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('field.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $field = Field::findOrFail($id);
            $imagePath = storage_path('app/public/fields/' . $field->image);
            if ($field->image !== 'default.png' && File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $field->delete();

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Field deleted successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('field.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the field</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('field.index'));
        }
    }
}
