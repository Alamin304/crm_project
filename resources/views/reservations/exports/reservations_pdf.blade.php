<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.reservations.title') }} {{ __('messages.common.export') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h2>{{ __('messages.reservations.title') }} – {{ __('messages.common.list') }}</h2>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.reservations.customer_name') }}</th>
                <th>{{ __('messages.reservations.table_no') }}</th>
                <th>{{ __('messages.reservations.number_of_people') }}</th>
                <th>{{ __('messages.reservations.start_time') }}</th>
                <th>{{ __('messages.reservations.end_time') }}</th>
                <th>{{ __('messages.reservations.date') }}</th>
                <th>{{ __('messages.reservations.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->customer_name }}</td>
                    <td>{{ $reservation->table_no }}</td>
                    <td class="text-center">{{ $reservation->number_of_people }}</td>
                    <td>{{ $reservation->start_time }}</td>
                    <td>{{ $reservation->end_time }}</td>
                    <td>{{ $reservation->date }}</td>
                    <td class="text-center">{{ ucfirst($reservation->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print text-right">
        <button onclick="window.print();" class="btn btn-primary">{{ __('messages.common.print') }}</button>
    </div>
</body>
</html>
