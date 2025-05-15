<!DOCTYPE html>
<html>
<head>
    <title>Booking Sources Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Booking Sources List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Booking Type</th>
                <th>Booking Source</th>
                <th>Commission Rate (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookingSources as $source)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $source->booking_type }}</td>
                <td>{{ $source->booking_source }}</td>
                <td>{{ number_format($source->commission_rate, 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
