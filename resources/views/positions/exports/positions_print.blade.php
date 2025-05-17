<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Positions List') }}</title>
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
    <h1 class="text-center mb-4">{{ __('Positions List') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.positions.id') }}</th>
                <th>{{ __('messages.positions.name') }}</th>
                <th>{{ __('messages.positions.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $index => $position)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $position->name }}</td>
                <td>{{ $position->status ? __('Active') : __('Inactive') }}</td>
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
