<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Loyalty Programs List') }}</title>
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
        <h1>{{ __('Loyalty Programs') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Redeem Type') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Min Points') }}</th>
                <th>{{ __('Rule Base') }}</th>
                <th>{{ __('Min Purchase') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loyaltyPrograms as $program)
            <tr>
                <td>{{ $program->name }}</td>
                <td>{{ ucfirst($program->redeem_type) }}</td>
                <td>{{ $program->start_date ? \Carbon\Carbon::parse($program->start_date)->format('Y-m-d') : '' }}</td>
                <td>{{ $program->end_date ? \Carbon\Carbon::parse($program->end_date)->format('Y-m-d') : '' }}</td>
                <td>{{ $program->minimum_point_to_redeem }}</td>
                <td>{{ ucfirst($program->rule_base) }}</td>
                <td>{{ $program->minimum_purchase }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Programs') }}: {{ count($loyaltyPrograms) }}
    </div>

    @if(request()->has('print'))
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        }
    </script>
    @endif
</body>
</html>
