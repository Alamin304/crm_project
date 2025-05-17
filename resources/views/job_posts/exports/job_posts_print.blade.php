<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Job Posts Report') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin-bottom: 5px; }
        .header .report-date { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; color: #333; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 10px; border-bottom: 1px solid #dee2e6; vertical-align: top; }
        .status-active { color: #28a745; }
        .status-inactive { color: #dc3545; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #6c757d; }
        strong { font-weight: 600; }
        small { font-size: 0.875em; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Job Posts Report') }}</h1>
        <div class="report-date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30%">{{ __('messages.job_posts.position') }}</th>
                <th width="20%">{{ __('messages.job_posts.company') }}</th>
                <th width="15%">{{ __('messages.job_posts.posting_date') }}</th>
                <th width="15%">{{ __('messages.job_posts.status') }}</th>
                <th width="20%">{{ __('messages.job_posts.date_of_closing') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobPosts as $jobPost)
                <tr>
                    <td>
                        <strong>{{ $jobPost->job_title }}</strong><br>
                        <small>{{ optional($jobPost->category)->name }}</small><br>
                        <small>{{ $jobPost->no_of_vacancy }} {{ __('messages.job_posts.vacancies') }}</small>
                    </td>
                    <td>{{ $jobPost->company_name }}</td>
                    <td>{{ optional($jobPost->created_at)->format('Y-m-d') }}</td>
                    <td class="status-{{ $jobPost->status ? 'active' : 'inactive' }}">
                        {{ $jobPost->status ? __('messages.job_posts.active') : __('messages.job_posts.inactive') }}
                    </td>
                    <td>{{ optional($jobPost->date_of_closing)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Job Posts') }}: {{ count($jobPosts) }}
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
