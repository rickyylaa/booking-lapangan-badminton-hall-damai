<?php

namespace App\Http\Controllers\Backend;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::orderBy('id', 'ASC')->paginate(10);
        $notification = Notification::getNotifications();
        return view('backend.main.pages.message.index', compact('message', 'notification'));
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $message = Message::findOrFail($id);
            $message->delete();

            DB::commit();

            Alert::toast('<span class="px-lg-4 ms-2">Message deleted successfully</span>')->hideCloseButton()->width('431px')->padding('25px')->toHtml();
            return redirect(route('message.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="px-lg-4 ms-2">An error occurred while deleting the message</span>')->hideCloseButton()->width('600px')->padding('25px')->toHtml();
            return redirect(route('message.index'));
        }
    }
}
