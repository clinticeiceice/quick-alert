<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::latest()->get();
        $notifications = Notification::where('role', $user->role)
                            ->where('is_read', false)
                            ->latest()
                            ->get();

        switch ($user->role) {
            case 'reporter':
                $reports = $user->reports()->latest()->paginate(5); // paginate reporter's reports
                return view('dashboard.reporter', compact('reports', 'notifications'));

            case 'designated':
                $reports = Report::where('status', 'pending')->latest()->paginate(5); // paginate pending
                return view('dashboard.designated', compact('reports', 'notifications'));

            case 'rescue':
                $reports = Report::where('designated_to', 'rescue')
                                ->whereIn('status', ['approved', 'accepted'])
                                ->latest()
                                ->paginate(5); // paginate rescue reports
                return view('dashboard.rescue', compact('reports', 'notifications'));

            case 'pnp':
                $reports = Report::where('designated_to', 'pnp')
                                ->whereIn('status', ['approved', 'accepted'])
                                ->latest()
                                ->paginate(5); // paginate pnp reports
                return view('dashboard.pnp', compact('reports', 'notifications'));

           case 'bfp':
    $reports = Report::where('designated_to', 'bfp')
                    ->whereIn('status', ['approved', 'accepted', 'controlled'])
                    ->latest()
                    ->paginate(5);
    return view('dashboard.bfp', compact('reports', 'notifications'));
            default:
                return redirect()->route('login');
        }
    }
}
