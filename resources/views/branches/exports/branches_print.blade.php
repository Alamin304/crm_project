<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Branches List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin-bottom: 5px; }
        .header .date { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; color: #333; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 10px; border-bottom: 1px solid #dee2e6; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Branches List') }}</h1>
        <div class="date">{{ __('Generated on') }}: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.branches.id') }}</th>
                <th>{{ __('messages.branches.company') }}</th>
                <th>{{ __('messages.branches.name') }}</th>
                <th>{{ __('messages.customer.vat_number') }}</th>
                <th>{{ __('messages.branches.phone') }}</th>
                <th>{{ __('messages.suppliers.country') }}</th>
                <th>{{ __('messages.customer.city') }}</th>
                <th>{{ __('messages.banks.name') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $index => $branch)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ strip_tags($branch->company_name ?? '') }}</td>
                <td>{{ strip_tags($branch->name) }}</td>
                <td>{{ $branch->vat_number ?? '' }}</td>
                <td>{{ $branch->phone ?? '' }}</td>
                <td>{{ optional($branch->country)->name ?? '' }}</td>
                <td>{{ $branch->city ?? '' }}</td>
                <td>{{ optional($branch->bank)->name ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Total Branches') }}: {{ count($branches) }}
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
