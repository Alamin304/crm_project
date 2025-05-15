<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.notice_boards.notice_boards') }} Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h2 { color: #333; text-align: center; margin-bottom: 15px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>{{ __('messages.notice_boards.notice_boards') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.notice_boards.id') }}</th>
                <th>{{ __('messages.notice_boards.notice_type') }}</th>
                <th>{{ __('messages.notice_boards.description') }}</th>
                <th>{{ __('messages.notice_boards.notice_date') }}</th>
                <th>{{ __('messages.notice_boards.notice_by') }}</th>
                {{-- <th>{{ __('messages.notice_boards.notice_attachment') }}</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($noticeBoards as $notice)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $notice->notice_type }}</td>
                <td>{{ strip_tags($notice->description) }}</td>
                <td>{{ \Carbon\Carbon::parse($notice->notice_date)->format('m/d/Y') }}</td>
                <td>{{ $notice->notice_by }}</td>
                {{-- <td>{{ $notice->notice_attachment ? basename($notice->notice_attachment) : 'N/A' }}</td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
