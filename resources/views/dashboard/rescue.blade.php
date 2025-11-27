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
    padding: 20px 30px;
    max-width: 900px;
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
    margin-bottom: 15px;
}

.glass-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.glass-table th, .glass-table td {
    background: rgba(255, 255, 255, 0.05);
    color: #0a0909;
    font-size: 0.95rem;
    padding: 10px;
}

.glass-table th {
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

/* Mobile responsive */
@media (max-width: 768px) {
    .glass-card {
        padding: 15px 20px;
        margin: 20px;
    }
    .btn-glass, .btn {
        width: 100%;
        margin-bottom: 8px;
    }
    .glass-table th, .glass-table td {
        font-size: 0.85rem;
        padding: 8px;
    }
}

@media (max-width: 576px) {
    .glass-table thead {
        display: none; /* hide table headers */
    }
    .glass-table tbody tr {
        display: block;
        margin-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        padding: 10px;
    }
    .glass-table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 6px 10px;
    }
    .glass-table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        text-transform: uppercase;
        flex: 1;
    }
    .btn-glass, .btn {
        width: 100%; /* two buttons side by side */
        margin-bottom: 5px;
    }
    .btn-glass.full-width {
        width: 100%; /* for single buttons */
    }
}
</style>

<div class="glass-card mt-5">
    <h2 class="mb-3">🚑 Rescue Dashboard</h2>
    <h4 class="mb-4">📋 Assigned Reports</h4>

    <div class="table-responsive">
        <table class="table glass-table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reporter</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td data-label="ID">{{ $report->id }}</td>
                    <td data-label="Reporter">{{ $report->reporter->name }}</td>
                    <td data-label="Level">Level {{ $report->level }}</td>
                    <td data-label="Status">{{ ucfirst($report->status) }}</td>
                <td data-label="Action" class="d-flex justify-content-end">
    @if($report->status == 'approved')
        <form action="{{ route('reports.accept', $report) }}" method="POST">
            @csrf
            <button class="btn btn-glass text-nowrap">Accept Report</button>
        </form>
    @elseif($report->status == 'accepted')
        <form action="{{ route('reports.underControl', $report) }}" method="POST">
            @method('PUT')
            @csrf
            <button class="btn btn-danger text-nowrap">Report Complete</button>
        </form>
    @else
        <span class="badge bg-success">Accepted</span>
    @endif
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $reports->links('pagination::bootstrap-5') }}
    </div>

    <a href="{{ route('reports.generate') }}" class="btn btn-glass mt-3 full-width">📑 Generate Report</a>
</div>
@endsection
