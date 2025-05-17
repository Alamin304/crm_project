<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Shifts List') }}</title>
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
    <h1 class="text-center mb-4">{{ __('Shifts List') }}</h1>
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
            @foreach($shifts as $index => $shift)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $shift->name }}</td>
                <td>{{ $shift->shift_start_time ? $shift->shift_start_time->format('h:i A') : '' }}</td>
                <td>{{ $shift->shift_end_time ? $shift->shift_end_time->format('h:i A') : '' }}</td>
                <td>
                    @if($shift->shift_start_time && $shift->shift_end_time)
                        @php
                            $start = clone $shift->shift_start_time;
                            $end = clone $shift->shift_end_time;
                            if ($end < $start) $end->addDay();
                            $diff = $end->diff($start);
                            $hours = $diff->h + ($diff->i / 60);
                        @endphp
                        {{ round($hours, 1) }} hours
                    @endif
                </td>
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
