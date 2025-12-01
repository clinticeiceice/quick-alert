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
use App\Notifications\FireAlertNotification;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Helper: create a notification for a single user with small duplicate prevention.
     * If $reportId is set, we append a tag to message so related notifications can be grouped.
     * 
     * ✅ UPDATED: Now uses broadcast() for real-time notifications (instead of event()).
     * This ensures the frontend can listen and play purge.mp3 for reporter/designated roles.
     */
    private function createNotificationForUser(int $userId, string $message, ?string $role = null, ?int $reportId = null, ?string $designatedTo = "", bool $soundAlert = false, string $soundType = 'purge')
    {
        $tag = $reportId ? " ||report:{$reportId}" : '';

        $exists = Notification::where('user_id', $userId)
            ->where('message', $message . $tag)
            ->whereNull('deleted_at')
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

            
        if (!$exists) {
            // ✅ UPDATED: Include 'report_id' in the create (matches your Notification model fillable)
            $notif = Notification::create([
                'user_id' => $userId,
                'message' => $message . $tag,
                'role' => $role,
                'report_id' => $reportId, // ✅ Added this
            ]);

            $user = User::find($userId);


            if ($user) {
                // ✅ Triggers both DB save and Pusher broadcast
                // event(new \App\Events\PusherTestEvent("hello Wordl!"));
                // event(new NewNotification($notif));
                Log::info('Notification data: ', [$notif, $designatedTo, $soundAlert]);
                $user->notify(new FireAlertNotification($notif, $designatedTo, $soundAlert, $soundType));
            }
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
            $this->createNotificationForUser($designated->id, $message, 'designated', $report->id, $request->get('designated_to'));
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

    public function decline(Report $report)
    {
        return view('reports.decline', compact('report'));
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
            $this->createNotificationForUser($u->id, $message, $report->designated_to, $report->id, $request->get('designated_to'), true, 'sos');
        }

        return redirect()->route('dashboard')->with('success', 'Report approved successfully.');
    }


    public function declineStore(Request $request, Report $report)
    {
        $report->update([
            'status'      => 'declined',
            'approved_by' => Auth::id(),
        ]);

        $message = '🚨 Your report has been declined!';
        $this->createNotificationForUser($report->reporter_id, $message, 'reporter', $report->id, $request->get('designated_to'), false, 'sos');
      
    
        return redirect()->route('dashboard')->with('success', 'Report declined successfully.');
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
                        $report->id, 
                        $request->get('designated_to'),
                        true
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
                $report->id,
                 $request->get('designated_to'),
                true
            );

            $designatedUsers = User::where('role', 'designated')->get();
            foreach ($designatedUsers as $designated) {
                $this->createNotificationForUser(
                    $designated->id,
                    '🔥 BFP confirmed fire (Level ' . $report->level . ').',
                    'designated',
                 $report->id, 
                 $request->get('designated_to'),
                true
                );
            }
            //added
            $rescues = User::where('role', 'rescue')->get();
            foreach ($rescues as $rescue) {
                $this->createNotificationForUser(
                    $rescue->id,
                    '🔥 BFP confirmed fire (Level ' . $report->level . ').',
                    'designated',
                    $report->id, 
                     $request->get('designated_to'),
                true
                );
            }
            $pnps = User::where('role', 'pnp')->get();
            foreach ($pnps as $pnp) {
                $this->createNotificationForUser(
                    $pnp->id,
                    '🔥 BFP confirmed fire (Level ' . $report->level . ').',
                    'designated',
                    $report->id, 
                     $request->get('designated_to'),
                true
                );
            }

        } else {
            // RESCUE or PNP -> notify reporter only
            $this->createNotificationForUser(
                $report->reporter_id,
                '✅ ' . strtoupper($role) . ' accepted your report (Level ' . $report->level . ')',
                'reporter',
                $report->id, 
                 $request->get('designated_to')
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

        $role = $report->designated_to;

        $notificationBodyForReporter = "🔥 Your reported fire incident is now under control (Report ID:  $report->id ), reporter";
        $notificationBodyForOther = "🚒 Fire report #$report->id is now under control (Level $report->level), ";

        if($role != "bfp") {
            $notificationBodyForReporter = "🆘 Your reported incident is now under control or completed (Report ID:  $report->id ), reporter";
            $notificationBodyForOther = "🚨 Incident report #$report->id is now under control or completed (Level $report->level), ";
        }

        // 🔹 Notify the reporter that the fire is under control
        $this->createNotificationForUser(
            $report->reporter_id,
            $notificationBodyForReporter,
            'reporter',
            $report->id, 
             $request->get('designated_to')
        );

        // 🔹 Notify designated personnel
        $designatedUsers = User::where('role', 'designated')->get();
        foreach ($designatedUsers as $designated) {
            $this->createNotificationForUser(
                $designated->id,
                $notificationBodyForOther . "designated",
                'designated',
                $report->id, 
                 $request->get('designated_to')
            );
        }

        if($role == "bfp") {

            //added
            $rescues = User::where('role', 'reporter')->whereNot('id', $report->reporter_id)->get();
            foreach ($rescues as $rescue) {
                $this->createNotificationForUser(
                    $rescue->id,
                    $notificationBodyForReporter,
                    'reporter',
                    $report->id, 
                     $request->get('designated_to')
                );
            }

            $rescues = User::where('role', 'rescue')->get();
            foreach ($rescues as $rescue) {
                $this->createNotificationForUser(
                    $rescue->id,
                    $notificationBodyForOther . "rescue",
                    'rescue',
                    $report->id, 
                     $request->get('designated_to')
                );
            }

            $pnps = User::where('role', 'pnp')->get();
            foreach ($pnps as $pnp) {
               $this->createNotificationForUser(
                   $pnp->id,
                   $notificationBodyForOther . "PNP",
                   'pnp',
                   $report->id, 
                   $request->get('designated_to')
               );
            }
        }

        // if($role != "bfp" && $role != "rescue" && $role != "pnp") {
        //     $bfps = User::where('role', 'bfp')->get();
        //    foreach ($bfps as $bfp) {
        //        $this->createNotificationForUser(
        //            $bfp->id,
        //            $notificationBodyForOther . "BFP",
        //            'bfp',
        //            $report->id, 
        //            $request->get('designated_to')
        //        );
        //    }
        // }

        return redirect()->route('dashboard')->with('success', 'Fire marked as under control.');
    }

    // ...generate / generatePdf code kept as-is (unchanged)
    public function generate()
    {
        $user = auth()->user();
        switch ($user->role) {
            case 'designated':
                $reports = Report::where('status', 'controlled')->latest()->get();
                break;

            case 'rescue':
            case 'pnp':
            case 'bfp':
                $reports = Report::where('designated_to', $user->role)
                    ->where('status', 'controlled')
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
                $reports = Report::where('status', 'controlled')->latest()->get();
                break;

            case 'rescue':
            case 'pnp':
            case 'bfp':
                $reports = Report::where('designated_to', $role)
                    ->where('status', 'controlled')
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
            $report->id, $report->designated_to
        );

        // Notify all rescue users
        $rescueUsers = User::where('role', 'rescue')->get();
        foreach ($rescueUsers as $rescue) {
            $this->createNotificationForUser($rescue->id,
             '🚨 New report assigned to RESCUE team.', 
             'rescue', 
             $report->id, 
             $report->designated_to);
        }
        

        return redirect()->back()->with('success', 'Report approved!');
    }

    public function resolveReport($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'resolved';
        $report->save();

        $this->createNotificationForUser($report->reporter_id, 
        '✅ Your report has been marked as resolved.', 
        'reporter', 
        $report->id, 
         $report->designated_to
        );

        return redirect()->back()->with('success', 'Report resolved!');
    }
}