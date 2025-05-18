<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Wake Up Calls List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header .date { color: #7f8c8d; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #3498db; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Wake Up Calls List') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.wake_up_calls.id') }}</th>
                <th>{{ __('messages.wake_up_calls.customer_name') }}</th>
                <th>{{ __('messages.wake_up_calls.date') }}</th>
                <th>{{ __('messages.wake_up_calls.description') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wakeUpCalls as $index => $call)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $call->customer_name }}</td>
                <td>{{ \Carbon\Carbon::parse($call->date)->format('Y-m-d') }}</td>
                <td>{{ strip_tags($call->description) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Wake Up Calls') }}: {{ count($wakeUpCalls) }}
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        }
    </script>
</body>
</html>
