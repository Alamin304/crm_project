<!DOCTYPE html>
<html>
<head>
    <title>Loyalty Users Print</title>
    <style>
        @media print {
            .no-print { display: none; }
        }
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .print-btn { margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Loyalty Users List</h2>
    <button onclick="window.print()" class="no-print print-btn">Print</button>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Email</th>
                <th>Membership</th>
                <th>Loyalty Point</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loyaltyUsers as $user)
            <tr>
                <td>{{ $user->customer }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->membership }}</td>
                <td>{{ $user->loyalty_point }}</td>
                <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
