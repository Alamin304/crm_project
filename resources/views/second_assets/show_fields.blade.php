<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.serial_number') }}:</strong>
            {{ $asset->serial_number }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.asset_name') }}:</strong>
            {{ $asset->asset_name }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.model') }}:</strong>
            {{ $asset->model }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.status') }}:</strong>
            {{ ucfirst($asset->status) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.supplier') }}:</strong>
            {{ $asset->supplier }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.purchase_date') }}:</strong>
            {{ \Carbon\Carbon::parse($asset->purchase_date)->format('d M, Y') }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.order_number') }}:</strong>
            {{ $asset->order_number ?? 'N/A' }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.purchase_cost') }}:</strong>
            {{ formatCurrency($asset->purchase_cost) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.location') }}:</strong>
            {{ $asset->location }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.warranty_months') }}:</strong>
            {{ $asset->warranty_months ?? 'N/A' }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.requestable') }}:</strong>
            {{ $asset->requestable ? 'Yes' : 'No' }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.for_sale') }}:</strong>
            {{ $asset->for_sale ? 'Yes' : 'No' }}
        </div>
    </div>
    @if($asset->for_sale)
        <div class="col-md-6">
            <div class="form-group">
                <strong>{{ __('messages.assets.selling_price') }}:</strong>
                {{ formatCurrency($asset->selling_price) }}
            </div>
        </div>
    @endif
    <div class="col-md-6">
        <div class="form-group">
            <strong>{{ __('messages.assets.for_rent') }}:</strong>
            {{ $asset->for_rent ? 'Yes' : 'No' }}
        </div>
    </div>
    @if($asset->for_rent)
        <div class="col-md-6">
            <div class="form-group">
                <strong>{{ __('messages.assets.rental_price') }}:</strong>
                {{ formatCurrency($asset->rental_price) }} per {{ $asset->rental_unit }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <strong>{{ __('messages.assets.minimum_renting_days') }}:</strong>
                {{ $asset->minimum_renting_days }}
            </div>
        </div>
    @endif
    <div class="col-md-12">
        <div class="form-group">
            <strong>{{ __('messages.assets.description') }}:</strong>
            <div class="border p-3">
                {!! $asset->description !!}
            </div>
        </div>
    </div>
</div>
