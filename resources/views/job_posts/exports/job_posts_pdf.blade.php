<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.job_posts.job_posts') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; font-size: 12px; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>{{ __('messages.job_posts.job_posts') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.job_posts.position') }}</th>
                <th>{{ __('messages.job_posts.company') }}</th>
                <th>{{ __('messages.job_posts.posting_date') }}</th>
                <th>{{ __('messages.job_posts.status') }}</th>
                <th>{{ __('messages.job_posts.date_of_closing') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobPosts as $jobPost)
                <tr>
                    <td>{{ $jobPost->job_title }}</td>
                    <td>{{ $jobPost->company_name }}</td>
                    <td>{{ optional($jobPost->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $jobPost->status ? __('messages.job_posts.active') : __('messages.job_posts.inactive') }}</td>
                    <td>{{ optional($jobPost->date_of_closing)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
