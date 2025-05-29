<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Business Brokers') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .header .report-date {
            color: #666;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .status-active {
            color: #28a745;
        }

        .status-inactive {
            color: #dc3545;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('Business Brokers') }}</h1>
        <div class="report-date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="15%">{{ __('messages.property_owners.code') }}</th>
                <th width="20%">{{ __('messages.property_owners.name') }}</th>
                <th width="20%">{{ __('messages.property_owners.email') }}</th>
                <th width="15%">{{ __('messages.property_owners.phone') }}</th>
                <th width="10%">{{ __('messages.property_owners.is_active') }}</th>
                <th width="15%">{{ __('messages.property_owners.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brokers as $index => $owner)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $owner->code }}</td>
                    <td>{{ $owner->owner_name }}</td>
                    <td>{{ $owner->email }}</td>
                    <td>{{ $owner->phone_number }}</td>
                    <td class="status-{{ $owner->is_active ? 'active' : 'inactive' }}">
                        {{ $owner->is_active ? __('Active') : __('Inactive') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($owner->created_at)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Business Brokers') }}: {{ count($brokers) }}
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => window.print(), 500);
        };
    </script>
</body>

</html>
