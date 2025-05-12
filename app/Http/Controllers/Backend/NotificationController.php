<?php

namespace App\Http\Controllers\Backend;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');
        Notification::where('id', $notificationId)->update(['is_read' => 1]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', 0)->update(['is_read' => 1]);
        return response()->json(['success' => true]);
    }
}
