<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Complementary Services') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header .date { color: #7f8c8d; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #3498db; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .rate { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Complementary Services') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">{{ __('SL') }}</th>
                <th width="25%">{{ __('Room Type') }}</th>
                <th width="40%">{{ __('Complementary') }}</th>
                <th width="25%" class="rate">{{ __('Rate') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complementaries as $index => $complementary)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $complementary->room_type }}</td>
                <td>{{ $complementary->complementary }}</td>
                <td class="rate">{{ number_format($complementary->rate, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Complementary Services') }}: {{ count($complementaries) }}
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
