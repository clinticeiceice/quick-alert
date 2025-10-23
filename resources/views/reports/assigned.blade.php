@if($reports->isEmpty())
    <p>No reports assigned to you yet.</p>
@else
<table class="table table-bordered">
    <thead>
        <tr><th>ID</th><th>Reporter</th><th>Level</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $report->id }}</td>
            <td>{{ $report->reporter->name }}</td>
            <td>Level {{ $report->level }}</td>
            <td>{{ ucfirst($report->status) }}</td>
            <td>
                @if($report->status == 'approved')
                <form action="{{ route('reports.accept', $report) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm">Accept Report</button>
                </form>
                @else
                    <span class="badge bg-success">Accepted</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
