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

    @media (max-width: 768px) {
        .glass-card {
            margin: 20px;
            padding: 15px;
        }
        .btn-glass {
            width: 100%; /* Buttons take full width on mobile */
            margin-bottom: 10px;
        }
        .table-glass th, .table-glass td {
            font-size: 0.85rem; /* Smaller font on mobile */
        }
    }

    body {
        background: linear-gradient(135deg, #e2e5eb, #73777e);
        min-height: 100vh;
        color: #111010;
    }

    h2, h4 {
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

    .table-glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .table-glass th, .table-glass td {
        background: rgba(255, 255, 255, 0.05);
        color: #111010;
    }

    .table-glass th {
        font-weight: bold;
        text-transform: uppercase;
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

    /* Optional: stacked table for mobile */
    @media (max-width: 576px) {
        .table-glass thead {
            display: none;
        }
        .table-glass tbody tr {
            display: block;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .table-glass tbody td {
            display: flex;
            justify-content: space-between;
            padding: 8px 10px;
        }
        .table-glass tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            flex: 1;
            text-transform: uppercase;
        }
    }
    /* Make action buttons stacked */
.action-buttons a {
    display: block;
    width: 100%;
    margin-bottom: 5px;
}

/* Optional: reduce width on larger screens if you want smaller buttons side by side */
@media (min-width: 768px) {
    .action-buttons a {
        width: auto;
        display: inline-block;
    }
}
</style>

<div class="glass-card mt-5">

    <h2 class="mb-4">🧑‍💼 Designated Personnel Dashboard</h2>

    <h4 class="mb-3">📋 Pending Reports</h4>

    <div class="table-responsive table-glass">
        <div class="text-end mb-3">
            <a href="{{ route('reports.generate') }}" class="btn btn-glass">📑 Generate Report</a>
        </div>

        <table class="table table-bordered table-hover mb-0 text-white">
            <thead>
                <tr>
                    <th>Reporter</th>
                    <th>Level</th>
                    <th>Reporter Phone Number</th>
                    <th>Designated To</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td data-label="Reporter">{{ $report->reporter->name }}</td>
                    <td data-label="Level">Level {{ $report->level }}</td>
                    <td data-label="Phone">
                        <a href="tel:{{ $report->reporter->phone_number }}" class="text-decoration-none text-primary">
                            {{ $report->reporter->phone_number }}
                        </a>
                    </td>
                    <td data-label="Designated To">{{ strtoupper($report->designated_to) }}</td>
                    <td data-label="Action" class="action-buttons">
                    </td>
                    <td>
    <a href="{{ route('reports.show', $report) }}" class="btn btn-glass btn-sm me-2">View</a>
    <a href="{{ route('reports.approve', $report) }}" class="btn btn-glass btn-sm">Approve</a>
    <a href="{{ route('reports.decline', $report) }}" class="btn btn-glass btn-sm">Decline</a>
</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $reports->links('pagination::bootstrap-5') }}
        </div>

    </div>

    {{-- <h4 class="mb-3">🔔 Notifications</h4>
    <div class="table-glass p-3">
        @include('notifications.index')
    </div> --}}

</div>
@endsection
