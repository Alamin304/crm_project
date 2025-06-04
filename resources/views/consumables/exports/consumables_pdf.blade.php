<!DOCTYPE html>
<html>
<head>
    <title>Consumables Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Consumables List</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Manufacturer</th>
                <th>Quantity</th>
                <th>Min Qty</th>
                <th>Purchase Cost</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consumables as $consumable)
            <tr>
                <td>{{ $consumable->consumable_name }}</td>
                <td>{{ $consumable->category_name }}</td>
                <td>{{ $consumable->supplier }}</td>
                <td>{{ $consumable->manufacturer }}</td>
                <td>{{ $consumable->quantity }}</td>
                <td>{{ $consumable->min_quantity }}</td>
                <td>{{ number_format($consumable->purchase_cost, 2) }}</td>
                <td>{{ $consumable->purchase_date->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
