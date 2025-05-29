<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Rental Requests Report') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
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
            font-size: 13px;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #6c757d;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('Rental Requests Report') }}</h1>
        <div class="report-date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Request Number') }}</th>
                <th>{{ __('Property Name') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Term') }}</th>
                <th>{{ __('Contract Amount') }}</th>
                <th>{{ __('Property Price') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Date Created') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rentalRequests as $index => $rentalRequest)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $rentalRequest->request_number }}</td>
                    <td>{{ $rentalRequest->property_name }}</td>
                    <td>{{ $rentalRequest->customer }}</td>
                    <td>{{ $rentalRequest->term }}</td>
                    <td>{{ number_format($rentalRequest->contract_amount, 2) }}</td>
                    <td>{{ number_format($rentalRequest->property_price, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($rentalRequest->start_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($rentalRequest->end_date)->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($rentalRequest->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($rentalRequest->created_at)->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Requests') }}: {{ count($rentalRequests) }}
    </div>
</body>

</html>
