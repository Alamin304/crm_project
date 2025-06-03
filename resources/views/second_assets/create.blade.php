@extends('layouts.app')
@section('title')
    {{ __('messages.second_assets.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.second_assets.add') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('second-assets.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.second_assets.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'second-assets.store', 'id' => 'createSecondAssetForm']) }}
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
                                    <option value="{{ $model }}">{{ $model }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('status', __('messages.second_assets.status').':') }}<span class="required">*</span>
                            <select name="status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="ready">Ready</option>
                                <option value="pending">Pending</option>
                                <option value="undeployable">Undeployable</option>
                                <option value="archived">Archived</option>
                                <option value="operational">Operational</option>
                                <option value="non-operational">Non-Operational</option>
                                <option value="repairing">Repairing</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('supplier', __('messages.second_assets.supplier').':') }}<span class="required">*</span>
                            <select name="supplier" class="form-control" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier }}">{{ $supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_date', __('messages.second_assets.purchase_date').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
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
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('warranty', __('messages.second_assets.warranty').':') }}<span class="required">*</span>
                            {{ Form::number('warranty', null, ['class' => 'form-control', 'required', 'min' => 0]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            <label>
                                {{ Form::checkbox('requestable', 1, false, ['class' => 'form-check-input']) }}
                                {{ __('messages.second_assets.requestable') }}
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>
                                {{ Form::checkbox('for_sell', 1, false, ['class' => 'form-check-input', 'id' => 'forSellCheckbox']) }}
                                {{ __('messages.second_assets.for_sell') }}
                            </label>
                        </div>
                        <div class="form-group col-sm-6 sell-price-field" style="display: none;">
                            {{ Form::label('selling_price', __('messages.second_assets.selling_price').':') }}
                            {{ Form::text('selling_price', null, ['class' => 'form-control price-input']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            <label>
                                {{ Form::checkbox('for_rent', 1, false, ['class' => 'form-check-input', 'id' => 'forRentCheckbox']) }}
                                {{ __('messages.second_assets.for_rent') }}
                            </label>
                        </div>
                        <div class="rent-fields" style="display: none;">
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
                                        <option value="{{ $unit }}">{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('description', __('messages.second_assets.description').':') }}
                            {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'rows' => 5]) }}
                        </div>
                    </div>
                    <div class="text-right">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'saveSecondAssetBtn']) }}
                        <a href="{{ route('second-assets.index') }}" class="btn btn-light ml-1">{{ __('messages.common.cancel') }}</a>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection
@section('scripts')
        <script>
    $(document).on('submit', '#addNewFormSecondAsset', function(event) {
        event.preventDefault();
        processingBtn('#addNewFormSecondAsset', '#btnSave', 'loading');

        // If you have a summernote or similar editor to validate, do it here

        $.ajax({
            url: "{{ route('second_assets.store') }}", // your store route
            type: 'POST',
            data: $(this).serialize(),
            success: function(result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    // Redirect to index page after success
                    window.location.href = "{{ route('second_assets.index') }}";
                } else {
                    displayErrorMessage(result.message);
                }
            },
            error: function(xhr) {
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    displayErrorMessage(xhr.responseJSON.message);
                } else {
                    displayErrorMessage('Something went wrong.');
                }
            },
            complete: function() {
                processingBtn('#addNewFormSecondAsset', '#btnSave', 'reset');
            }
        });
    });

    $(document).ready(function() {
        // Initialize your summernote or other inputs here if needed
        $('.summernote-simple').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        // Your datepicker and checkbox toggling logic from above
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            icons: {
                up: "fas fa-chevron-up",
                down: "fas fa-chevron-down",
                next: 'fas fa-chevron-right',
                previous: 'fas fa-chevron-left'
            }
        });

        $('#forSellCheckbox').change(function() {
            if($(this).is(':checked')) {
                $('.sell-price-field').show();
                $('input[name="selling_price"]').attr('required', true);
            } else {
                $('.sell-price-field').hide();
                $('input[name="selling_price"]').attr('required', false).val('');
            }
        });

        $('#forRentCheckbox').change(function() {
            if($(this).is(':checked')) {
                $('.rent-fields').show();
                $('input[name="rental_price"]').attr('required', true);
                $('input[name="minimum_renting_price"]').attr('required', true);
                $('select[name="unit"]').attr('required', true);
            } else {
                $('.rent-fields').hide();
                $('input[name="rental_price"]').attr('required', false).val('');
                $('input[name="minimum_renting_price"]').attr('required', false).val('');
                $('select[name="unit"]').attr('required', false).val('');
            }
        });
    });
</script>

@endsection
