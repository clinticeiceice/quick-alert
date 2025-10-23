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
        max-width: 700px;
        margin: 0 auto;
    }

    body {
        background: linear-gradient(135deg, #e2e5eb, #73777e);
        min-height: 100vh;
        color: #111010;
    }

    h2 {
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

    label {
        font-weight: 600;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #111010;
        border-radius: 8px;
        backdrop-filter: blur(6px);
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.35);
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
        color: #000;
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

<div class="glass-card mt-5">
    <h2 class="mb-4">Submit New Report</h2>

    <form action="{{ route('reports.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Level</label>
            <select name="level" class="form-control" required>
                <option value="1">Level 1 (Low)</option>
                <option value="2">Level 2 (Medium)</option>
                <option value="3">Level 3 (Severe)</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Designate To</label>
            <select name="designated_to" class="form-control" required>
                <option value="rescue">Rescue</option>
                <option value="pnp">PNP</option>
                <option value="bfp">BFP</option>
            </select>
        </div>

        <button class="btn btn-glass">Submit Report</button>
        
    </form>
</div>

@endsection
