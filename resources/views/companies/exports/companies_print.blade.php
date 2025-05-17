<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Companies List') }}</title>
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
    <h1 class="text-center mb-4">{{ __('Companies List') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.companies.id') }}</th>
                <th>{{ __('messages.companies.name') }}</th>
                <th>{{ __('messages.companies.description') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $index => $company)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $company->name }}</td>
                <td>{!! strip_tags($company->description) !!}</td>
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
