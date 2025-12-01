@extends('layouts.app')

@section('content')
<style>
    /* Glassmorphism card */
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        padding: 25px 30px;
        max-width: 600px;
        margin: 60px auto;
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

    p {
        font-size: 1rem;
        margin-bottom: 25px;
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(6px);
        transition: all 0.3s ease;
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 500;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #000;
    }

    .btn-glass.cancel {
        background: rgba(200, 200, 200, 0.2);
        color: #111010;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .btn-glass.cancel:hover {
        background: rgba(200, 200, 200, 0.35);
        color: #000;
    }

    form button + a {
        margin-left: 10px;
    }
</style>

<div class="glass-card">
    <h2>Decline Report #{{ $report->id }}</h2>
    <p>Are you sure you want to decline this report?</p>

    <form action="{{ route('reports.decline.store', $report) }}" method="POST">
        @csrf
        <button type="submit" class="btn-glass">Yes, Decline</button>
        <a href="{{ route('dashboard') }}" class="btn-glass cancel">Cancel</a>
    </form>
</div>
@endsection
