@extends('layouts.app')

@section('content')
<div class="container">


    <h1 class="text-center mb-4">All Accepted Agency Reports</h1>

    <div class="card shadow-sm">
    <div class="card-header bg-primary text-white text-center fw-bold">
        Reports
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-secondary text-center">
                    <tr>
                        <th style="width: 15%">Level</th>
                         <th style="width: 25%">Designated To</th>
                          <th style="width: 20%">Description</th>
                        <th style="width: 20%">Status</th>
                       
                       
                        {{-- <th style="width: 25%">Accepted At</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td class="text-center fw-small">
                                Level {{ $report->level }}
                            </td>

                            <td class="text-center text-uppercase">
                                {{ $report->designated_to }}
                            </td>

                            <td class="text-center">
                                <span class="badge bg-secondary text-uppercase">
                                    {{ $report->description }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-success text-uppercase">
                                    {{ $report->status }}
                                </span>
                            </td>


                            {{-- <td class="text-center text-muted">
                                {{ $report->created_at
                                    ? $report->created_at->timezone('Asia/Manila')->format('M d, Y h:i A')
                                    : 'N/A' }}
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No reports found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
@endsection
