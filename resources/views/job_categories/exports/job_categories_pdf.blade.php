<!DOCTYPE html>
<html>
<head>
    <title>Job Categories Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Job Categories List</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.job_categories.id') }}</th>
                <th>{{ __('messages.job_categories.name') }}</th>
                <th>{{ __('messages.job_categories.description') }}</th>
                <th>{{ __('messages.job_categories.start_date') }}</th>
                <th>{{ __('messages.job_categories.end_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobCategories as $jobCategory)
                <tr>
                    <td>{{ $jobCategory->id }}</td>
                    <td>{{ $jobCategory->name }}</td>
                    <td>{{ strip_tags($jobCategory->description) }}</td>
                    <td>{{ \Carbon\Carbon::parse($jobCategory->start_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($jobCategory->end_date)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
