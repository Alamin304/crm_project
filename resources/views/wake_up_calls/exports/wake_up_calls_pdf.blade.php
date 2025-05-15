<!DOCTYPE html>
<html>

<head>
    <title>Wake Up Calls Export</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Wake Up Calls List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Customer Name</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wakeUpCalls as $index => $call)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- This shows sequential numbers -->
                    <td>{{ $call->customer_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($call->date)->format('Y-m-d') }}</td>
                    <td>{{ e(strip_tags($call->description)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
