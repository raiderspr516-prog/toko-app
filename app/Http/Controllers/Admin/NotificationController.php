<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::guard('admin')->user()->notifications()->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(string $id)
    {
        $notification = Auth::guard('admin')->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back();
    }

    public function markAllRead()
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();

        return back();
    }
}
