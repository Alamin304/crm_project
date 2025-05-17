<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Currencies Report') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">{{ __('Currencies Report') }}</h2>
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
</body>
</html>
