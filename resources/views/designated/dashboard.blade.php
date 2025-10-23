<h2 class="text-xl font-bold mb-4">Designated Personnel Dashboard</h2>

<table class="w-full border">
    <thead>
        <tr>
            <th>Reporter</th>
            <th>Level</th>
            <th>Description</th>
            <th>Designated To</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $report->reporter->name }}</td>
            <td>{{ $report->level }}</td>
            <td>{{ $report->description }}</td>
            <td>{{ strtoupper($report->designated_to) }}</td>
            <td>{{ $report->status }}</td>
            <td>
                @if($report->status === 'pending')
                <form action="{{ route('reports.approve', $report) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">
                        Approve
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
