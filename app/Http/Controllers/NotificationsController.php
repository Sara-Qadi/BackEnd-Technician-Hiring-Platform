<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index($userId)
    {
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('notification_id', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->read_status = 'read';
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function destroy($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
}
