<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Warranties Report') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin-bottom: 5px; }
        .header .report-date { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; color: #333; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 10px; border-bottom: 1px solid #dee2e6; }
        .status-active { color: #28a745; }
        .status-inactive { color: #dc3545; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Warranties Report') }}</h1>
        <div class="report-date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">{{ __('messages.warranties.id') }}</th>
                <th width="15%">{{ __('messages.warranties.claim_code') }}</th>
                <th width="20%">{{ __('messages.warranties.customer') }}</th>
                <th width="15%">{{ __('messages.warranties.date_created') }}</th>
                <th width="30%">{{ __('messages.warranties.description') }}</th>
                <th width="15%">{{ __('messages.warranties.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warranties as $index => $warranty)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $warranty->claim_code }}</td>
                <td>{{ $warranty->customer }}</td>
                <td>{{ $warranty->date_created ? Carbon\Carbon::parse($warranty->date_created)->format('Y-m-d') : '' }}</td>
                <td>{{ e(strip_tags($warranty->description)) }}</td>
                <td class="status-{{ strtolower($warranty->status) }}">{{ $warranty->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Warranties') }}: {{ count($warranties) }}
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
