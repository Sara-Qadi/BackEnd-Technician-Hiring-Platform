<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index($userId)
    {
        $notifications = $this->notificationService->getNotificationsByUser($userId);

        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|integer|exists:users,user_id',
            'type'        => 'required|string',
            'message'     => 'required|string',
            'read_status' => 'required|in:unread,read',
        ]);

        $notification = $this->notificationService->createNotification($data);

        return response()->json($notification, 201);
    }

    public function markAsRead($notificationId)
    {
        $success = $this->notificationService->markAsRead($notificationId);

        if (! $success) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function destroy($notificationId)
    {
        $success = $this->notificationService->deleteNotification($notificationId);

        if (! $success) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        return response()->json(['message' => 'Notification deleted']);
    }
}
