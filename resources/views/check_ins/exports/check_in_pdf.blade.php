<!DOCTYPE html>
<html>
<head>
    <title>Check In List Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Check In List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Booking Number</th>
                <th>Room Type</th>
                <th>Room No</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($checkIns as $checkIn)
            <tr>
                <td>{{ $checkIn->id }}</td>
                <td>{{ $checkIn->booking_number }}</td>
                <td>{{ $checkIn->room_type }}</td>
                <td>{{ $checkIn->room_no }}</td>
                <td>{{ \Carbon\Carbon::parse($checkIn->check_in)->format('Y-m-d H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($checkIn->check_out)->format('Y-m-d H:i') }}</td>
                <td>{{ $checkIn->booking_status ? 'Confirmed' : 'Pending' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
