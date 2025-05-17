<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Notice Boards List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 1.5rem; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .date { font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Notice Boards List') }}</h1>
        <div class="date">{{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">{{ __('messages.notice_boards.id') }}</th>
                <th width="15%">{{ __('messages.notice_boards.notice_type') }}</th>
                <th width="45%">{{ __('messages.notice_boards.description') }}</th>
                <th width="15%">{{ __('messages.notice_boards.notice_date') }}</th>
                <th width="20%">{{ __('messages.notice_boards.notice_by') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($noticeBoards as $index => $notice)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $notice->notice_type }}</td>
                <td>{{ strip_tags($notice->description) }}</td>
                <td>{{ $notice->notice_date }}</td>
                <td>{{ $notice->notice_by }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
