<!DOCTYPE html>
<html>
<head>
    <title>Complementaries Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Complementaries List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Room Type</th>
                <th>Rate</th>
                <th>Complementary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complementaries as $complementary)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $complementary->room_type }}</td>
                <td>{{ number_format($complementary->rate, 2) }}</td>
                <td>{!! strip_tags($complementary->complementary) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
