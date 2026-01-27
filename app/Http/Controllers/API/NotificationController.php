<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        $notifications = Notification::where('utilisateur_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $unreadCount = Notification::where('utilisateur_id', $request->user()->id)
            ->where('est_lue', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Get unread count only
     */
    public function unreadCount(Request $request)
    {
        $count = Notification::where('utilisateur_id', $request->user()->id)
            ->where('est_lue', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('utilisateur_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('utilisateur_id', $request->user()->id)
            ->where('est_lue', false)
            ->update([
                'est_lue' => true,
                'date_lecture' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Toutes les notifications ont été marquées comme lues'
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, $id)
    {
        $notification = Notification::where('utilisateur_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée'
        ]);
    }
}
