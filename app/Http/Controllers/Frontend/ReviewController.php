<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Field;
use App\Models\Review;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ReviewController extends Controller
{
    public function reviewStore(Request $request, $slug)
    {
        $this->validate($request, [
            'rate' => 'required|numeric|min:1',
            'review' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $field = Field::getFieldBySlug($slug);

            if (!$field) {
                return redirect()->back()->with('error', 'Field tidak ditemukan.');
            }

            Review::create([
                'customer_id' => auth()->guard('customer')->user()->id,
                'field_id' => $field->id,
                'rate' => $request->rate,
                'comment' => $request->review
            ]);

            DB::commit();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while updating the profile</span>'. $e->getMessage())->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function reviewDestroy(string $id)
    {
        try {
            DB::beginTransaction();

            $review = Review::findOrFail($id);
            $review->delete();

            DB::commit();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the comment</span>')->hideCloseButton()->width('580px')->padding('25px')->toHtml();
            return redirect()->back();
        }
    }
}
