<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h3>{{ __('messages.warranties.warranty_information') }}</h3>
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
            @foreach ($warranties as $warranty)
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
</body>
</html>
