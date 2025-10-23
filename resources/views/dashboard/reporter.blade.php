@extends('layouts.app')

@section('content')
<style>
    /* Glassmorphism styles */
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        padding: 20px;
        max-width: 900px;
        margin: 40px auto;
    }

    .glass-table {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .glass-table th, .glass-table td {
        background: rgba(255, 255, 255, 0.05);
        color: #0a0909;
    }

    .glass-table th {
        font-weight: bold;
        text-transform: uppercase;
    }

    body {
        background: linear-gradient(135deg, #e2e5eb, #73777e);
        min-height: 100vh;
        color: #111010;
    }

    h2, h4 {
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(6px);
        transition: all 0.3s ease;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #000;
    }
</style>

<div class="glass-card">
    <h2 class="mb-4">🆘 Reporter Dashboard</h2>

    <a href="{{ route('reports.create') }}" class="btn btn-glass mb-3">+ Submit New Report</a>

    <h4 class="mt-4">📄 Your Reports</h4>
    <div class="table-responsive glass-table mt-3">
        <table class="table table-bordered table-hover text-white mb-0">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>Level {{ $report->level }}</td>
                    <td>{{ ucfirst($report->status) }}</td>
                    <td>{{ strtoupper($report->designated_to) }}</td>
                    <td>{{ $report->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
    {{ $reports->links('pagination::bootstrap-5') }}
</div>
    </div>

    <h4 class="mb-3">🔔 Notifications</h4>
    <div class="glass-table p-3">
        @include('notifications.index')
    </div>
</div>
@endsection
