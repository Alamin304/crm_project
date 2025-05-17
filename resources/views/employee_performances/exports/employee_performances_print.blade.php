<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Employee Performances List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <h1 class="text-center mb-4">{{ __('Employee Performances List') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.employee_performances.id') }}</th>
                <th>{{ __('messages.employee_performances.name') }}</th>
                <th>{{ __('messages.employee_performances.total_score') }}</th>
                <th>{{ __('messages.employee_performances.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employeePerformances as $index => $performance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $performance->employee->name ?? 'N/A' }}</td>
                <td>{{ $performance->total_score }}</td>
                <td>{{ $performance->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
