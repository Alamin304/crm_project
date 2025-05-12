<!DOCTYPE html>
<html>
<head>
    <title>Shifts Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Shifts List</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.shifts.id') }}</th>
                <th>{{ __('messages.shifts.name') }}</th>
                <th>{{ __('messages.shifts.shift_start_time') }}</th>
                <th>{{ __('messages.shifts.shift_end_time') }}</th>
                <th>{{ __('messages.shifts.duration') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
            <tr>
                <td>{{ $shift->id }}</td>
                <td>{{ $shift->name }}</td>
                <td>{{ $shift->shift_start_time ? $shift->shift_start_time->format('h:i A') : '' }}</td>
                <td>{{ $shift->shift_end_time ? $shift->shift_end_time->format('h:i A') : '' }}</td>
                <td>
                    @if($shift->shift_start_time && $shift->shift_end_time)
                        @php
                            $start = clone $shift->shift_start_time;
                            $end = clone $shift->shift_end_time;
                            if ($end < $start) {
                                $end->addDay();
                            }
                            $diff = $end->diff($start);
                            $hours = $diff->h + ($diff->i / 60);
                            echo round($hours, 1) . ' hours';
                        @endphp
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
