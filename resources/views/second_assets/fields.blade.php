<div class="form-group col-sm-6">
    {{ Form::label('serial_number', __('messages.second_assets.serial_number').':') }}<span class="required">*</span>
    <div class="input-group">
        {{ Form::text('serial_number', null, ['class' => 'form-control', 'required', 'id' => 'serialNumber']) }}
        <div class="input-group-append">
            <button class="btn btn-outline-primary" type="button" id="generateSerialBtn">
                <i class="fas fa-sync-alt"></i> {{ __('messages.second_assets.generate') }}
            </button>
        </div>
    </div>
</div>

<div class="form-group col-sm-6">
    {{ Form::label('asset_name', __('messages.second_assets.asset_name').':') }}<span class="required">*</span>
    {{ Form::text('asset_name', null, ['class' => 'form-control', 'required']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('model', __('messages.second_assets.model').':') }}<span class="required">*</span>
    {{ Form::select('model', $modelOptions, null, ['class' => 'form-control select2', 'required', 'id' => 'model']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('status', __('messages.second_assets.status').':') }}<span class="required">*</span>
    {{ Form::select('status', $statusOptions, 'ready', ['class' => 'form-control select2', 'required', 'id' => 'status']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('supplier', __('messages.second_assets.supplier').':') }}<span class="required">*</span>
    {{ Form::select('supplier', $supplierOptions, null, ['class' => 'form-control select2', 'required', 'id' => 'supplier']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('purchase_date', __('messages.second_assets.purchase_date').':') }}<span class="required">*</span>
    {{ Form::text('purchase_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('order_number', __('messages.second_assets.order_number').':') }}
    {{ Form::text('order_number', null, ['class' => 'form-control']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('purchase_cost', __('messages.second_assets.purchase_cost').':') }}<span class="required">*</span>
    {{ Form::text('purchase_cost', null, ['class' => 'form-control price-input', 'required']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('location', __('messages.second_assets.location').':') }}<span class="required">*</span>
    {{ Form::select('location', $locationOptions, null, ['class' => 'form-control select2', 'required', 'id' => 'location']) }}
</div>

<div class="form-group col-sm-6">
    {{ Form::label('warranty_months', __('messages.second_assets.warranty_months').':') }}
    {{ Form::number('warranty_months', null, ['class' => 'form-control', 'min' => '0']) }}
</div>

<div class="form-group col-sm-6">
    <div class="custom-control custom-checkbox">
        {{ Form::checkbox('requestable', 1, false, ['class' => 'custom-control-input', 'id' => 'requestable']) }}
        {{ Form::label('requestable', __('messages.second_assets.requestable'), ['class' => 'custom-control-label']) }}
    </div>
</div>

<div class="form-group col-sm-6">
    <div class="custom-control custom-checkbox">
        {{ Form::checkbox('for_sale', 1, false, ['class' => 'custom-control-input', 'id' => 'forSale']) }}
        {{ Form::label('for_sale', __('messages.second_assets.for_sale'), ['class' => 'custom-control-label']) }}
    </div>
</div>

<div class="form-group col-sm-6 for-sale-field" style="display: none;">
    {{ Form::label('selling_price', __('messages.second_assets.selling_price').':') }}<span class="required">*</span>
    {{ Form::text('selling_price', null, ['class' => 'form-control price-input']) }}
</div>

<div class="form-group col-sm-6">
    <div class="custom-control custom-checkbox">
        {{ Form::checkbox('for_rent', 1, false, ['class' => 'custom-control-input', 'id' => 'forRent']) }}
        {{ Form::label('for_rent', __('messages.second_assets.for_rent'), ['class' => 'custom-control-label']) }}
    </div>
</div>

<div class="form-group col-sm-6 for-rent-field" style="display: none;">
    {{ Form::label('rental_price', __('messages.second_assets.rental_price').':') }}<span class="required">*</span>
    {{ Form::text('rental_price', null, ['class' => 'form-control price-input']) }}
</div>

<div class="form-group col-sm-6 for-rent-field" style="display: none;">
    {{ Form::label('minimum_renting_days', __('messages.second_assets.minimum_renting_days').':') }}<span class="required">*</span>
    {{ Form::number('minimum_renting_days', null, ['class' => 'form-control', 'min' => '1']) }}
</div>

<div class="form-group col-sm-6 for-rent-field" style="display: none;">
    {{ Form::label('rental_unit', __('messages.second_assets.rental_unit').':') }}<span class="required">*</span>
    {{ Form::select('rental_unit', $rentalUnitOptions, null, ['class' => 'form-control select2', 'id' => 'rentalUnit']) }}
</div>

<div class="form-group col-sm-12">
    {{ Form::label('description', __('messages.second_assets.description').':') }}
    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'description']) }}
</div>

<div class="form-group col-sm-12 text-right">
    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'saveAssetBtn']) }}
    <a href="{{ route('second-assets.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
</div>
