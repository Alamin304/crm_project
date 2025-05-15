<!DOCTYPE html>
<html>

<head>
    <title>Booking List Export</title>
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
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Booking List</h2>
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
            @foreach ($bookingLists as $index => $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- Changed from $booking->id to show serial number -->
                    <td>{{ $booking->booking_number }}</td>
                    <td>{{ $booking->room_type }}</td>
                    <td>{{ $booking->room_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d H:i') }}</td>
                    <td>{{ $booking->booking_status ? 'Confirmed' : 'Pending' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
