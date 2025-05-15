<!DOCTYPE html>
<html>
<head>
    <title>Positions Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Positions List</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.positions.id') }}</th>
                <th>{{ __('messages.positions.name') }}</th>
                <th>{{ __('messages.positions.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $position)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- This shows sequential numbers -->
                <td>{{ $position->name }}</td>
                <td>{{ $position->status ? 'Active' : 'Inactive' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
