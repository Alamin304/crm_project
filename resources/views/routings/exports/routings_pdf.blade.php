<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.routings.routings') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>{{ __('messages.routings.routings') }}</h1>
<table>
    <thead>
    <tr>
        <th>{{ __('messages.routings.routing_code') }}</th>
        <th>{{ __('messages.routings.routing_name') }}</th>
        <th>{{ __('messages.routings.note') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($routings as $routing)
        <tr>
            <td>{{ $routing->routing_code }}</td>
            <td>{{ $routing->routing_name }}</td>
            <td>{!! $routing->note !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
