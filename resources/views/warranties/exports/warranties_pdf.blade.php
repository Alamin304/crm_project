<!DOCTYPE html>
<html>

<head>
    <title>Warranty Export</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            word-break: break-word;
        }
    </style>
</head>

<body>
    <h2>{{ __('messages.warranties.export') }} {{ __('messages.warranties.warranties') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.warranties.id') }}</th>
                <th>{{ __('messages.warranties.claim_code') }}</th>
                <th>{{ __('messages.warranties.customer') }}</th>
                <th>{{ __('messages.warranties.date_created') }}</th>
                <th>{{ __('messages.warranties.description') }}</th>
                <th>{{ __('messages.warranties.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($warranties as $index => $warranty)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $warranty->claim_code }}</td>
                    <td>{{ $warranty->customer }}</td>
                    <td>{{ \Carbon\Carbon::parse($warranty->date_created)->format('Y-m-d') }}</td>
                    <td>{{ e(strip_tags($warranty->description)) }}</td>
                    <td>{{ ucfirst($warranty->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
