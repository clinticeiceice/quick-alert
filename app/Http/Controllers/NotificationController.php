<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    /**
     * Show current user's notifications (not soft-deleted).
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification (and related ones for the same report if present) as "Fire Under Control".
     * Only BFP users should be allowed to clear fire notifications.
     */
    public function markAsControlled($reportId)
{
    $user = Auth::user();

    // ✅ Only BFP can mark as controlled
    if ($user->role !== 'bfp') {
        abort(403, 'Unauthorized action.');
    }

    $report = \App\Models\Report::findOrFail($reportId);

    // ✅ Skip if already marked as controlled
    if ($report->status === 'controlled') {
        return redirect()->back()->with('info', 'This fire has already been marked as under control.');
    }

    // ✅ Update report status
    $report->update([
        'status' => 'controlled',
    ]);

    // 🧠 Check if similar notifications already exist
    $existing = Notification::where('message', 'like', '%Fire under control as reported by BFP%')
        ->where('message', 'like', "%report:{$reportId}%")
        ->exists();

    if (!$existing) {
        // ✅ Send notification only once per user
        $recipients = \App\Models\User::whereIn('role', ['reporter', 'designated'])->get();

        foreach ($recipients as $recipient) {
            $notification = Notification::create([
                'user_id' => $recipient->id,
                'message' => '✅ Fire under control as reported by BFP ||report:' . $reportId,
                'role' => $recipient->role,
            ]);

            event(new \App\Events\NewNotification($notification)); // pass model, not array
        }
    }

    // 🧹 Delete previous “BFP confirmed fire” notifications for this report
    Notification::where('message', 'like', '%BFP confirmed fire%||report:' . $reportId)
        ->delete();

    return redirect()->back()->with('success', '🔥 Fire marked as under control. Notifications sent.');
}




    /**
     * Soft-delete a single notification (alias).
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        // Only the owner or BFP can delete (optional)
        if ($notification->user_id !== auth()->id() && auth()->user()->role !== 'bfp') {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification removed.');
    }

    /**
     * Mark all notifications for current user as read (sets read_at).
     * This returns JSON (useful for AJAX) or redirect depending on request type.
     */
    public function readAll(Request $request)
    {
        $userId = auth()->id();

        Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a single notification as read (optional).
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        return redirect()->back();
    }
    public function markAllAsRead()
{
    Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return redirect()->back()->with('success', 'All notifications marked as read.');
}
//added new
public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Save notification
        $notification = Notification::create([
            'message' => $request->message,
            'status' => 'unread',
        ]);

        // Broadcast to specific roles
        $users = User::whereIn('role', ['reporter', 'designated'])->get();

        foreach ($users as $user) {
            $notificationData = [
                'id' => $notification->id,
                'message' => $notification->message,
                'user_id' => $user->id,
                'created_at' => $notification->created_at,
            ];

            event(new \App\Events\NewNotification((object) $notificationData));
        }

        return response()->json(['success' => true]);
    }

}
