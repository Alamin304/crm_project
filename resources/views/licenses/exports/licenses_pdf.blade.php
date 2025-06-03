<!DOCTYPE html>
<html>
<head>
    <title>Licenses Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
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
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="text-center">Licenses Report</h1>
    <p class="text-center">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Software Name</th>
                <th>Category</th>
                <th>Product Key</th>
                <th>Seats</th>
                <th>Manufacturer</th>
                <th>Purchase Date</th>
                <th>Expiration Date</th>
                <th>Purchase Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($licenses as $license)
                <tr>
                    <td>{{ $license->software_name }}</td>
                    <td>{{ $license->category_name }}</td>
                    <td>{{ $license->product_key }}</td>
                    <td>{{ $license->seats }}</td>
                    <td>{{ $license->manufacturer }}</td>
                    <td>{{ $license->purchase_date->format('Y-m-d') }}</td>
                    <td>{{ $license->expiration_date ? $license->expiration_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ number_format($license->purchase_cost, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>