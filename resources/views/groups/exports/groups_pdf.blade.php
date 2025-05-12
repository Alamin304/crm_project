<!DOCTYPE html>
<html>
<head>
    <title>Groups Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Groups List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Group Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td>{{ $group->group_name }}</td>
                <td>{!! strip_tags($group->description) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
