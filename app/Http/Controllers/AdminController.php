<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'reporters' => User::where('role', 'reporter')->count(),
            'designated' => User::where('role', 'designated')->count(),
            'pending' => User::where('is_approved', false)->count(),
            'reports' => Report::count(),
        ]);
    }

    public function pending()
    {
        $users = User::where('role', 'reporter')
            ->where('is_approved', false)
            ->paginate(10);

        return view('admin.pending', compact('users'));
    }

    public function list(Request $request)
    {
        $users = User::when($request->has('filter'), function ($query) use($request){
            $query->where('role', $request->get('filter'));
        })
        ->latest()
            ->paginate(10);

        return view('admin.list', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);

        try {
            if($user->role == 'reporter'){
                Mail::send('emails.confirm-approved', [
                        'reporter' => $user,
                        'loginUrl' => route('login'),
                    ], function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Your Reporter Account Has Been Approved');
                });

            }
        } catch (\Throwable $th) {
            // ignore here
        }
        return back()->with('success', 'User approved successfully.');
    }
//new
    public function allreport()
    {
        $reports = Report::all();

    return view('admin.allreports', compact('reports'));
    }

    public function create()
    {
        return view('admin.create-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|digits:11',
            'role' => 'required|in:reporter,designated',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_approved' => true,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'User created successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::firstOrFail($id);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
