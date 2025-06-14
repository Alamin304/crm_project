<!DOCTYPE html>
<html>
<head>
    <title>Work Centers Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="title">
        <h1>Work Centers</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Working Hours</th>
                <th>Time Efficiency (%)</th>
                <th>Cost Per Hour</th>
                <th>Capacity</th>
                <th>OEE Target (%)</th>
                <th>Time Before Prod (m)</th>
                <th>Time After Prod (m)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workCenters as $workCenter)
            <tr>
                <td>{{ $workCenter->name }}</td>
                <td>{{ $workCenter->code }}</td>
                <td>{{ $workCenter->working_hours }}</td>
                <td>{{ $workCenter->time_efficiency }}</td>
                <td>${{ number_format($workCenter->cost_per_hour, 2) }}</td>
                <td>{{ $workCenter->capacity }}</td>
                <td>{{ $workCenter->oee_target }}</td>
                <td>{{ $workCenter->time_before_prod }}</td>
                <td>{{ $workCenter->time_after_prod }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
