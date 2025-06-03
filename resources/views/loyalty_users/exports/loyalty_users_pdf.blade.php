<!DOCTYPE html>
<html>
<head>
    <title>Loyalty Users Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Loyalty Users List</h2>
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
