<div class="row">
    <div class="form-group col-sm-6">
        {{ Form::label('serial_number', __('messages.second_assets.serial_number').':') }}<span class="required">*</span>
        {{ Form::text('serial_number', null, ['class' => 'form-control', 'required']) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('asset_name', __('messages.second_assets.asset_name').':') }}<span class="required">*</span>
        {{ Form::text('asset_name', null, ['class' => 'form-control', 'required']) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('model', __('messages.second_assets.model').':') }}<span class="required">*</span>
        <select name="model" class="form-control" required>
            <option value="">Select Model</option>
            @foreach($models as $model)
                <option value="{{ $model }}" {{ (isset($secondAsset) && $secondAsset->model == $model) ? 'selected' : '' }}>{{ $model }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('status', __('messages.second_assets.status').':') }}<span class="required">*</span>
        <select name="status" class="form-control" required>
            <option value="">Select Status</option>
            <option value="ready" {{ (isset($secondAsset) && $secondAsset->status == 'ready') ? 'selected' : '' }}>Ready</option>
            <option value="pending" {{ (isset($secondAsset) && $secondAsset->status == 'pending') ? 'selected' : '' }}>Pending</option>
            <option value="undeployable" {{ (isset($secondAsset) && $secondAsset->status == 'undeployable') ? 'selected' : '' }}>Undeployable</option>
            <option value="archived" {{ (isset($secondAsset) && $secondAsset->status == 'archived') ? 'selected' : '' }}>Archived</option>
            <option value="operational" {{ (isset($secondAsset) && $secondAsset->status == 'operational') ? 'selected' : '' }}>Operational</option>
            <option value="non-operational" {{ (isset($secondAsset) && $secondAsset->status == 'non-operational') ? 'selected' : '' }}>Non-Operational</option>
            <option value="repairing" {{ (isset($secondAsset) && $secondAsset->status == 'repairing') ? 'selected' : '' }}>Repairing</option>
        </select>
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('supplier', __('messages.second_assets.supplier').':') }}<span class="required">*</span>
        <select name="supplier" class="form-control" required>
            <option value="">Select Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier }}" {{ (isset($secondAsset) && $secondAsset->supplier == $supplier) ? 'selected' : '' }}>{{ $supplier }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('purchase_date', __('messages.second_assets.purchase_date').':') }}<span class="required">*</span>
        {{ Form::text('purchase_date', isset($secondAsset) ? $secondAsset->purchase_date->format('Y-m-d') : null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('order_number', __('messages.second_assets.order_number').':') }}<span class="required">*</span>
        {{ Form::text('order_number', null, ['class' => 'form-control', 'required']) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('purchase_cost', __('messages.second_assets.purchase_cost').':') }}<span class="required">*</span>
        {{ Form::text('purchase_cost', null, ['class' => 'form-control price-input', 'required']) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('location', __('messages.second_assets.location').':') }}<span class="required">*</span>
        <select name="location" class="form-control" required>
            <option value="">Select Location</option>
            @foreach($locations as $location)
                <option value="{{ $location }}" {{ (isset($secondAsset) && $secondAsset->location == $location) ? 'selected' : '' }}>{{ $location }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('warranty', __('messages.second_assets.warranty').':') }}<span class="required">*</span>
        {{ Form::number('warranty', null, ['class' => 'form-control', 'required', 'min' => 0]) }}
    </div>
    <div class="form-group col-sm-6">
        <label>
            {{ Form::checkbox('requestable', 1, isset($secondAsset) ? $secondAsset->requestable : false, ['class' => 'form-check-input']) }}
            {{ __('messages.second_assets.requestable') }}
        </label>
    </div>
    <div class="form-group col-sm-6">
        <label>
            {{ Form::checkbox('for_sell', 1, isset($secondAsset) ? $secondAsset->for_sell : false, ['class' => 'form-check-input', 'id' => 'forSellCheckbox']) }}
            {{ __('messages.second_assets.for_sell') }}
        </label>
    </div>
    <div class="form-group col-sm-6 sell-price-field" style="{{ isset($secondAsset) && $secondAsset->for_sell ? '' : 'display: none;' }}">
        {{ Form::label('selling_price', __('messages.second_assets.selling_price').':') }}
        {{ Form::text('selling_price', null, ['class' => 'form-control price-input']) }}
    </div>
    <div class="form-group col-sm-6">
        <label>
            {{ Form::checkbox('for_rent', 1, isset($secondAsset) ? $secondAsset->for_rent : false, ['class' => 'form-check-input', 'id' => 'forRentCheckbox']) }}
            {{ __('messages.second_assets.for_rent') }}
        </label>
    </div>
    <div class="rent-fields" style="{{ isset($secondAsset) && $secondAsset->for_rent ? '' : 'display: none;' }}">
        <div class="form-group col-sm-6">
            {{ Form::label('rental_price', __('messages.second_assets.rental_price').':') }}
            {{ Form::text('rental_price', null, ['class' => 'form-control price-input']) }}
        </div>
        <div class="form-group col-sm-6">
            {{ Form::label('minimum_renting_price', __('messages.second_assets.minimum_renting_price').':') }}
            {{ Form::text('minimum_renting_price', null, ['class' => 'form-control price-input']) }}
        </div>
        <div class="form-group col-sm-6">
            {{ Form::label('unit', __('messages.second_assets.unit').':') }}
            <select name="unit" class="form-control">
                <option value="">Select Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit }}" {{ (isset($secondAsset) && $secondAsset->unit == $unit) ? 'selected' : '' }}>{{ $unit }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12">
        {{ Form::label('description', __('messages.second_assets.description').':') }}
        {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'rows' => 5]) }}
    </div>
</div>
