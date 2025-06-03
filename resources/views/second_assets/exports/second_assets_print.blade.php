<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Second Assets List') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <h1 class="text-center mb-4">{{ __('Second Assets List') }}</h1>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.second_assets.asset_name') }}</th>
                <th>{{ __('messages.second_assets.serial_number') }}</th>
                <th>{{ __('messages.second_assets.model') }}</th>
                <th>{{ __('messages.second_assets.status') }}</th>
                <th>{{ __('messages.second_assets.location') }}</th>
                <th>{{ __('messages.second_assets.supplier') }}</th>
                <th>{{ __('messages.second_assets.purchase_date') }}</th>
                <th>{{ __('messages.second_assets.purchase_cost') }}</th>
                <th>{{ __('messages.second_assets.order_number') }}</th>
                <th>{{ __('messages.second_assets.warranty') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($secondAssets as $asset)
            <tr>
                <td>{{ $asset->asset_name }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->model }}</td>
                <td>{{ ucfirst($asset->status) }}</td>
                <td>{{ $asset->location }}</td>
                <td>{{ $asset->supplier }}</td>
                <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('Y-m-d') }}</td>
                <td>{{ number_format($asset->purchase_cost, 2) }}</td>
                <td>{{ $asset->order_number }}</td>
                <td>{{ $asset->warranty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
