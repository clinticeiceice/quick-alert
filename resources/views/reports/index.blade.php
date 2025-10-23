@extends('layouts.app')

@section('content')
<h2>Pending Reports for Approval</h2>
<table class="table table-bordered">
    <thead>
        <tr><th>ID</th><th>Reporter</th><th>Level</th><th>Action</th></tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $report->id }}</td>
            <td>{{ $report->reporter->name }}</td>
            <td>Level {{ $report->level }}</td>
            <td>
                <a href="{{ route('reports.show', $report) }}" class="btn btn-info btn-sm">View</a>
                <a href="{{ route('reports.approve', $report) }}" class="btn btn-success btn-sm">Approve</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
