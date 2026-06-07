<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: NotificationController
 *
 * Manages user notifications via Inertia.
 * Provides listing, marking as read (single/all), and deletion.
 */
class NotificationController extends Controller
{
    /**
     * Display a listing of the authenticated user's notifications.
     *
     * Returns paginated notifications sorted by most recent first.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->paginate($request->input('per_page', 20))
            ->withQueryString();

        // Mark unread count for the frontend
        $unreadCount = $user->unreadNotifications()->count();

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Mark a specific notification as read.
     *
     * Accepts the notification ID in the request body.
     * Returns the updated unread count for Inertia reactivity.
     */
    public function markAsRead(string $id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification && $notification->unread()) {
            $notification->markAsRead();
        }

        $unreadCount = $user->unreadNotifications()->count();

        return back()->with([
            'success'     => 'Notificación marcada como leída.',
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark all unread notifications as read.
     *
     * Marks every unread notification for the authenticated user as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }

    /**
     * Delete a specific notification.
     *
     * Removes the notification from the database permanently.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (! $notification) {
            return back()->with('error', 'Notificación no encontrada.');
        }

        $notification->delete();

        return back()->with('success', 'Notificación eliminada exitosamente.');
    }
}
