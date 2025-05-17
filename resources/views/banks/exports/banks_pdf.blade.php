<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Banks Report') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">{{ __('Banks Report') }}</h2>
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
</body>
</html>
