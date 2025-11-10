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

</style>

<div class="glass-card">
    <h2 class="mb-3">👮‍♂️PNP Dashboard</h2>
    <h4 class="mb-4">📋Assigned Reports</h4>

    <div class="table-responsive glass-table mb-4">
        <div class="mt-3">
    {{ $reports->links('pagination::bootstrap-5') }}
</div>
    @include('reports.assigned')
</div>


<a href="{{ route('reports.generate') }}" class="btn btn-glass">📑 Generate Report</a>

</div>
@endsection
