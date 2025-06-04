<!DOCTYPE html>
<html>
<head>
    <title>Accessories Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Accessories Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Accessory Name</th>
                <th>Category</th>
                <th>Manufacturer</th>
                <th>Quantity</th>
                <th>Purchase Cost</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accessories as $accessory)
            <tr>
                <td>{{ $accessory->accessory_name }}</td>
                <td>{{ $accessory->category_name }}</td>
                <td>{{ $accessory->manufacturer }}</td>
                <td>{{ $accessory->quantity }}</td>
                <td>{{ $accessory->purchase_cost ? '$'.number_format($accessory->purchase_cost, 2) : 'N/A' }}</td>
                <td>{{ $accessory->purchase_date ? $accessory->purchase_date->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
