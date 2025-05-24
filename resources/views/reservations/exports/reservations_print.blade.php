<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Reservation List') }}</title>
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
        <h1>{{ __('Reservation List') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.reservations.customer_name') }}</th>
                <th>{{ __('messages.reservations.table_no') }}</th>
                <th>{{ __('messages.reservations.number_of_people') }}</th>
                <th>{{ __('messages.reservations.date') }}</th>
                <th>{{ __('messages.reservations.start_time') }}</th>
                <th>{{ __('messages.reservations.end_time') }}</th>
                <th>{{ __('messages.reservations.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $index => $reservation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $reservation->customer_name }}</td>
                    <td>{{ $reservation->table_no }}</td>
                    <td>{{ $reservation->number_of_people }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</td>
                    <td class="{{ $reservation->status === 'confirmed' ? 'status-confirmed' : 'status-pending' }}">
                        {{ ucfirst($reservation->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Reservations') }}: {{ count($reservations) }} |
        {{ __('Confirmed') }}: {{ $reservations->where('status', 'confirmed')->count() }} |
        {{ __('Pending') }}: {{ $reservations->where('status', 'pending')->count() }}
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
