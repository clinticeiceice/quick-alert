@extends('layouts.app')

@section('content')
<style>
    /* Facebook-inspired mobile layout styles */
    body {
        background: #f0f2f5; /* Facebook's light gray background */
        min-height: 100vh;
        color: #1c1e21; /* Dark text for readability */
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; /* System font like Facebook */
    }

    .fb-header {
        background: #1877f2; /* Facebook blue */
        color: white;
        padding: 12px 16px;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .fb-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .fb-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        margin: 16px;
        padding: 16px;
        max-width: none; /* Full width on mobile */
    }

    .fb-btn {
        background: #1877f2;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }

    .fb-btn:hover {
        background: #166fe5;
    }

    .fb-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    .fb-table th, .fb-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e4e6ea;
    }

    .fb-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #1c1e21;
    }

    .fb-table td {
        color: #1c1e21;
    }

    .fb-notifications {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        margin: 16px;
        padding: 16px;
    }

    /* Mobile-specific adjustments */
    @media (max-width: 768px) {
        .fb-table {
            display: block;
            overflow-x: auto;
        }

        .fb-card {
            margin: 8px;
            padding: 12px;
        }

        .fb-header {
            padding: 10px 12px;
        }

        .fb-header h2 {
            font-size: 18px;
        }
    }

    /* Hide table headers on very small screens and make it list-like */
    @media (max-width: 480px) {
        .fb-table thead {
            display: none;
        }

        .fb-table tbody tr {
            display: block;
            border: 1px solid #e4e6ea;
            border-radius: 8px;
            margin-bottom: 8px;
            padding: 12px;
        }

        .fb-table td {
            display: block;
            border: none;
            padding: 4px 0;
        }

        .fb-table td:before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: #65676b;
        }
    }
</style>
<div class="dashboard-container mt-5">
    <div class="fb-header">
        <h2>🆘 Reporter Dashboard</h2>
    </div>
    <div class="fb-card">
        <a href="{{ route('reports.create') }}" class="fb-btn mb-3">+ Submit New Report</a>
        <h4 class="mt-4">📄 Your Reports</h4>
        <div class="table-responsive">
            <table class="fb-table">
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
                        <td data-label="Level">Level {{ $report->level }}</td>
                        <td data-label="Status">{{ ucfirst($report->status) }}</td>
                        <td data-label="Assigned To">{{ strtoupper($report->designated_to) }}</td>
                        <td data-label="Date">{{ $report->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>


{{-- <div class="fb-notifications">
    <h4 class="mb-3">🔔 Notifications</h4>
    @include('notifications.index')
</div> --}}
@endsection