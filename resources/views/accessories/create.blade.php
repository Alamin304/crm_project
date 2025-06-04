@extends('layouts.app')
@section('title')
    {{ __('messages.accessory.add') }}
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
            <h1>{{ __('messages.accessory.add') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('accessories.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.accessory.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['id' => 'addNewForm', 'files' => true]) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('accessory_name', __('messages.accessory.accessory_name').':') }}<span class="required">*</span>
                            {{ Form::text('accessory_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('category_name', __('messages.accessory.category_name').':') }}<span class="required">*</span>
                            {{ Form::select('category_name', $categories, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Category']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('supplier', __('messages.accessory.supplier').':') }}<span class="required">*</span>
                            {{ Form::select('supplier', $suppliers, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Supplier']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('manufacturer', __('messages.accessory.manufacturer').':') }}<span class="required">*</span>
                            {{ Form::select('manufacturer', $manufacturers, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Manufacturer']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('location', __('messages.accessory.location').':') }}
                            {{ Form::text('location', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('model_number', __('messages.accessory.model_number').':') }}
                            {{ Form::text('model_number', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('order_number', __('messages.accessory.order_number').':') }}
                            {{ Form::text('order_number', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_cost', __('messages.accessory.purchase_cost').':') }}
                            {{ Form::text('purchase_cost', null, ['class' => 'form-control price-input']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_date', __('messages.accessory.purchase_date').':') }}
                            {{ Form::text('purchase_date', null, ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('quantity', __('messages.accessory.quantity').':') }}<span class="required">*</span>
                            {{ Form::number('quantity', 1, ['class' => 'form-control', 'required', 'min' => 1]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('min_quantity', __('messages.accessory.min_quantity').':') }}<span class="required">*</span>
                            {{ Form::number('min_quantity', 0, ['class' => 'form-control', 'required', 'min' => 0]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="custom-control custom-checkbox mt-4">
                                {{ Form::checkbox('for_sell', 1, false, ['class' => 'custom-control-input', 'id' => 'for_sell']) }}
                                {{ Form::label('for_sell', __('messages.accessory.for_sell'), ['class' => 'custom-control-label']) }}
                            </div>
                        </div>
                        <div class="form-group col-sm-6" id="sellingPriceField" style="display: none;">
                            {{ Form::label('selling_price', __('messages.accessory.selling_price').':') }}
                            {{ Form::text('selling_price', null, ['class' => 'form-control price-input']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('image', __('messages.accessory.image').':') }}
                            {{ Form::file('image', ['class' => 'form-control-file', 'accept' => 'image/*']) }}
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('notes', __('messages.accessory.notes').':') }}
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

            $('#addNewForm').submit(function(e) {
                e.preventDefault();
                let loadingButton = $('#btnSave');
                loadingButton.attr('disabled', true);
                loadingButton.html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('accessories.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('accessories.index') }}";
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
