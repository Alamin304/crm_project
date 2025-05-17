<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Job Categories List') }}</title>
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
    <h1 class="text-center mb-4">{{ __('Job Categories List') }}</h1>
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
            @foreach($jobCategories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ strip_tags($category->description) }}</td>
                <td>{{ Carbon\Carbon::parse($category->start_date)->format('Y-m-d') }}</td>
                <td>{{ Carbon\Carbon::parse($category->end_date)->format('Y-m-d') }}</td>
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
