<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Get all notifications for a user ordered by newest first.
     */
    public function getNotificationsByUser(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('notification_id', 'desc')
            ->get();
    }

    /**
     * Create a new notification.
     */
    public function createNotification(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        if (! $notification) {
            return false;
        }

        $notification->read_status = 'read';
        $notification->save();

        return true;
    }

    /**
     * Delete a notification.
     */
    public function deleteNotification(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        if (! $notification) {
            return false;
        }

        $notification->delete();

        return true;
    }

    public function getUnreadNotificationsByUser($userId)
{
    return \App\Models\Notification::where('user_id', $userId)
        ->where('read_status', 'unread')
        ->get();
}

}
