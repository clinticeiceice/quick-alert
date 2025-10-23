<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ strtoupper($role) }} Reports</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1 class="justify-conent-center"> Quick-Alert System</h1>
    <h2>{{ strtoupper($role) }} - Accepted Reports</h2>
    <table>
        <thead>
            <tr>
               
                <th>Level</th>
                <th>Status</th>
                <th>Designated To</th>
                <th>Accepted At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                   
                    <td>Level {{ $report->level }}</td>
                    <td>{{ ucfirst($report->status) }}</td>
                    <td>{{ strtoupper($report->designated_to) }}</td>
                    <td>
                        {{ optional($report->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
