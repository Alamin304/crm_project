<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Groups List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <h1 class="text-center mb-4">{{ __('Groups List') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.groups.id') }}</th>
                <th>{{ __('messages.groups.group_name') }}</th>
                <th>{{ __('messages.groups.description') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $group->group_name }}</td>
                <td>{!! strip_tags($group->description) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
