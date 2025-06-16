<!DOCTYPE html>
<html>
<head>
    <title>Work Orders Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Work Orders Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Work Order</th>
                <th>Start Date</th>
                <th>Work Center</th>
                <th>Manufacturing Order</th>
                <th>Product Quantity</th>
                <th>Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workOrders as $workOrder)
            <tr>
                <td>{{ $workOrder->work_order }}</td>
                <td>{{ $workOrder->start_date->format('Y-m-d H:i') }}</td>
                <td>{{ $workOrder->work_center }}</td>
                <td>{{ $workOrder->manufacturing_order }}</td>
                <td>{{ $workOrder->product_quantity }}</td>
                <td>{{ $workOrder->unit }}</td>
                <td>{{ ucfirst($workOrder->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
