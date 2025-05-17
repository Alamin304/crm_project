<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Branches Report') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">{{ __('Branches Report') }}</h2>
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
</body>
</html>
