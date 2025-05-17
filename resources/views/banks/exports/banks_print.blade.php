<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Banks List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin-bottom: 5px; }
        .header .date { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; color: #333; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 10px; border-bottom: 1px solid #dee2e6; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Banks List') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.banks.id') }}</th>
                <th>{{ __('messages.banks.name') }}</th>
                <th>{{ __('messages.banks.account_number') }}</th>
                <th>{{ __('messages.banks.branch_name') }}</th>
                <th>{{ __('messages.banks.iban_number') }}</th>
                <th>{{ __('messages.banks.description') }}</th>
                <th class="text-right">{{ __('messages.banks.opening_balance') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $index => $bank)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ strip_tags($bank->name) }}</td>
                <td>{{ $bank->account_number ?? '' }}</td>
                <td>{{ $bank->branch_name ?? '' }}</td>
                <td>{{ $bank->iban_number ?? '' }}</td>
                <td>{{ strip_tags($bank->description) }}</td>
                <td class="text-right">{{ number_format($bank->opening_balance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Banks') }}: {{ count($banks) }}
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
