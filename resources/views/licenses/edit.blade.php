@extends('layouts.app')
@section('title')
    {{ __('messages.licenses.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.licenses.edit') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('licenses.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.licenses.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($license, ['route' => ['licenses.update', $license->id], 'method' => 'put', 'id' => 'editForm']) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('software_name', __('messages.licenses.software_name').':') }}<span class="required">*</span>
                            {{ Form::text('software_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('category_name', __('messages.licenses.category_name').':') }}<span class="required">*</span>
                            {{ Form::select('category_name', $categories, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Category']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('product_key', __('messages.licenses.product_key').':') }}<span class="required">*</span>
                            {{ Form::text('product_key', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('seats', __('messages.licenses.seats').':') }}<span class="required">*</span>
                            {{ Form::number('seats', null, ['class' => 'form-control', 'required', 'min' => 1]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('manufacturer', __('messages.licenses.manufacturer').':') }}<span class="required">*</span>
                            {{ Form::select('manufacturer', $manufacturers, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Manufacturer']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('licensed_name', __('messages.licenses.licensed_name').':') }}<span class="required">*</span>
                            {{ Form::text('licensed_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('licensed_email', __('messages.licenses.licensed_email').':') }}<span class="required">*</span>
                            {{ Form::email('licensed_email', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('supplier', __('messages.licenses.supplier').':') }}<span class="required">*</span>
                            {{ Form::select('supplier', $suppliers, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Supplier']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('order_number', __('messages.licenses.order_number').':') }}<span class="required">*</span>
                            {{ Form::text('order_number', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_order_number', __('messages.licenses.purchase_order_number').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_order_number', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_cost', __('messages.licenses.purchase_cost').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_cost', null, ['class' => 'form-control price-input', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_date', __('messages.licenses.purchase_date').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('expiration_date', __('messages.licenses.expiration_date').':') }}
                            {{ Form::text('expiration_date', null, ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('termination_date', __('messages.licenses.termination_date').':') }}
                            {{ Form::text('termination_date', null, ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('depreciation', __('messages.licenses.depreciation').':') }}<span class="required">*</span>
                            {{ Form::select('depreciation', $depreciations, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Depreciation']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="custom-control custom-checkbox mt-4">
                                {{ Form::checkbox('reassignable', 1, null, ['class' => 'custom-control-input', 'id' => 'reassignable']) }}
                                {{ Form::label('reassignable', __('messages.licenses.reassignable'), ['class' => 'custom-control-label']) }}
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="custom-control custom-checkbox mt-4">
                                {{ Form::checkbox('maintained', 1, null, ['class' => 'custom-control-input', 'id' => 'maintained']) }}
                                {{ Form::label('maintained', __('messages.licenses.maintained'), ['class' => 'custom-control-label']) }}
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="custom-control custom-checkbox mt-4">
                                {{ Form::checkbox('for_sell', 1, null, ['class' => 'custom-control-input', 'id' => 'for_sell']) }}
                                {{ Form::label('for_sell', __('messages.licenses.for_sell'), ['class' => 'custom-control-label']) }}
                            </div>
                        </div>
                        <div class="form-group col-sm-6" id="sellingPriceField" style="display: {{ $license->for_sell ? 'block' : 'none' }};">
                            {{ Form::label('selling_price', __('messages.licenses.selling_price').':') }}
                            {{ Form::text('selling_price', null, ['class' => 'form-control price-input']) }}
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('notes', __('messages.licenses.notes').':') }}
                            {{ Form::textarea('notes', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                        </div>
                    </div>
                    <div class="text-right">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnSave']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false,
                icons: {
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right'
                }
            });

            $('#for_sell').change(function() {
                if($(this).is(':checked')) {
                    $('#sellingPriceField').show();
                    $('#selling_price').attr('required', true);
                } else {
                    $('#sellingPriceField').hide();
                    $('#selling_price').removeAttr('required');
                }
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                let loadingButton = $('#btnSave');
                loadingButton.attr('disabled', true);
                loadingButton.html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('licenses.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        loadingButton.attr('disabled', false);
                        loadingButton.html('{{ __('messages.common.save') }}');
                    }
                });
            });
        });
    </script>
@endsection