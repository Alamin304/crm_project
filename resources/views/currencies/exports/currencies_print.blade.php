<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Currencies List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin-bottom: 5px; }
        .header .date { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; color: #333; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 10px; border-bottom: 1px solid #dee2e6; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Currencies List') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.currencies.id') }}</th>
                <th>{{ __('messages.currencies.currencies') }}</th>
                <th>{{ __('messages.currencies.description') }}</th>
                <th>{{ __('messages.currencies.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($currencies as $index => $currency)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $currency->name }}</td>
                <td>{{ $currency->description }}</td>
                <td>{{ $currency->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Currencies') }}: {{ count($currencies) }}
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
