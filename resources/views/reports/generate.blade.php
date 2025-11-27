@extends('layouts.app')

@section('content')
<div class="glass-card">
    <h2 class="mt-5">📑 Generated Report</h2>

    @if($reports->isEmpty())
        <p class="text-center">No accepted reports found.</p>
    @else
        <div class="table-responsive table-glass">
            <table class="table table-bordered table-hover mb-0 text-white">
                <a href="{{ route('reports.generate.pdf') }}" class="btn btn-danger">
                    Download PDF Report
                 </a>

                <thead>
                    <tr>
                        <th>Reporter</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Designated To</th>
                        <th>Accepted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->reporter->name }}</td>
                        <td>Level {{ $report->level }}</td>
                        <td>{{ ucfirst($report->status) }}</td>
                        <td>{{ strtoupper($report->designated_to) }}</td>
                        <td>{{ $report->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
