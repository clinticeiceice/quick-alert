<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\FireNotificationService;
use App\Events\NewNotification;

class ReportController extends Controller
{
    /**
     * Helper: create a notification for a single user with small duplicate prevention.
     * If $reportId is set, we append a tag to message so related notifications can be grouped.
     */
    private function createNotificationForUser(int $userId, string $message, ?string $role = null, ?int $reportId = null)
{
    $tag = $reportId ? " ||report:{$reportId}" : '';

    $exists = Notification::where('user_id', $userId)
        ->where('message', $message . $tag)
        ->whereNull('deleted_at')
        ->where('created_at', '>=', now()->subMinutes(2))
        ->exists();

    if (!$exists) {
        $notif = Notification::create([
            'user_id' => $userId,
            'message' => $message . $tag,
            'role'    => $role,
        ]);

        // ✅ broadcast the new notification
        event(new NewNotification($notif));
    }
}

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'level' => 'required|in:1,2,3',
            'description' => 'required|string',
            'designated_to' => 'required|in:rescue,pnp,bfp',
        ]);

        // Save the report and set reporter_id
        $report = Report::create([
            'reporter_id'   => auth()->id(),
            'level'         => $request->level,
            'description'   => $request->description,
            'status'        => 'pending',
            'designated_to' => $request->designated_to,
            'reported_at'   => now(),
        ]);

        // Notify ALL designated personnel
        $designatedUsers = User::where('role', 'designated')->get();
        $message = '📢 New report submitted by ' . auth()->user()->name . ' (Level ' . $report->level . ')';
       
        foreach ($designatedUsers as $designated) {
            $this->createNotificationForUser($designated->id, $message, 'designated', $report->id);
        }
         

        return redirect()->route('dashboard')->with('success', 'Report submitted successfully!');
    }

    public function index()
    {
        $reports = Report::where('status', 'pending')->get();
        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    public function approve(Report $report)
    {
        return view('reports.approve', compact('report'));
    }

    public function approveStore(Request $request, Report $report)
    {
        $report->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Notify the assigned group (rescue/pnp/bfp) - create per-user notifications
        $assignedUsers = User::where('role', $report->designated_to)->get();
        $message = '🚨 New report assigned to ' . strtoupper($report->designated_to) . ' (Level ' . $report->level . ')';
       
        foreach ($assignedUsers as $u) {
            $this->createNotificationForUser($u->id, $message, $report->designated_to, $report->id);
        }

        return redirect()->route('dashboard')->with('success', 'Report approved successfully.');
    }

    /**
     * Accept report (Rescue / PNP / BFP)
     *
     * Behavior:
     * - If RESCUE or PNP accepts -> notify reporter only.
     * - If BFP accepts -> notify reporter AND designated personnel. Notification persists until BFP clicks "Fire Under Control".
     */
    public function accept(Request $request, Report $report)
{
    $user = Auth::user();
    $role = $user->role; // rescue, pnp, or bfp

    // Update report status and who accepted it
    $report->update([
        'status' => 'accepted',
        'accepted_by' => $user->id,
        'accepted_at' => now(),
    ]);

    if ($role === 'bfp') {
        /**
         * ✅ FIX 1 — Prevent duplicate notifications
         * Check if this report already has "BFP accepted" type notification recently.
         */
        $exists = Notification::where('role', 'bfp')
            ->where('message', 'like', '%BFP accepted report%')
            ->where('message', 'like', "%report:{$report->id}%")
            ->exists();

        if (!$exists) {
            // Notify other BFP users for visibility
            $bfpUsers = User::where('role', 'bfp')->get();
            foreach ($bfpUsers as $bfpUser) {
                $this->createNotificationForUser(
                    $bfpUser->id,
                    '🔥 BFP accepted report (Level ' . $report->level . ')',
                    'bfp',
                    $report->id
                );
            }
        }

        /**
         * ✅ FIX 2 — Notify reporter and designated personnel once
         * "BFP confirmed fire" messages appear on their dashboards.
         */
        $this->createNotificationForUser(
            $report->reporter_id,
            '🔥 BFP confirmed fire for your report (Level ' . $report->level . ')',
            'reporter',
            $report->id
        );

        $designatedUsers = User::where('role', 'designated')->get();
        foreach ($designatedUsers as $designated) {
            $this->createNotificationForUser(
                $designated->id,
                '🔥 BFP confirmed fire (Level ' . $report->level . ').',
                'designated',
                $report->id
            );
        }
    } else {
        // RESCUE or PNP -> notify reporter only
        $this->createNotificationForUser(
            $report->reporter_id,
            '✅ ' . strtoupper($role) . ' accepted your report (Level ' . $report->level . ')',
            'reporter',
            $report->id
        );
    }

    return redirect()->route('dashboard')->with('success', ucfirst($role) . ' accepted the report.');
}
public function markUnderControl(Request $request, Report $report)
{
    // 🔹 Update the report status properly with quotes
    $report->update([
        'status' => 'controlled',
        'controlled_at' => now(),
    ]);

    // 🔹 Notify the reporter that the fire is under control
    $this->createNotificationForUser(
        $report->reporter_id,
        '🔥 Your reported fire incident is now under control (Report ID: ' . $report->id . ')',
        'reporter',
        $report->id
    );

    // 🔹 Notify designated personnel
    $designatedUsers = User::where('role', 'designated')->get();
    foreach ($designatedUsers as $designated) {
        $this->createNotificationForUser(
            $designated->id,
            '🚒 Fire report #' . $report->id . ' is now under control (Level ' . $report->level . ').',
            'designated',
            $report->id
        );
    }

    return redirect()->route('dashboard')->with('success', 'Fire marked as under control.');
}

    // ...generate / generatePdf code kept as-is (unchanged)
    public function generate()
    {
        $user = auth()->user();
        switch ($user->role) {
            case 'designated':
                $reports = Report::where('status', 'accepted')->latest()->get();
                break;

            case 'rescue':
            case 'pnp':
            case 'bfp':
                $reports = Report::where('designated_to', $user->role)
                    ->where('status', 'accepted')
                    ->latest()
                    ->get();
                break;

            default:
                return redirect()->route('dashboard');
        }

        return view('reports.generate', compact('reports'));
    }

    public function generatePdf()
    {
        $user = Auth::user();
        $role = $user->role;

        switch ($role) {
            case 'designated':
                $reports = Report::where('status', 'accepted')->latest()->get();
                break;

            case 'rescue':
            case 'pnp':
            case 'bfp':
                $reports = Report::where('designated_to', $role)
                    ->where('status', 'accepted')
                    ->latest()
                    ->get();
                break;

            default:
                $reports = collect();
        }

        $pdf = Pdf::loadView('reports.pdf', compact('reports', 'role'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("{$role}_reports.pdf");
    }

    // convenience helpers used earlier in your code (keeping them consistent)
    public function approveReport($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'approved';
        $report->save();

        // Notify reporter
        $this->createNotificationForUser(
            $report->reporter_id,
            '✅ Your report has been approved and assigned to RESCUE.',
            'reporter',
            $report->id
        );

        // Notify all rescue users
        $rescueUsers = User::where('role', 'rescue')->get();
        foreach ($rescueUsers as $rescue) {
            $this->createNotificationForUser($rescue->id, '🚨 New report assigned to RESCUE team.', 'rescue', $report->id);
        }
        

        return redirect()->back()->with('success', 'Report approved!');
    }

    public function resolveReport($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'resolved';
        $report->save();

        $this->createNotificationForUser($report->reporter_id, '✅ Your report has been marked as resolved.', 'reporter', $report->id);

        return redirect()->back()->with('success', 'Report resolved!');
    }
}
