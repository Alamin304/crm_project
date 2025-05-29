<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Rental Requests') }}</title>
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
            font-size: 12px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .status-pending {
            color: #ffc107;
        }

        .status-approved {
            color: #28a745;
        }

        .status-rejected {
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
        <h1>{{ __('Rental Requests') }}</h1>
        <div class="report-date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Request Number') }}</th>
                <th>{{ __('Property Name') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Inspected') }}</th>
                <th>{{ __('Contract Amount') }}</th>
                <th>{{ __('Property Price') }}</th>
                <th>{{ __('Term') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $index => $request)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $request->request_number }}</td>
                    <td>{{ $request->property_name }}</td>
                    <td>{{ optional($request->customer)->name ?? 'N/A' }}</td>
                    <td>{{ $request->inspected_property ? __('Yes') : __('No') }}</td>
                    <td>{{ $request->contract_amount ? '$' . number_format($request->contract_amount, 2) : 'N/A' }}</td>
                    <td>{{ $request->property_price ? '$' . number_format($request->property_price, 2) : 'N/A' }}</td>
                    <td>{{ $request->term ? $request->term . ' ' . __('months') : 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->start_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->end_date)->format('Y-m-d') }}</td>
                    <td class="status-{{ strtolower($request->status) }}">
                        {{ ucfirst($request->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Requests') }}: {{ count($requests) }}
    </div>

    <script>
        window.onload = function () {
            setTimeout(() => window.print(), 500);
        };
    </script>
</body>

</html>
