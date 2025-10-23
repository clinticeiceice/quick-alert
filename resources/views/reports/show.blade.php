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
        max-width: 700px;
        margin: 40px auto;
        color: #111010;
    }

    body {
        background: linear-gradient(135deg, #e2e5eb, #73777e);
        min-height: 100vh;
        font-family: 'Nunito', sans-serif;
    }

    h2 {
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
        margin-bottom: 20px;
    }

    ul {
        list-style: none;
        padding: 0;
    }

    ul li {
        background: rgba(255, 255, 255, 0.05);
        margin-bottom: 10px;
        padding: 10px 15px;
        border-radius: 8px;
        backdrop-filter: blur(6px);
        display: flex;
        justify-content: space-between;
        font-weight: 500;
    }

    ul li strong {
        color: #000;
    }

</style>

<div class="glass-card">
    <h2>Report Details</h2>
    <ul>
        <li><strong>ID:</strong> <span>{{ $report->id }}</span></li>
        <li><strong>Reporter:</strong> <span>{{ $report->reporter->name }}</span></li>
        <li><strong>Phone:</strong> <span>{{ $report->reporter->phone_number }}</span></li>
        <li><strong>Level:</strong> <span>Level {{ $report->level }}</span></li>
        <li><strong>Description:</strong> <span>{{ $report->description }}</span></li>
        <li><strong>Status:</strong> <span>{{ ucfirst($report->status) }}</span></li>
        <li><strong>Assigned To:</strong> <span>{{ strtoupper($report->designated_to) }}</span></li>
    </ul>
</div>
@endsection
