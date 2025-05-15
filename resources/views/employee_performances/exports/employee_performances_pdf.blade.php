<!DOCTYPE html>
<html>
<head>
    <title>Employee Performances Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Employee Performances List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Total Score</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employeePerformances as $index => $performance)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- This shows sequential numbers -->
                <td>{{ $performance->employee->name ?? 'N/A' }}</td>
                <td>{{ $performance->total_score }}</td>
                <td>{{ $performance->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
