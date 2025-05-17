<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Divisions List') }}</title>
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
    <h1 class="text-center mb-4">{{ __('Divisions List') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.divisions.id') }}</th>
                <th>{{ __('messages.divisions.name') }}</th>
                <th>{{ __('messages.divisions.description') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($divisions as $index => $division)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $division->name }}</td>
                <td>{!! strip_tags($division->description) !!}</td>
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
