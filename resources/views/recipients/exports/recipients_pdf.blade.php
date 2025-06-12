<!DOCTYPE html>
<html>
<head>
    <title>Recipients PDF Export</title>
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
    <h1>Recipients List</h1>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Recipient</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipients as $recipient)
            <tr>
                <td>{{ $recipient->customer }}</td>
                <td>{{ $recipient->recipient }}</td>
                <td>{{ $recipient->email }}</td>
                <td>{{ $recipient->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
