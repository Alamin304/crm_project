<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.org_charts.title') }} - Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>{{ __('messages.org_charts.title') }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.org_charts.name') }}</th>
                <th>{{ __('messages.org_charts.parent_unit') }}</th>
                <th>{{ __('messages.org_charts.unit_manager') }}</th>
                <th>{{ __('messages.org_charts.email') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orgCharts as $orgChart)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $orgChart->name }}</td>
                <td>{{ $orgChart->parentUnit?->name ?? __('messages.org_charts.no_parent') }}</td>
                <td>{{ $orgChart->unit_manager }}</td>
                <td>{{ $orgChart->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
