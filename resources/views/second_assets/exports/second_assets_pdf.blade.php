<!DOCTYPE html>
<html>
<head>
    <title>Second Assets Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Second Assets List</h2>
    <table>
        <thead>
            <tr>
                <th>SL</th>
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
                <td>{{ $loop->iteration }}</td>
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
</body>
</html>
