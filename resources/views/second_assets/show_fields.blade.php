<table class="table table-striped">
    <tbody>
        <tr>
            <th>{{ __('messages.second_assets.serial_number') }}</th>
            <td>{{ $secondAsset->serial_number }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.asset_name') }}</th>
            <td>{{ $secondAsset->asset_name }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.model') }}</th>
            <td>{{ $secondAsset->model }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.status') }}</th>
            <td>{{ ucfirst($secondAsset->status) }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.supplier') }}</th>
            <td>{{ $secondAsset->supplier }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.purchase_date') }}</th>
            <td>{{ $secondAsset->purchase_date->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.order_number') }}</th>
            <td>{{ $secondAsset->order_number }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.purchase_cost') }}</th>
            <td>{{ number_format($secondAsset->purchase_cost, 2) }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.location') }}</th>
            <td>{{ $secondAsset->location }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.warranty') }}</th>
            <td>{{ $secondAsset->warranty }} months</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.requestable') }}</th>
            <td>{{ $secondAsset->requestable ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.for_sell') }}</th>
            <td>{{ $secondAsset->for_sell ? 'Yes' : 'No' }}</td>
        </tr>
        @if($secondAsset->for_sell)
        <tr>
            <th>{{ __('messages.second_assets.selling_price') }}</th>
            <td>{{ number_format($secondAsset->selling_price, 2) }}</td>
        </tr>
        @endif
        <tr>
            <th>{{ __('messages.second_assets.for_rent') }}</th>
            <td>{{ $secondAsset->for_rent ? 'Yes' : 'No' }}</td>
        </tr>
        @if($secondAsset->for_rent)
        <tr>
            <th>{{ __('messages.second_assets.rental_price') }}</th>
            <td>{{ number_format($secondAsset->rental_price, 2) }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.minimum_renting_price') }}</th>
            <td>{{ number_format($secondAsset->minimum_renting_price, 2) }}</td>
        </tr>
        <tr>
            <th>{{ __('messages.second_assets.unit') }}</th>
            <td>{{ $secondAsset->unit }}</td>
        </tr>
        @endif
        <tr>
            <th>{{ __('messages.second_assets.description') }}</th>
            <td>{!! $secondAsset->description !!}</td>
        </tr>
    </tbody>
</table>
