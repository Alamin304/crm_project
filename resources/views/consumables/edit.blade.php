@extends('layouts.app')
@section('title')
    {{ __('messages.consumables.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.consumables.edit') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('consumables.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.consumables.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($consumable, ['id' => 'editForm', 'files' => true, 'route' => ['consumables.update', $consumable->id], 'method' => 'put']) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('consumable_name', __('messages.consumables.consumable_name').':') }}<span class="required">*</span>
                            {{ Form::text('consumable_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('category_name', __('messages.consumables.category_name').':') }}<span class="required">*</span>
                            {{ Form::select('category_name', $categories, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Category']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('supplier', __('messages.consumables.supplier').':') }}<span class="required">*</span>
                            {{ Form::select('supplier', $suppliers, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Supplier']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('manufacturer', __('messages.consumables.manufacturer').':') }}<span class="required">*</span>
                            {{ Form::select('manufacturer', $manufacturers, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Manufacturer']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('location', __('messages.consumables.location').':') }}
                            {{ Form::text('location', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('model_number', __('messages.consumables.model_number').':') }}
                            {{ Form::text('model_number', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('order_number', __('messages.consumables.order_number').':') }}
                            {{ Form::text('order_number', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_cost', __('messages.consumables.purchase_cost').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_cost', null, ['class' => 'form-control price-input', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_date', __('messages.consumables.purchase_date').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('quantity', __('messages.consumables.quantity').':') }}<span class="required">*</span>
                            {{ Form::number('quantity', null, ['class' => 'form-control', 'required', 'min' => 0]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('min_quantity', __('messages.consumables.min_quantity').':') }}<span class="required">*</span>
                            {{ Form::number('min_quantity', null, ['class' => 'form-control', 'required', 'min' => 0]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="custom-control custom-checkbox mt-4">
                                {{ Form::checkbox('for_sell', 1, null, ['class' => 'custom-control-input', 'id' => 'for_sell']) }}
                                {{ Form::label('for_sell', __('messages.consumables.for_sell'), ['class' => 'custom-control-label']) }}
                            </div>
                        </div>
                        <div class="form-group col-sm-6" id="sellingPriceField" style="{{ $consumable->for_sell ? '' : 'display: none;' }}">
                            {{ Form::label('selling_price', __('messages.consumables.selling_price').':') }}
                            {{ Form::text('selling_price', null, ['class' => 'form-control price-input']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('image', __('messages.consumables.image').':') }}
                            {{ Form::file('image', ['class' => 'form-control', 'accept' => 'image/*']) }}
                            @if($consumable->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$consumable->image) }}" alt="Consumable Image" width="100">
                                    {{-- <a href="javascript:void(0)" class="text-danger ml-2" id="removeImage">Remove Image</a> --}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('notes', __('messages.consumables.notes').':') }}
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

            // $('#removeImage').click(function() {
            //     $.ajax({
            //         url: "{{ route('consumables.remove.image', $consumable->id) }}",
            //         type: 'POST',
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             _method: 'DELETE'
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 displaySuccessMessage(response.message);
            //                 window.location.reload();
            //             }
            //         },
            //         error: function(response) {
            //             displayErrorMessage(response.responseJSON.message);
            //         }
            //     });
            // });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                let loadingButton = $('#btnSave');
                loadingButton.attr('disabled', true);
                loadingButton.html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('consumables.index') }}";
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
