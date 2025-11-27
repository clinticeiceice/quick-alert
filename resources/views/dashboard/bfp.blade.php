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
    max-width: 1000px;
    margin: 40px auto;
    color: #111010;
}

body {
    background: linear-gradient(135deg, #e2e5eb, #73777e);
    min-height: 100vh;
    font-family: 'Nunito', sans-serif;
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

/* Action buttons */
.action-buttons .btn {
    margin-bottom: 5px;
    white-space: nowrap;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .glass-card {
        margin: 20px;
        padding: 15px;
    }
    .btn-glass {
        width: 100%;
    }
    .table-glass th, .table-glass td {
        font-size: 0.85rem;
    }
}

@media (max-width: 576px) {
    .table-glass thead {
        display: none;
    }
    .table-glass tbody tr {
        display: block;
        margin-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        padding: 10px;
    }
    .table-glass tbody td {
        display: flex;
        justify-content: space-between;
        padding: 6px 10px;
        flex-wrap: wrap;
    }
    .table-glass tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        text-transform: uppercase;
        flex: 1 0 45%;
        margin-right: 5px;
    }
    .action-buttons .btn {
        flex: 1 0 100%;
    }
}
</style>

<div class="glass-card mt-5">

    <h2 class="mb-4">🚒 BFP Dashboard</h2>
    <h4 class="mb-3">📋 Reported Fire Incidents</h4>

    {{-- Generate Report Button --}}
    <div class="text-end mb-3">
        <a href="{{ route('reports.generate') }}" class="btn btn-glass btn-sm">📑 Generate Report</a>
    </div>

    <div class="table-responsive table-glass">
        <table class="table table-bordered table-hover mb-0 text-white">
            <thead class="table-danger text-center">
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date Reported</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td data-label="#"> {{ $loop->iteration }} </td>
                    <td data-label="Reporter"> {{ $report->reporter->name ?? 'N/A' }} </td>
                    <td data-label="Description"> {{ $report->description ?? 'No description' }} </td>
                    <td data-label="Status" class="d-flex justify-content-start align-items-center">
                        @switch($report->status)
                            @case('approved')
                                <span class="badge bg-warning text-dark">Approved</span>
                                @break
                            @case('accepted')
                                <span class="badge bg-success">Accepted</span>
                                @break
                            @case('controlled')
                                <span class="badge bg-primary">Under Control</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($report->status ?? 'Pending') }}</span>
                        @endswitch
                    </td>
                    <td data-label="Date Reported" class="d-flex justify-content-end"> {{ $report->created_at->format('M d, Y h:i A') }} </td>
                    <td data-label="Actions" class="action-buttons d-flex flex-wrap justify-content-end gap-1">
                        @if(auth()->user()->role === 'bfp' && $report->status === 'approved')
                            <form action="{{ route('reports.accept', $report->id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">✅ Accept Report</button>
                            </form>
                        @endif

                        @if(auth()->user()->role === 'bfp' && $report->status === 'accepted')
                            <form action="{{ route('reports.underControl', $report->id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">🔥 Fire Under Control</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $reports->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
