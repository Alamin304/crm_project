<!DOCTYPE html>
<html>
<head>
    <title>Companies Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Companies List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $Company)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- This shows sequential numbers -->
                <td>{{ $Company->name }}</td>
                <td>{!! strip_tags($Company->description) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
