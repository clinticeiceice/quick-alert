@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">🚒 BFP Dashboard</h2>

    {{-- Display success messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Reports Table --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <strong>Reported Fire Incidents</strong>

            {{-- 📑 Generate Report Button --}}
            <a href="{{ route('reports.generate') }}" class="btn btn-light btn-sm">
                📑 Generate Report
            </a>
        </div>

        <div class="card-body">
            @if($reports->isEmpty())
                <p class="text-center">No fire reports available at the moment.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $report->reporter->name ?? 'N/A' }}</td>
                                    <td>{{ $report->description ?? 'No description' }}</td>
                                    <td class="text-center">
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
                                    <td>{{ $report->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="text-center">
                                        {{-- ✅ Accept Report Button --}}
                                        @if(auth()->user()->role === 'bfp' && $report->status === 'approved')
                                            <form action="{{ route('reports.accept', $report->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    ✅ Accept Report
                                                </button>
                                            </form>
                                        @endif

                                        {{-- 🔥 Fire Under Control Button --}}
                                        @if(Auth::user()->role === 'bfp' && $report->status === 'accepted')
                                            <form action="{{ route('reports.underControl', $report->id) }}" method="POST" style="display:inline;">
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
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $reports->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
