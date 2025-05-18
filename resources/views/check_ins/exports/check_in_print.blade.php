<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Check-In Records') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header .date { color: #7f8c8d; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #3498db; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .status-confirmed { color: #28a745; font-weight: bold; }
        .status-pending { color: #dc3545; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Check-In Records') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.check_in.id') }}</th>
                <th>{{ __('messages.check_in.booking_number') }}</th>
                <th>{{ __('messages.check_in.room_type') }}</th>
                <th>{{ __('messages.check_in.room_no') }}</th>
                <th>{{ __('messages.check_in.check_in') }}</th>
                <th>{{ __('messages.check_in.check_out') }}</th>
                <th>{{ __('messages.check_in.booking_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($checkIns as $index => $checkIn)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $checkIn->booking_number }}</td>
                <td>{{ $checkIn->room_type }}</td>
                <td>{{ $checkIn->room_no }}</td>
                <td>{{ \Carbon\Carbon::parse($checkIn->check_in)->format('Y-m-d H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($checkIn->check_out)->format('Y-m-d H:i') }}</td>
                <td class="{{ $checkIn->booking_status ? 'status-confirmed' : 'status-pending' }}">
                    {{ $checkIn->booking_status ? __('Confirmed') : __('Pending') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Check-Ins') }}: {{ count($checkIns) }} |
        {{ __('Confirmed') }}: {{ $checkIns->where('booking_status', true)->count() }} |
        {{ __('Pending') }}: {{ $checkIns->where('booking_status', false)->count() }}
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
