<!DOCTYPE html>
<html>
<head>
    <title>Pre Alerts PDF Export</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Pre Alerts</h1>
    <table>
        <thead>
            <tr>
                <th>Tracking</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Shipping Company</th>
                <th>Supplier</th>
                <th>Package Description</th>
                <th>Delivery Date</th>
                <th>Purchase Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preAlerts as $alert)
            <tr>
                <td>{{ $alert->tracking }}</td>
                <td>{{ $alert->date }}</td>
                <td>{{ $alert->customer }}</td>
                <td>{{ $alert->shipping_company }}</td>
                <td>{{ $alert->supplier }}</td>
                <td>{{ $alert->package_description }}</td>
                <td>{{ $alert->delivery_date }}</td>
                <td>{{ $alert->purchase_price }}</td>
                <td>{{ ucfirst($alert->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
