<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Warranty Information') }}</title>
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
        <h1>{{ __('Warranty Information') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.warranties.id') }}</th>
                <th>{{ __('messages.warranties.customer') }}</th>
                <th>{{ __('messages.warranties.order_number') }}</th>
                <th>{{ __('messages.warranties.invoice') }}</th>
                <th>{{ __('messages.warranties.product_service_name') }}</th>
                <th>{{ __('messages.warranties.rate') }}</th>
                <th>{{ __('messages.warranties.quantity') }}</th>
                <th>{{ __('messages.warranties.serial_number') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warranties as $warranty)
            <tr>
                <td>{{ $warranty['id'] }}</td>
                <td>{{ $warranty['customer'] }}</td>
                <td>{{ $warranty['order_number'] }}</td>
                <td>{{ $warranty['invoice'] }}</td>
                <td>{{ $warranty['product_service_name'] }}</td>
                <td>{{ $warranty['rate'] }}</td>
                <td>{{ $warranty['quantity'] }}</td>
                <td>{{ $warranty['serial_number'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Warranties') }}: {{ count($warranties) }}
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
